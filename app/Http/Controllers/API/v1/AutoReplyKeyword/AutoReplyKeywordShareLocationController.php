<?php

namespace YellowProject\Http\Controllers\API\v1\AutoReplyKeyword;

use Illuminate\Http\Request;
use YellowProject\Http\Controllers\Controller;
use YellowProject\AutoReplyKeyword;
use YellowProject\AutoReplyKeywordItem;
use YellowProject\AutoReplyKeywordMessage;
use YellowProject\AutoReplyKeywordSticker;
use YellowProject\Keyword;

class AutoReplyKeywordShareLocationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $autoReplyKeywords  = AutoReplyKeyword::has('shareLocationItems')->get();
       
        return response()->json([
            'datas'          => $autoReplyKeywords,
            'countAutoReply' => $autoReplyKeywords->count(),
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
        //dd($request->all());
        $arrErrsField = array();
        $colectErrsField= collect();
        if (!isset($request->title) || (isset($request->title) && trim($request->title) == "") ) {
            $arrErrsField[] = 'title';
        }

        if (!isset($request->keywords) || (isset($request->keywords) && sizeof($request->keywords) == 0) ) {
            $arrErrsField[] = 'keywords';
        }

        if (sizeof($arrErrsField) > 0) {
           
            return response()->json([
                'msg_return' => 'VALID_REQUIRE',
                'code_return' => 1,
                'items'       => $arrErrsField
            ]);
        }

        $dup = AutoReplyKeyword::checkDuplicate($request->title);
        if ($dup) {
           
            return response()->json([
                'msg_return' => 'ERR_DUPPLICATE',
                'code_return' => 1,
                'items'       => ['title']
            ]);
        }

        $dupKeyword = Keyword::checkDuplicate($request->keywords);
        if ($dupKeyword) {
          
            return response()->json([
                'msg_return' => 'ERR_DUPPLICATE',
                'code_return' => 1,
                'items'       => ['keywords']
            ]);
        }

        $autoReplyKeyword = AutoReplyKeyword::firstOrNew([
            'active'   => true,
            'folder_id'   => $request->folder_id,
            'title'    => $request->title,
            'sent_date'    => $request->sent_date,
            'last_sent_date'    => $request->last_sent_date,
        ]);

        if ($autoReplyKeyword->exists) {

            return response()->json([
                'msg_return' => 'ERR_DUPPLICATE',
                'code_return' => 1,
                'items'       => ['title']
            ]);

        } else {
            // autoReplyKeyword created from 'new'; does not exist in database.
            $autoReplyKeyword->save();
            if ( isset($request->keywords) ) {
                foreach ($request->keywords as $keyword) {
                    foreach ($keyword as $value) {
                        $keyword = new Keyword([
                            'keyword'                       => $value,
                            'dim_auto_reply_keywords_id'    => $autoReplyKeyword->id,
                            'active'                        => true
                        ]);
                        if (!is_null($keyword)) {
                            $keyword->save();
                        } 
                        $keyword = null;
                    }
                }
            }
            if (isset($request->items) && sizeof($request->items) > 0) {
                foreach ($request->items as $item) {
                    switch ($item['type']) {
                        case 'text':
                            foreach ($item['value'] as $value) {
                                $autoReplyKeywordMessage = new AutoReplyKeywordMessage([
                                    'message' => $value['playload'],
                                    'display' => $value['display'],
                                ]);
                            }
                            $autoReplyKeywordMessage->save();
                            $autoReplyKeywordItem = new AutoReplyKeywordItem([
                                'dim_auto_reply_keyword_id' => $autoReplyKeyword->id,
                                'message_type_id'           => $item['type'],
                                'seq_no'                    => $item['seq_no'],
                                'auto_reply_message_id'     => $autoReplyKeywordMessage->id,
                                'auto_reply_sticker_id'     => null,
                                'auto_reply_richmessage_id' => null,
                                'auto_reply_photo_id'       => null,
                                'auto_reply_video_id'       => null,
                            ]);
                            $autoReplyKeywordItem->save();
                           break;
                        case 'sticker':
                            foreach ($item['value'] as $value) {
                                $autoReplyKeywordSticker = new AutoReplyKeywordSticker([
                                    'packageId' => $value['package_id'],
                                    'stickerId' => $value['stricker_id'],
                                    'display' => $value['display'],
                                ]);
                            }
                            $autoReplyKeywordSticker->save();
                            $autoReplyKeywordItem = new AutoReplyKeywordItem([
                                'dim_auto_reply_keyword_id' => $autoReplyKeyword->id,
                                'message_type_id'           => $item['type'],
                                'seq_no'                    => $item['seq_no'],
                                'auto_reply_message_id'     => null,
                                'auto_reply_sticker_id'     => $autoReplyKeywordSticker->id,
                                'auto_reply_richmessage_id' => null,
                                'auto_reply_photo_id'       => null,
                                'auto_reply_video_id'       => null,
                            ]);    
                            $autoReplyKeywordItem->save();
                           break;
                        case 'imagemap':
                            $autoReplyKeywordItem = new AutoReplyKeywordItem([
                                'dim_auto_reply_keyword_id' => $autoReplyKeyword->id,
                                'message_type_id'           => $item['type'],
                                'seq_no'                    => $item['seq_no'],
                                'auto_reply_message_id'     => null,
                                'auto_reply_sticker_id'     => null,
                                'auto_reply_richmessage_id' => $item['value'][0]['auto_reply_richmessage_id'],
                                'auto_reply_photo_id'       => null,
                                'auto_reply_video_id'       => null,
                            ]);    
                            $autoReplyKeywordItem->save();
                           break;
                        case 'photo':
                            $autoReplyKeywordItem = new AutoReplyKeywordItem([
                                'dim_auto_reply_keyword_id' => $autoReplyKeyword->id,
                                'message_type_id'           => $item['type'],
                                'seq_no'                    => $item['seq_no'],
                                'auto_reply_message_id'     => null,
                                'auto_reply_sticker_id'     => null,
                                'auto_reply_richmessage_id' => null,
                                'auto_reply_photo_id'       => $item['value'][0]['auto_reply_photo_id'],
                                'auto_reply_video_id'       => null,
                            ]);    
                            $autoReplyKeywordItem->save();
                           break;
                        case 'video':
                            $autoReplyKeywordItem = new AutoReplyKeywordItem([
                                'dim_auto_reply_keyword_id' => $autoReplyKeyword->id,
                                'message_type_id'           => $item['type'],
                                'seq_no'                    => $item['seq_no'],
                                'auto_reply_message_id'     => null,
                                'auto_reply_sticker_id'     => null,
                                'auto_reply_richmessage_id' => null,
                                'auto_reply_photo_id'       => null,
                                'auto_reply_video_id'       => $item['value'][0]['auto_reply_video_id'],
                            ]);    
                            $autoReplyKeywordItem->save();
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
        $autoReplyKeyword  = AutoReplyKeyword::find($id);
        if ($autoReplyKeyword) {
            
            $items  = $autoReplyKeyword->autoReplyKeyWordItems;
            $arr = array();
            foreach ($items as $item) {
               $arr[] = array(
                    "id" => $item->id ,
                    "seq_no"    => $item->seq_no,
                    "type"      => $item->messageType->type,
                    "value"     => [
                        $item->show_message
                    ]
                );
            }
            $arrKeywords = array();
            if($autoReplyKeyword->keywords) {
                $keywords =  $autoReplyKeyword->keywords;
                $itemKeywords = [];
                foreach ($keywords as $value) {
                   $itemKeywords[] = $value->keyword;
                }
                $arrKeywords = [
                    "value" => $itemKeywords
                ];
            }

            $data  = array(
                "id"                =>  $autoReplyKeyword->id,
                "folder_id"         =>  $autoReplyKeyword->folder_id,
                "title"             =>  $autoReplyKeyword->title,
                'sent_date'         =>  $autoReplyKeyword->sent_date,
                'last_sent_date'    =>  $autoReplyKeyword->last_sent_date,
                "active"            =>  $autoReplyKeyword->active,
                'keywords'          =>  $arrKeywords,
                "items"             =>  $arr
            );
    
            //return collect($data)->toJson();
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
        $autoReplyKeyword  = AutoReplyKeyword::find($id);
        if ($autoReplyKeyword) {
            $arrErrsField = array();
            $colectErrsField= collect();

            if (!isset($request->title) || (isset($request->title) && trim($request->title) == "") ) {
                $arrErrsField[] = 'title';
            }

            if (sizeof($arrErrsField) > 0) {
                $colectErrsField = $colectErrsField->push([
                    'VALID_REQUIRE' => $arrErrsField
                ]);

                return response()->json([
                    'msg_return'    => 'VALID_REQUIRE',
                    'code_return' => 1,
                    'items'       => $arrErrsField
                ]);
            }

            $dup = AutoReplyKeyword::checkDuplicate($request->title, $id);
            if ($dup) {
               
                return response()->json([
                    'msg_return' => 'ERR_DUPPLICATE',
                    'code_return' => 1,
                    'items'       => ['title']
                ]);
            }

            $autoReplyKeyword = $autoReplyKeyword->update([
                'folder_id'             => $request->folder_id,
                'active'                => $request->active,
                'title'                 => $request->title,
                'sent_date'             => $request->sent_date,
                'last_sent_date'        => $request->last_sent_date,
            ]);
            $autoReplyKeyword  = AutoReplyKeyword::find($id); //Select Again After Code Update

            if (isset($request->items) && sizeof($request->items) > 0) {
                $autoReplyKeywordItems = $autoReplyKeyword->autoReplyKeyWordItems;

                if(sizeof($autoReplyKeywordItems) > 0) {
                    foreach ($autoReplyKeywordItems as  $autoReplyKeywordItem) {
                       switch ( $autoReplyKeywordItem->messageType->type) {
                            case 'text':
                                $autoReplyKeywordMessage = $autoReplyKeywordItem->message()->delete();
                                break;
                            case 'sticker':
                                $autoReplyKeywordSticker = $autoReplyKeywordItem->sticker()->delete();
                                break;
                        }

                    }
                    $autoReplyKeyword->autoReplyKeyWordItems()->delete();

                }
                //Delete Old KeyWord
                
                if($autoReplyKeyword->keywords) {
                    $keywords =  $autoReplyKeyword->keywords()->delete();
                }
               
                // keyword
                if ( isset($request->keywords) ) {
                    foreach ($request->keywords as $keyword) {
                        foreach ($keyword as $value) {
                            $keyword = new Keyword([
                                'keyword'                       => $value,
                                'dim_auto_reply_keywords_id'    => $id,
                                'active'                        => true
                            ]);
                            if (!is_null($keyword)) {
                                $keyword->save();
                            } 
                            $keyword = null;
                        }
                    }
                }

                foreach ($request->items as $item) {

                    switch ($item['type']) {
                        case 'text':
                            foreach ($item['value'] as $value) {
                                $autoReplyKeywordMessage = new AutoReplyKeywordMessage([
                                    'message' => $value['playload'],
                                    'display' => $value['display'],

                                ]);
                            }
                            $autoReplyKeywordMessage->save();
                            $autoReplyKeywordItem = new AutoReplyKeywordItem([
                                'dim_auto_reply_keyword_id' => $id,
                                'message_type_id'           => $item['type'],
                                'seq_no'                    => $item['seq_no'],
                                'auto_reply_message_id'     => $autoReplyKeywordMessage->id,
                                'auto_reply_sticker_id'     => null,
                                'auto_reply_richmessage_id'     => null,
                                'auto_reply_photo_id'       => null,
                                'auto_reply_video_id'       => null,
                            ]);
                            $autoReplyKeywordItem->save();
                           break;
                        case 'sticker':
                            foreach ($item['value'] as $value) {
                                $autoReplyKeywordSticker = new AutoReplyKeywordSticker([
                                    'packageId' => $value['package_id'],
                                    'stickerId' => $value['stricker_id'],
                                    'display' => $value['display'],
                                ]);
                            }
                            $autoReplyKeywordSticker->save();
                            $autoReplyKeywordItem = new AutoReplyKeywordItem([
                                'dim_auto_reply_keyword_id' => $id,
                                'message_type_id'           => $item['type'],
                                'seq_no'                    => $item['seq_no'],
                                'auto_reply_message_id'     => null,
                                'auto_reply_sticker_id'     => $autoReplyKeywordSticker->id,
                                'auto_reply_richmessage_id'     => null,
                                'auto_reply_photo_id'       => null,
                                'auto_reply_video_id'       => null,
                            ]);    
                            $autoReplyKeywordItem->save();

                           break;
                        case 'imagemap':
                            $autoReplyKeywordItem = new AutoReplyKeywordItem([
                                'dim_auto_reply_keyword_id' => $autoReplyKeyword->id,
                                'message_type_id'           => $item['type'],
                                'seq_no'                    => $item['seq_no'],
                                'auto_reply_message_id'     => null,
                                'auto_reply_sticker_id'     => null,
                                'auto_reply_richmessage_id' => $item['value'][0]['auto_reply_richmessage_id'],
                                'auto_reply_photo_id'       => null,
                                'auto_reply_video_id'       => null,
                            ]);    
                            $autoReplyKeywordItem->save();
                           break;
                        case 'photo':
                            $autoReplyKeywordItem = new AutoReplyKeywordItem([
                                'dim_auto_reply_keyword_id' => $autoReplyKeyword->id,
                                'message_type_id'           => $item['type'],
                                'seq_no'                    => $item['seq_no'],
                                'auto_reply_message_id'     => null,
                                'auto_reply_sticker_id'     => null,
                                'auto_reply_richmessage_id' => null,
                                'auto_reply_photo_id'       => $item['value'][0]['auto_reply_photo_id'],
                                'auto_reply_video_id'       => null,
                            ]);    
                            $autoReplyKeywordItem->save();
                           break;
                        case 'video':
                            $autoReplyKeywordItem = new AutoReplyKeywordItem([
                                'dim_auto_reply_keyword_id' => $autoReplyKeyword->id,
                                'message_type_id'           => $item['type'],
                                'seq_no'                    => $item['seq_no'],
                                'auto_reply_message_id'     => null,
                                'auto_reply_sticker_id'     => null,
                                'auto_reply_richmessage_id' => null,
                                'auto_reply_photo_id'       => null,
                                'auto_reply_video_id'       => $item['value'][0]['auto_reply_video_id'],
                            ]);    
                            $autoReplyKeywordItem->save();
                           break;

                       default:
                           # code...
                       break;
                    }
                }

                return response()->json([
                    'msg_return'    => 'UPDATE_SUCCESS',
                    'code_return'   => 1,
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
        $autoReplyKeyword  = AutoReplyKeyword::find($id);
        if ($autoReplyKeyword) {
            if ($autoReplyKeyword->active) {
                return response()->json([
                    'msg_return' => 'ลบไม่สำเร็จ ข้อมูลถูกใช้งานอยู่',
                    'code_return' => 1,
                ]);
            } else {
                $keyword = Keyword::where('dim_auto_reply_keywords_id',$autoReplyKeyword->id)->forceDelete();
               $autoReplyKeyword->delete();

                return response()->json([
                    'msg_return' => 'ลบสำเร็จ',
                    'code_return' => 1,
                ]); 
            }
            
        }
    }

    public function postActive(Request $request, $id)
    {
        $autoReplyKeyword  = AutoReplyKeyword::find($id);
       
        if ($autoReplyKeyword) {
            $autoReplyKeyword->update([
                'active'  => $request->active,
            ]);

            return response()->json([
                'msg_return' => 'บันทึกสำเร็จ',
                'code_return' => 1,
            ]);
        }
    }
}
