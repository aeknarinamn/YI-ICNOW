<?php

namespace YellowProject\Http\Controllers\API\v1;

use Illuminate\Http\Request;
use YellowProject\Http\Controllers\Controller;
use YellowProject\RichMessageMain;
use YellowProject\RichMessageItem;

class RichMessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $datas = [];
        $richMessages = RichMessageMain::all();
        foreach ($richMessages as $key => $richMessage) {
            $datas[$key]['folder_name'] = $richMessage->folder->name;
            $datas[$key] = $richMessage->toArray();
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
        // dd($request->all());
        // dd(\Request::root());
        $saveImageRichMessage = RichMessageItem::saveImageRichmesage($request['original_photo_path']);
        $request['rich_message_url'] = \Request::root().'/images/richmessage/'.$saveImageRichMessage;
        $richMessage = RichMessageMain::create($request->all());
        foreach ($request->rich_message_items as $richMessageItems) {
            $richMessageItems['x'] = ceil($richMessageItems['x']*2);
            $richMessageItems['y'] = ceil($richMessageItems['y']*2);
            $richMessageItems['height'] = ceil($richMessageItems['height']*2);
            $richMessageItems['width'] = ceil($richMessageItems['width']*2);
            $richMessageItems['rich_message_id'] = $richMessage->id;
            RichMessageItem::create($richMessageItems);
        }
        // $comeback_url = \Request::root().$request->comeback_url;
        $comeback_url = $request->comeback_url;
        return redirect($comeback_url);
        // dd($saveImageRichMessage);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $richMessage = RichMessageMain::find($id);
        $richMessageItems = $richMessage->richMessageItems;
        return response()->json([
            'data' => $richMessage
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
        $richMessage = RichMessageMain::find($id);
        $richMessageItemArraysIds = $richMessage->richMessageItems->pluck('id')->toArray();
        if($request->original_photo_path){
            $saveImageRichMessage = RichMessageItem::saveImageRichmesage($request->original_photo_path);
            $request['rich_message_url'] = \Request::root().'/images/richmessage/'.$saveImageRichMessage;
        }
        $richMessage->update($request->all());
        foreach ($request->rich_message_items as $richMessageItems) {
            $richMessageItems['x'] = ceil($richMessageItems['x']*2);
            $richMessageItems['y'] = ceil($richMessageItems['y']*2);
            $richMessageItems['height'] = ceil($richMessageItems['height']*2);
            $richMessageItems['width'] = ceil($richMessageItems['width']*2);
            if($richMessageItems['id'] == 0){
                $richMessageItems['rich_message_id'] = $richMessage->id;
                RichMessageItem::create($richMessageItems);
            }else{
                $richMessageDatas = RichMessageItem::find($richMessageItems['id']);
                $richMessageDatas->update($richMessageItems);
                if(($key = array_search($richMessageItems['id'], $richMessageItemArraysIds)) !== false) {
                    unset($richMessageItemArraysIds[$key]);
                }
            }
        }
        RichMessageItem::whereIn('id',$richMessageItemArraysIds)->delete();

        $comeback_url = $request->comeback_url;
        return redirect($comeback_url);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        $richMessage = RichMessageMain::find($id);
        $richMessage->delete();
        RichMessageItem::where('rich_message_id',$id)->delete();

        // $comeback_url = $request->comeback_url;
        // return redirect($comeback_url);
        return response()->json([
            'msg_return' => 'ลบสำเร็จ',
            'code_return' => 1,
        ]);
    }
}
