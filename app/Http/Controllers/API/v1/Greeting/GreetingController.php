<?php

namespace YellowProject\Http\Controllers\API\v1\Greeting;

use Illuminate\Http\Request;
use YellowProject\Http\Controllers\Controller;
use YellowProject\Greeting\Greeting;
use YellowProject\Greeting\GreetingItem;
use YellowProject\Greeting\GreetingItemMessage;
use YellowProject\Greeting\GreetingItemSticker;
use YellowProject\LineMessageType;

class GreetingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $greetings  = Greeting::all();
        
        return response()->json([
            'datas'          => $greetings,
            'countGreeting' => $greetings->count()
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

        $dup = Greeting::checkDuplicate($request->title);
        if ($dup) {

            return response()->json([
                'msg_return' => 'VALID_REQUIRE',
                'code_return' => 1,
                'items'       => ['title']
            ]);
        }

        $greeting = Greeting::firstOrNew([
            // 'active'                => false,
            'active'                => $request->active,
            'title'                 => $request->title
        ]);

        if ($greeting->exists) {
            $arrErrsField[] = 'title';

            return response()->json([
                'msg_return' => 'VALID_REQUIRE',
                'code_return' => 1,
                'items'       => ['title']
            ]);

        } else {
            // autoReplyDefault created from 'new'; does not exist in database.
            $greeting->save();
            if (isset($request->items) && sizeof($request->items) > 0) {
                foreach ($request->items as $item) {
                    switch ($item['type']) {
                        case 'text':
                            foreach ($item['value'] as $value) {
                                $greetingMessage = new GreetingItemMessage([
                                    'message' => $value['playload'],
                                    'display' => $value['display'],
                                    //'display'
                                ]);
                            }
                            $greetingMessage->save();
                            $greetingItem = new GreetingItem([
                                'greeting_id' => $greeting->id,
                                'message_type_id'           => $item['type'],
                                'seq_no'                    => $item['seq_no'],
                                'greeting_message_id'     => $greetingMessage->id,
                                'greeting_sticker_id'     => null,
                                'greeting_richmessage_id' => null,
                            ]);
                            $greetingItem->save();
                           break;
                        case 'sticker':
                            foreach ($item['value'] as $value) {
                                $greetingSticker = new GreetingItemSticker([
                                    'packageId' => $value['package_id'],
                                    'stickerId' => $value['stricker_id'],
                                    'display'   => $value['display'],
                                ]);
                            }
                            $greetingSticker->save();
                            $greetingItem = new GreetingItem([
                                'greeting_id' => $greeting->id,
                                'message_type_id'           => $item['type'],
                                'seq_no'                    => $item['seq_no'],
                                'greeting_message_id'     => null,
                                'greeting_sticker_id'     => $greetingSticker->id,
                                'greeting_richmessage_id' => null,
                            ]);    
                            $greetingItem->save();
                           break;
                        case 'imagemap':
                            $greetingItem = new GreetingItem([
                                'greeting_id' => $greeting->id,
                                'message_type_id'           => $item['type'],
                                'seq_no'                    => $item['seq_no'],
                                'greeting_message_id'     => null,
                                'greeting_sticker_id'     => null,
                                'greeting_richmessage_id' => $item['value'][0]['auto_reply_richmessage_id'],
                            ]);
                            $greetingItem->save();
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
        $greeting  = Greeting::find($id);
        if ($greeting) {
            $items  = $greeting->greetingItems;

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
                "id"        => $greeting->id,
                "title"     => $greeting->title,
                "active"    => $greeting->active,
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
        $greeting  = Greeting::find($id);
        if ($greeting) {

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

                $greeting = $greeting->update([
                    'active'  => $request->active,
                    'title'   => $request->title
                ]);
                $greeting  = Greeting::find($id);

                if (isset($request->items) && sizeof($request->items) > 0) {
                  
                    $greeting->greetingItems()->delete();
                        
                    foreach ($request->items as $item) {
                            switch ($item['type']) {
                                case 'text':
                                    foreach ($item['value'] as $value) {
                                        $greetingMessage = new GreetingItemMessage([
                                            'message' => $value['playload'],
                                            'display' => $value['display'],

                                        ]);
                                    }
                                    $greetingMessage->save();
                                    $greetingItem = new GreetingItem([
                                        'greeting_id' => $id,
                                        'message_type_id'           => $item['type'],
                                        'seq_no'                    => $item['seq_no'],
                                        'greeting_message_id'     => $greetingMessage->id,
                                        'greeting_sticker_id'     => null,
                                    ]);
                                    $greetingItem->save();
                                   break;
                                case 'sticker':
                                    foreach ($item['value'] as $value) {
                                        $greetingSticker = new GreetingItemSticker([
                                            'packageId' => $value['package_id'],
                                            'stickerId' => $value['stricker_id'],
                                            'display' => $value['display'],
                                        ]);
                                    }
                                    $greetingSticker->save();
                                    $greetingItem = new GreetingItem([
                                        'greeting_id' => $id,
                                        'message_type_id'           => $item['type'],
                                        'seq_no'                    => $item['seq_no'],
                                        'greeting_message_id'     => null,
                                        'greeting_sticker_id'     => $greetingSticker->id,
                                    ]);    
                                    $greetingItem->save();
                                   break;
                                case 'imagemap':
                                    $greetingItem = new GreetingItem([
                                        'greeting_id' => $greeting->id,
                                        'message_type_id'           => $item['type'],
                                        'seq_no'                    => $item['seq_no'],
                                        'greeting_message_id'     => null,
                                        'greeting_sticker_id'     => null,
                                        'greeting_richmessage_id' => $item['value'][0]['auto_reply_richmessage_id'],
                                    ]);
                                    $greetingItem->save();
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
        $greeting  = Greeting::find($id);
        if ($greeting) {
            if ($greeting->active) {
                return response()->json([
                    'msg_return' => 'ลบไม่สำเร็จ ข้อมูลถูกใช้งานอยู่',
                    'code_return' => 1,
                ]);
            } else {
               $greeting->delete();

                return response()->json([
                    'msg_return' => 'ลบสำเร็จ',
                    'code_return' => 1,
                ]); 
            }
            
        }
    }

    public function postActive(Request $request, $id)
    {

        $greetings = Greeting::all();
        foreach ($greetings as  $greeting) {
            $greeting->update([
                    'active'  => false,
            ]);
        }

        $greeting  = Greeting::find($id);
        if ($greeting) {
            if ($request->active ) {
                //w8 confirm solution
                $greeting->update([
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
