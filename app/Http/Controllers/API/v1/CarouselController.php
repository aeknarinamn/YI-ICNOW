<?php

namespace YellowProject\Http\Controllers\API\v1;

use Illuminate\Http\Request;
use YellowProject\Http\Controllers\Controller;
use YellowProject\Carousel;
use YellowProject\CarouselItem;
use YellowProject\CarouselItemKeyword;
use YellowProject\AutoReplyKeyword;
use YellowProject\AutoReplyKeywordItem;
use YellowProject\AutoReplyKeywordMessage;
use YellowProject\Keyword;
use YellowProject\KeywordFolder;
use Excel;

class CarouselController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $carousels = Carousel::all();
        foreach ($carousels as $key => $carousel) {
            $carouselFolder = $carousel->folder;
        }
        return response()->json([
            'datas' => $carousels,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        //Create Empty Carousel Arrays
        // $carouselDatas = [];
        // Set Location Data
        // $carouselDatas['name'] = $request['name'];
        // $carouselDatas['description'] = $request['description'];
        // Save Location
        // dd($request->items);
        $carousel = Carousel::create($request->all());
        if($request->items){
            foreach ($request->items as $carouselData) {
                $carouselData['carousel_id'] = $carousel->id;
                $carouselItem = CarouselItem::create($carouselData);
                if (array_key_exists('keyword', $carouselData)) {
                    $carouselKeywords = explode('#&', $carouselData['keyword']);
                    foreach ($carouselKeywords as $carouselKeywordData) {
                        $carouselKeywordDatas['carousel_id'] = $carouselItem->id;
                        $carouselKeywordDatas['keyword'] = $carouselKeywordData;
                        CarouselItemKeyword::create($carouselKeywordDatas);
                    }
                }

                if($request->is_autoreply == 1){
                    $keywordFolder = KeywordFolder::where('folder_name',$carouselData['autoreply_folder_name'])->first();
                    if(!$keywordFolder){
                        $keywordFolder = KeywordFolder::create([
                            "folder_name" => $carouselData['autoreply_folder_name'],
                            "description" => $carouselData['autoreply_folder_name']
                        ]);
                    }
                    
                    $autoreplyKeyword = AutoReplyKeyword::create([
                        "title" => $carouselData['autoreply_title'],
                        "active" => 1,
                        "sent_date" => $request->start_date,
                        "last_sent_date" => $request->end_date,
                        "folder_id" => $keywordFolder->id,
                        "report_tag_id" => null,
                    ]);

                    $autoReplyKeywordMessage = AutoReplyKeywordMessage::create([
                        "message" => $carouselData['auto_reply_message'],
                        "display" => $carouselData['auto_reply_message'],
                    ]);

                    $keyword = Keyword::create([
                        "keyword" => $carouselData['auto_reply_keyword'],
                        "active" => 1,
                        "dim_auto_reply_keywords_id" => $autoreplyKeyword->id,
                    ]);

                    AutoReplyKeywordItem::create([
                        "dim_auto_reply_keyword_id" => $autoreplyKeyword->id,
                        "message_type_id" => "text",
                        "seq_no" => 1,
                        "auto_reply_message_id" => $autoReplyKeywordMessage->id,
                        "auto_reply_sticker_id" => null,
                        "auto_reply_richmessage_id" => null,
                    ]);

                    $carouselData['auto_reply_keyword_id'] = $autoreplyKeyword->id;
                    $carouselItem->update($carouselData);
                    // $carouselItem->update([
                    //     'auto_reply_keyword_id' => $autoreplyKeyword->id,
                    // ]);
                }
            }
        }

        return response()->json([
            'msg_return' => 'บันทึกสำเร็จ',
            'code_return' => 1,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $carousel = Carousel::find($id);
        $carouselItems = $carousel->carouselItems;
        return response()->json([
            'carousel' => $carousel,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // dd($request->all());
        $carousel = Carousel::find($id);
        $carousel->update($request->all());
        if($request->items){
            foreach ($request->items as $carouselItemData) {
                $carouselItem = CarouselItem::where('carousel_id',$carousel->id)->where('name',$carouselItemData['name'])->first();
                $carouselItemData['carousel_id'] = $carousel->id;
                if($carouselItem){
                    $carouselKeywordArrays = [];
                    $carouselKeywordArrays = CarouselItemKeyword::where('carousel_id',$carouselItem->id)->pluck('keyword')->toArray();
                    $carouselItem->update($carouselItemData);
                    if (array_key_exists('keyword', $carouselItemData)) {
                        $carouselKeywords = explode('#&', $carouselItemData['keyword']);
                        foreach ($carouselKeywords as $carouselKeywordData) {
                            if (in_array($carouselKeywordData, $carouselKeywordArrays)) {
                                if(($key = array_search($carouselKeywordData, $carouselKeywordArrays)) !== false) {
                                    unset($carouselKeywordArrays[$key]);
                                }
                                // dd($carouselKeywordArrays);
                            }else{
                                // dd($carouselKeywordArrays);
                                // dd(in_array($carouselKeywordData, $carouselKeywordArrays));
                                $carouselKeywordDatas['carousel_id'] = $carouselItem->id;
                                $carouselKeywordDatas['keyword'] = $carouselKeywordData;
                                CarouselItemKeyword::create($carouselKeywordDatas);
                            }
                            
                        }
                    }
                    CarouselItemKeyword::where('carousel_id',$carouselItem->id)->whereIn('keyword',$carouselKeywordArrays)->delete();
                }else{
                    $carouselItem = CarouselItem::create($carouselItemData);
                    if (array_key_exists('keyword', $carouselItemData)) {
                        $carouselKeywords = explode('#&', $carouselItemData['keyword']);
                        foreach ($carouselKeywords as $carouselKeywordData) {
                            $carouselKeywordDatas['carousel_id'] = $carouselItem->id;
                            $carouselKeywordDatas['keyword'] = $carouselKeywordData;
                            CarouselItemKeyword::create($carouselKeywordDatas);
                        }
                    }
                }
            }
        }

        return response()->json([
            'msg_return' => 'บันทึกสำเร็จ',
            'code_return' => 1,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $carousel = Carousel::find($id);
        $carouselItems = CarouselItem::where('carousel_id',$carousel->id)->get();
        foreach ($carouselItems as $carouselItem) {
            CarouselItemKeyword::where('carousel_id',$carouselItem->id)->delete();
            $autoReplyKeywords = AutoReplyKeyword::where('id',$carouselItem->auto_reply_keyword_id)->get();
            foreach ($autoReplyKeywords as $key => $autoReplyKeyword) {
                Keyword::where('dim_auto_reply_keywords_id',$autoReplyKeyword->id)->forceDelete();
                $autoReplyKeywordItems = AutoReplyKeywordItem::where('dim_auto_reply_keyword_id',$autoReplyKeyword->id)->get();
                foreach ($autoReplyKeywordItems as $key => $autoReplyKeywordItem) {
                    AutoReplyKeywordMessage::where('id',$autoReplyKeywordItem->auto_reply_message_id)->delete();
                    $autoReplyKeywordItem->delete();
                }
                $autoReplyKeyword->delete();
            }
        }
        CarouselItem::where('carousel_id',$carousel->id)->delete();
        $carousel->delete();
        return response()->json([
            'msg_return' => 'ลบสำเร็จ',
            'code_return' => 1,
        ]);
    }

    public function updateSingleRow(Request $request)
    {
        $carouselItemId = $request->carousel_item_id;
        $carouselItem = CarouselItem::find($carouselItemId);
        $carousel = $carouselItem->carousel;
        if($carousel->is_autoreply == 1){
            $autoReplyKeyword = AutoReplyKeyword::find($carouselItem->auto_reply_keyword_id);
            if($autoReplyKeyword){
                $autoReplyKeyword->update([
                    "title" => $request->autoreply_title,
                ]);
                $autoReplyKeywordItem = AutoReplyKeywordItem::where('dim_auto_reply_keyword_id',$autoReplyKeyword->id)->first();
                $autoReplyKeywordMessage = AutoReplyKeywordMessage::find($autoReplyKeywordItem->auto_reply_message_id);
                $autoReplyKeywordMessage->update([
                    "message" => $request->auto_reply_message,
                    "display" => $request->auto_reply_message,
                ]);
                $keyword = Keyword::where('dim_auto_reply_keywords_id',$autoReplyKeyword->id)->first();
                $keyword->update([
                    "keyword" => $request->auto_reply_keyword,
                ]);
            }
        }
        $carouselKeywordArrays = [];
        $carouselKeywordArrays = CarouselItemKeyword::where('carousel_id',$carouselItem->id)->pluck('keyword')->toArray();
        $carouselItem->update($request->all());
        $carouselKeywords = explode('#&', $request['keyword']);
        foreach ($carouselKeywords as $carouselKeywordData) {
            if (in_array($carouselKeywordData, $carouselKeywordArrays)) {
                if(($key = array_search($carouselKeywordData, $carouselKeywordArrays)) !== false) {
                    unset($carouselKeywordArrays[$key]);
                }
            }else{
                $carouselKeywordDatas['carousel_id'] = $carouselItem->id;
                $carouselKeywordDatas['keyword'] = $carouselKeywordData;
                CarouselItemKeyword::create($carouselKeywordDatas);
            }
            
        }

        CarouselItemKeyword::where('carousel_id',$carouselItem->id)->whereIn('keyword',$carouselKeywordArrays)->delete();

        return response()->json([
            'msg_return' => 'บันทึกสำเร็จ',
            'code_return' => 1,
        ]);
    }

    public function exportCarousel(Request $request)
    {
        $carouselId = $request->carousel_id;
        $carousel = Carousel::find($carouselId);
        $carouselItems = $carousel->carouselItems;
        $dataExports = $carouselItems->toArray();

        $dateNow = \Carbon\Carbon::now()->format('dmY_His');

        Excel::create('carousel'.$dateNow, function($excel) use ($dataExports) {
            $excel->sheet('sheet1', function($sheet) use ($dataExports)
            {
                $sheet->fromArray($dataExports);
            });
        })->download('xlsx');
    }
}
