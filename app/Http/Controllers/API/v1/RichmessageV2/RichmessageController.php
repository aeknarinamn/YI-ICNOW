<?php

namespace YellowProject\Http\Controllers\API\v1\RichmessageV2;

use Illuminate\Http\Request;
use YellowProject\Http\Controllers\Controller;
use YellowProject\RichmessageV2\CoreRichmessage;
use YellowProject\RichmessageV2\Richmessage;
use YellowProject\RichmessageV2\RichmessageArea;
use YellowProject\RichmessageV2\RichmessageAreaAction;
use YellowProject\RichmessageV2\RichmessageAreaBounds;
use YellowProject\AutoReplyDefaultItem;
use YellowProject\AutoReplyKeywordItem;
use YellowProject\CampaignItem;
use YellowProject\RichmessageV2\RichmessageUpload;
use Carbon\Carbon;
use URL;

class RichmessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $datas = [];
        $richMessages = Richmessage::orderByDesc('updated_at')->get();
        $autoReplyDefaultItem = AutoReplyDefaultItem::orderByDesc('created_at');
        $autoReplyKeywordItem = AutoReplyKeywordItem::orderByDesc('created_at');
        $campaignItem = CampaignItem::orderByDesc('created_at');
        foreach ($richMessages as $key => $richMessage) {
            $checkAutoreplyDefault = clone $autoReplyDefaultItem;
            $checkAutoreplyDefault->where('auto_reply_richmessage_id',$richMessage->id)->first();
            $checkAutoReplyKeyword = clone $autoReplyKeywordItem;
            $checkAutoReplyKeyword->where('auto_reply_richmessage_id',$richMessage->id)->first();
            $checkCampaign = clone $campaignItem;
            $checkCampaign->where('campaign_richmessage_id',$richMessage->id)->first();
            $datas[$key] = $richMessage->toArray();
            $datas[$key]['is_use'] = 0;
            if($checkAutoreplyDefault){
                $datas[$key]['is_use'] = 1;
            }
            if($checkAutoReplyKeyword){
                $datas[$key]['is_use'] = 1;
            }
            if($checkCampaign){
                $datas[$key]['is_use'] = 1;
            }
        }

        return response()->json([
            'datas' => $datas,
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
        $richMessageCheckName = Richmessage::where('name',$request->name)->first();
        if($richMessageCheckName){
          return response()->json([
              'msg_return' => 'Duplicate Name',
              'code_return' => 2,
          ]);
        }

        $saveImageRichMessage = CoreRichmessage::saveImageRichmesage($request['img_url']);
        $request['rich_message_url'] = \Request::root().'/images/richmessage/'.$saveImageRichMessage;
        $request['height'] = $request['size']['height'];
        $request['width'] = $request['size']['width'];
        $request['original_photo_path'] = $request['img_url'];
        $request['alt_text'] = $request['chatBarText'];
        $richMessage = Richmessage::create($request->all());
        foreach ($request->areas as $key => $areas) {
            $richmessageArea = RichmessageArea::create([
                'rich_message_id' => $richMessage->id,
            ]);
            $areas['action']['rich_message_areas_id'] = $richmessageArea->id;
            $areas['bounds']['rich_message_areas_id'] = $richmessageArea->id;
            RichmessageAreaAction::create($areas['action']);
            RichmessageAreaBounds::create($areas['bounds']);
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
        $richMessage = Richmessage::find($id);
        $dataRichmessage['id'] = $richMessage->id;
        $dataRichmessage['chatBarText'] = $richMessage->alt_text;
        $dataRichmessage['img_url'] = $richMessage->original_photo_path;
        $dataRichmessage['name'] = $richMessage->name;
        $dataRichmessage['size']['height'] = $richMessage->height;
        $dataRichmessage['size']['width'] = $richMessage->width;
        $richMessageAreas = $richMessage->areas;
        foreach ($richMessageAreas as $key => $richMessageArea) {
            $action = $richMessageArea->action;
            $dataRichmessage['areas'][$key]['action']['data'] = $action->data;
            $dataRichmessage['areas'][$key]['action']['type'] = $action->type;
            $bound = $richMessageArea->bound;
            $dataRichmessage['areas'][$key]['bounds']['height'] = $bound->height;
            $dataRichmessage['areas'][$key]['bounds']['width'] = $bound->width;
            $dataRichmessage['areas'][$key]['bounds']['x'] = $bound->x;
            $dataRichmessage['areas'][$key]['bounds']['y'] = $bound->y;
        }

        return response()->json([
            'datas' => $dataRichmessage,
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
        $richMessage = Richmessage::find($id);
        $richMessageCheckName = Richmessage::where('name',$request->name)->first();
        if($richMessageCheckName && $request->name != $richMessage->name){
          return response()->json([
              'msg_return' => 'Duplicate Name',
              'code_return' => 2,
          ]);
        }
        
        $saveImageRichMessage = CoreRichmessage::saveImageRichmesage($request['img_url']);
        $request['rich_message_url'] = \Request::root().'/images/richmessage/'.$saveImageRichMessage;
        $request['height'] = $request['size']['height'];
        $request['width'] = $request['size']['width'];
        $request['original_photo_path'] = $request['img_url'];
        $request['alt_text'] = $request['chatBarText'];
        $richMessage->update($request->all());
        $richMessageAreas = $richMessage->areas;
        if($richMessageAreas){
            foreach ($richMessageAreas as $key => $richMessageArea) {
                RichmessageAreaAction::where('rich_message_areas_id',$richMessageArea->id)->delete();
                RichmessageAreaBounds::where('rich_message_areas_id',$richMessageArea->id)->delete();
                $richMessageArea->delete();
            }
        }

        foreach ($request->areas as $key => $areas) {
            $richmessageArea = RichmessageArea::create([
                'rich_message_id' => $richMessage->id,
            ]);
            $areas['action']['rich_message_areas_id'] = $richmessageArea->id;
            $areas['bounds']['rich_message_areas_id'] = $richmessageArea->id;
            RichmessageAreaAction::create($areas['action']);
            RichmessageAreaBounds::create($areas['bounds']);
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
        $richMessage = Richmessage::find($id);
        $richMessageAreas = $richMessage->areas;
        if($richMessageAreas){
            foreach ($richMessageAreas as $key => $richMessageArea) {
                RichmessageAreaAction::where('rich_message_areas_id',$richMessageArea->id)->delete();
                RichmessageAreaBounds::where('rich_message_areas_id',$richMessageArea->id)->delete();
                $richMessageArea->delete();
            }
        }
        $richMessage->delete();

        return response()->json([
            'msg_return' => 'ลบข้อมูลสำเร็จ',
            'code_return' => 1,
        ]);
    }

    public function uploadMultiple(Request $request)
    {
        // dd($request->all());
        $path = 'file_uploads/richmessage';
        RichmessageUpload::checkFolderDefaultPath($path);
        $datas = collect();
        if($request->img_items){
            foreach ($request->img_items as $key => $img_item) {
                $dateNow = Carbon::now()->format('dmY_His');
                $fileImage = $img_item;
                $type = null;
                // ImageFile::checkFolderDefaultPath();
                $destinationPath = $path; // upload path
                $extension = $fileImage->getClientOriginalExtension(); // getting image extension
                $fileName = $dateNow.'-'.$key.'.'.$extension; // renameing image
                $fileImage->move($destinationPath, $fileName); // uploading file to given path

                $imageFile = RichmessageUpload::create([
                    'img_url' => URL::to('/')."/".$destinationPath."/".$fileName,
                    'img_size' => null,
                ]);

                $datas->put($key,$imageFile);
            }
        }

        return response()->json([
            'msg_return' => 'บันทึกสำเร็จ',
            'code_return' => 1,
            'datas' => $datas,
        ]);
    }
}
