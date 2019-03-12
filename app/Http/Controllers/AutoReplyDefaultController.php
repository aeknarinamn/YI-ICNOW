<?php

namespace YellowProject\Http\Controllers;

use Illuminate\Http\Request;
use YellowProject\AutoReplyDefault;
use YellowProject\AutoReplyDefaultItem;
use YellowProject\AutoReplyDefaultMessage;
use YellowProject\AutoReplyDefaultSticker;
use YellowProject\LineMessageType;

class AutoReplyDefaultController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $autoReplyDefaults  = AutoReplyDefault::all();
        
        return response()->json([
            'datas'          => $autoReplyDefaults,
            'countAutoReply' => $autoReplyDefaults->count()
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
        $autoReplyDefault = AutoReplyDefault::where('active',1)->first();
        if($request->active == 1 && $autoReplyDefault){
            return response()->json([
                'msg_return' => 'AUTOREPLY_DEFAULT_IS_ACTIVE',
                'code_return' => 2,
            ]);
        }
        $arrErrsField = array();
        $colectErrsField= collect();
        if (!isset($request->title) || (isset($request->title) && trim($request->title) == "") ) {
            $arrErrsField[] = 'title';
        }
        if (sizeof($arrErrsField) > 0) {

            return response()->json([
                'msg_return' => 'VALID_REQUIRE',
                'code_return' => 1,
                'items'       => $arrErrsField
            ]);
        }

        $dup = AutoReplyDefault::checkDuplicate($request->title);
        if ($dup) {

            return response()->json([
                'msg_return' => 'VALID_REQUIRE',
                'code_return' => 1,
                'items'       => ['title']
            ]);
        }

        $autoReplyDefault = AutoReplyDefault::firstOrNew([
            // 'active'                => false,
            'active'                => $request->active,
            'title'                 => $request->title
        ]);

        if ($autoReplyDefault->exists) {
            $arrErrsField[] = 'title';

            return response()->json([
                'msg_return' => 'VALID_REQUIRE',
                'code_return' => 1,
                'items'       => ['title']
            ]);

        } else {
            // autoReplyDefault created from 'new'; does not exist in database.
            $autoReplyDefault->save();
            if (isset($request->items) && sizeof($request->items) > 0) {
                foreach ($request->items as $item) {
                    switch ($item['type']) {
                        case 'text':
                            foreach ($item['value'] as $value) {
                                $autoReplyDefaultMessage = new AutoReplyDefaultMessage([
                                    'message' => $value['playload'],
                                    'display' => $value['display'],
                                    //'display'
                                ]);
                            }
                            $autoReplyDefaultMessage->save();
                            $autoReplyDefaultItem = new AutoReplyDefaultItem([
                                'dim_auto_reply_default_id' => $autoReplyDefault->id,
                                'message_type_id'           => $item['type'],
                                'seq_no'                    => $item['seq_no'],
                                'auto_reply_message_id'     => $autoReplyDefaultMessage->id,
                                'auto_reply_sticker_id'     => null,
                                'auto_reply_richmessage_id' => null,
                            ]);
                            $autoReplyDefaultItem->save();
                           break;
                        case 'sticker':
                            foreach ($item['value'] as $value) {
                                $autoReplyDefaultSticker = new AutoReplyDefaultSticker([
                                    'packageId' => $value['package_id'],
                                    'stickerId' => $value['stricker_id'],
                                    'display'   => $value['display'],
                                ]);
                            }
                            $autoReplyDefaultSticker->save();
                            $autoReplyDefaultItem = new AutoReplyDefaultItem([
                                'dim_auto_reply_default_id' => $autoReplyDefault->id,
                                'message_type_id'           => $item['type'],
                                'seq_no'                    => $item['seq_no'],
                                'auto_reply_message_id'     => null,
                                'auto_reply_sticker_id'     => $autoReplyDefaultSticker->id,
                                'auto_reply_richmessage_id' => null,
                            ]);    
                            $autoReplyDefaultItem->save();
                           break;
                        case 'imagemap':
                            $autoReplyDefaultItem = new AutoReplyDefaultItem([
                                'dim_auto_reply_default_id' => $autoReplyDefault->id,
                                'message_type_id'           => $item['type'],
                                'seq_no'                    => $item['seq_no'],
                                'auto_reply_message_id'     => null,
                                'auto_reply_sticker_id'     => null,
                                'auto_reply_richmessage_id' => $item['value'][0]['auto_reply_richmessage_id'],
                            ]);
                            $autoReplyDefaultItem->save();
                           break;
                       default:
                           # code...
                           break;
                    }
                }
            }

            return response()->json([
                'msg_return' => 'INSERT_SUCCESS',
                'code_return' => 1,
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $autoReplyDefault  = AutoReplyDefault::find($id);
        if ($autoReplyDefault) {
            $items  = $autoReplyDefault->autoReplyDefaultItems;

            $arr = array();
            foreach ($items as $item) {
               $arr[] = array(
                    "id" => $item->id ,
                    "seq_no" => $item->seq_no,
                    "type" => $item->messageType->type,
                    "value" => array(
                        $item->show_message
                    )
                );
            }

            $data  = array(
                "id"        => $autoReplyDefault->id,
                "title"     => $autoReplyDefault->title,
                "active"    => $autoReplyDefault->active,
                "items"     => $arr
            );

    
            return json_encode($data, JSON_UNESCAPED_UNICODE);
        }

        return response()->json([
            'msg_return' => 'ERR_NOTFOUND',
            'code_return' => 1,
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
        
        $autoReplyDefault  = AutoReplyDefault::find($id);
        $autoReplyDefaultActive = AutoReplyDefault::where('active',1)->first();
        if($autoReplyDefaultActive && $request->active == 1){
            if($autoReplyDefault->active != 1){
                return response()->json([
                    'msg_return' => 'AUTOREPLY_DEFAULT_IS_ACTIVE',
                    'code_return' => 2,
                ]);
            }
        }
        if ($autoReplyDefault) {

                $arrErrsField = array();
                $colectErrsField= collect();

                if (!isset($request->title) || (isset($request->title) && trim($request->title) == "") ) {
                    $arrErrsField[] = 'title';
                }

                if (sizeof($arrErrsField) > 0) {
                    
                    return response()->json([
                        'msg_return' => 'VALID_REQUIRE',
                        'code_return' => 1,
                        'items'       => ['title']
                    ]);
                }

                $autoReplyDefault = $autoReplyDefault->update([
                    'active'  => $request->active,
                    'title'                 => $request->title
                ]);
                $autoReplyDefault  = AutoReplyDefault::find($id);

                if (isset($request->items) && sizeof($request->items) > 0) {
                  
                    $autoReplyDefault->autoReplyDefaultItems()->delete();
                        
                    foreach ($request->items as $item) {
                            switch ($item['type']) {
                                case 'text':
                                    foreach ($item['value'] as $value) {
                                        $autoReplyDefaultMessage = new AutoReplyDefaultMessage([
                                            'message' => $value['playload'],
                                            'display' => $value['display'],

                                        ]);
                                    }
                                    $autoReplyDefaultMessage->save();
                                    $autoReplyDefaultItem = new AutoReplyDefaultItem([
                                        'dim_auto_reply_default_id' => $id,
                                        'message_type_id'           => $item['type'],
                                        'seq_no'                    => $item['seq_no'],
                                        'auto_reply_message_id'     => $autoReplyDefaultMessage->id,
                                        'auto_reply_sticker_id'     => null,
                                    ]);
                                    $autoReplyDefaultItem->save();
                                   break;
                                case 'sticker':
                                    foreach ($item['value'] as $value) {
                                        $autoReplyDefaultSticker = new AutoReplyDefaultSticker([
                                            'packageId' => $value['package_id'],
                                            'stickerId' => $value['stricker_id'],
                                            'display' => $value['display'],
                                        ]);
                                    }
                                    $autoReplyDefaultSticker->save();
                                    $autoReplyDefaultItem = new AutoReplyDefaultItem([
                                        'dim_auto_reply_default_id' => $id,
                                        'message_type_id'           => $item['type'],
                                        'seq_no'                    => $item['seq_no'],
                                        'auto_reply_message_id'     => null,
                                        'auto_reply_sticker_id'     => $autoReplyDefaultSticker->id,
                                    ]);    
                                    $autoReplyDefaultItem->save();
                                   break;
                                case 'imagemap':
                                    $autoReplyDefaultItem = new AutoReplyDefaultItem([
                                        'dim_auto_reply_default_id' => $autoReplyDefault->id,
                                        'message_type_id'           => $item['type'],
                                        'seq_no'                    => $item['seq_no'],
                                        'auto_reply_message_id'     => null,
                                        'auto_reply_sticker_id'     => null,
                                        'auto_reply_richmessage_id' => $item['value'][0]['auto_reply_richmessage_id'],
                                    ]);
                                    $autoReplyDefaultItem->save();
                                   break;
                               default:
                                   # code...
                               break;
                            }
                    }

                    return response()->json([
                        'msg_return' => 'UPDATE_SUCCESS',
                        'code_return' => 1,
                    ]);
                }
        }

        return response()->json([
            'msg_return' => 'ERR_NOTFOUND',
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
        $autoReplyDefault  = AutoReplyDefault::find($id);
        if ($autoReplyDefault) {
            if ($autoReplyDefault->active) {
                return response()->json([
                    'msg_return' => 'ลบไม่สำเร็จ ข้อมูลถูกใช้งานอยู่',
                    'code_return' => 1,
                ]);
            } else {
               $autoReplyDefault->delete();

                return response()->json([
                    'msg_return' => 'ลบสำเร็จ',
                    'code_return' => 1,
                ]); 
            }
            
        }
    }

    public function postActive(Request $request, $id)
    {

        $autoReplyDefaults = AutoReplyDefault::all();
        foreach ($autoReplyDefaults as  $autoReplyDefault) {
            $autoReplyDefault->update([
                    'active'  => false,
            ]);
        }

        $autoReplyDefault  = AutoReplyDefault::find($id);
        if ($autoReplyDefault) {
            if ($request->active ) {
                //w8 confirm solution
                $autoReplyDefault->update([
                    'active'  => true,
                ]);

                return response()->json([
                    'msg_return' => 'บันทึกสำเร็จ',
                    'code_return' => 1,
                ]);
            }
        }
    }
    
}
