<?php

namespace YellowProject\Http\Controllers\API\v1\ShareLocation;

use Illuminate\Http\Request;
use YellowProject\Http\Controllers\Controller;
use YellowProject\ShareLocation\ShareLocation;
use YellowProject\ShareLocation\ShareLocationItem;
use YellowProject\AutoReplyKeyword;
use YellowProject\AutoReplyKeywordItem;
use YellowProject\AutoReplyKeywordMessage;
use YellowProject\Keyword;
use YellowProject\KeywordFolder;
use Excel;

class ShareLocationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $shareLocations = ShareLocation::all();
        foreach ($shareLocations as $key => $shareLocation) {
            $shareLocationFolder = $shareLocation->folder;
        }
        return response()->json([
            'datas' => $shareLocations,
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
        $shareLocation = ShareLocation::create($request->all());
        if($request->items){
            foreach ($request->items as $shareLocationData) {
                $shareLocationData['share_location_id'] = $shareLocation->id;

                if($request->is_autoreply == 1){
                    $keywordFolder = KeywordFolder::where('folder_name',$shareLocationData['autoreply_folder_name'])->first();
                    if(!$keywordFolder){
                        $keywordFolder = KeywordFolder::create([
                            "folder_name" => $shareLocationData['autoreply_folder_name'],
                            "description" => $shareLocationData['autoreply_folder_name']
                        ]);
                    }
                    
                    $autoreplyKeyword = AutoReplyKeyword::create([
                        "title" => $shareLocationData['autoreply_title'],
                        "active" => 1,
                        "sent_date" => $request->start_date,
                        "last_sent_date" => $request->end_date,
                        "folder_id" => $keywordFolder->id,
                        "report_tag_id" => null,
                    ]);

                    $shareLocationData['auto_reply_keyword_id'] = $autoreplyKeyword->id;

                    $autoReplyKeywordMessage = AutoReplyKeywordMessage::create([
                        "message" => $shareLocationData['auto_reply_message'],
                        "display" => $shareLocationData['auto_reply_message'],
                    ]);

                    $keyword = Keyword::create([
                        "keyword" => $shareLocationData['auto_reply_keyword'],
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
                }

                $shareLocationlItem = ShareLocationItem::create($shareLocationData);
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
        $shareLocation = ShareLocation::find($id);
        $shareLocationItems = $shareLocation->shareLocationItems;
        return response()->json([
            'carousel' => $shareLocation,
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
        $shareLocation = ShareLocation::find($id);
        $shareLocation->update($request->all());
        if($request->items){
            foreach ($request->items as $shareLocationlItemData) {
                $shareLocationItem = ShareLocationItem::where('share_location_id',$shareLocation->id)->where('name',$shareLocationlItemData['name'])->first();
                $shareLocationlItemData['share_location_id'] = $shareLocation->id;
                if($shareLocationItem){
                    $shareLocationItem->update($shareLocationlItemData);
                }else{
                    $shareLocationItem = ShareLocationItem::create($shareLocationlItemData);
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
        $shareLocation = ShareLocation::find($id);
        $shareLocationItems = ShareLocationItem::where('share_location_id',$shareLocation->id)->get();
        foreach ($shareLocationItems as $key => $shareLocationItem) {
            $autoReplyKeywords = AutoReplyKeyword::where('id',$shareLocationItem->auto_reply_keyword_id)->get();
            foreach ($autoReplyKeywords as $key => $autoReplyKeyword) {
                Keyword::where('dim_auto_reply_keywords_id',$autoReplyKeyword->id)->forceDelete();
                $autoReplyKeywordItems = AutoReplyKeywordItem::where('dim_auto_reply_keyword_id',$autoReplyKeyword->id)->get();
                foreach ($autoReplyKeywordItems as $key => $autoReplyKeywordItem) {
                    AutoReplyKeywordMessage::where('id',$autoReplyKeywordItem->auto_reply_message_id)->delete();
                    $autoReplyKeywordItem->delete();
                }
                $autoReplyKeyword->delete();
            }
            $shareLocationItem->delete();
        }
        $shareLocation->delete();
        
        return response()->json([
            'msg_return' => 'ลบสำเร็จ',
            'code_return' => 1,
        ]);
    }

    public function updateSingleRow(Request $request)
    {
        $sharelocationItemId = $request->share_location_item_id;
        $shareLocationItem = ShareLocationItem::find($sharelocationItemId);
        $shareLocation = $shareLocationItem->shareLocation;
        if($shareLocation->is_autoreply == 1){
            $autoReplyKeyword = AutoReplyKeyword::find($shareLocationItem->auto_reply_keyword_id);
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
        $shareLocationItem->update($request->all());

        return response()->json([
            'msg_return' => 'บันทึกสำเร็จ',
            'code_return' => 1,
        ]);
    }

    public function exportShareLocation(Request $request)
    {
        $shareLocationId = $request->share_location_id;
        $shareLocation = ShareLocation::find($shareLocationId);
        $shareLocationItems = $shareLocation->shareLocationItems;
        $dataExports = $shareLocationItems->toArray();

        $dateNow = \Carbon\Carbon::now()->format('dmY_His');

        Excel::create('share_location'.$dateNow, function($excel) use ($dataExports) {
            $excel->sheet('sheet1', function($sheet) use ($dataExports)
            {
                $sheet->fromArray($dataExports);
            });
        })->download('xlsx');
    }
}
