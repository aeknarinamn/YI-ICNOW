<?php

namespace YellowProject\Http\Controllers\API\v1\TemplateMessage;

use Illuminate\Http\Request;
use YellowProject\Http\Controllers\Controller;
use YellowProject\TemplateMessage\TemplateMessage;
use YellowProject\TemplateMessage\TemplateMessageColumn;
use YellowProject\TemplateMessage\TemplateMessageAction;
use YellowProject\AutoReplyDefaultItem;
use YellowProject\CampaignItem;

class TemplateMessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $isUse = 0;
        $datas = [];
        $templateMessages = TemplateMessage::orderByDesc('updated_at')->get();
        foreach ($templateMessages as $key => $templateMessage) {
            $autoReplyDefaultItem = AutoReplyDefaultItem::where('template_message_id',$templateMessage->id)->first();
            if($autoReplyDefaultItem){
              $isUse = 1;
            }
            $campaignItem = CampaignItem::where('template_message_id',$templateMessage->id)->first();
            if($campaignItem){
              $isUse = 1;
            }
            $datas[$key]['id'] = $templateMessage->id;
            $datas[$key]['name'] = $templateMessage->name;
            $datas[$key]['alt_text'] = $templateMessage->alt_text;
            $datas[$key]['type'] = $templateMessage->type;
            $datas[$key]['folder_name'] = ($templateMessage->folder)? $templateMessage->folder->name : null;
            $datas[$key]['is_use'] = $isUse;
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
        $templateMessageCheckName = TemplateMessage::where('name',$request->name)->first();
        if($templateMessageCheckName){
          return response()->json([
              'msg_return' => 'Duplicate Name',
              'code_return' => 2,
          ]);
        }

        $templateMessage = TemplateMessage::create($request->all());
        if(isset($request->columns)){
            $columns = $request->columns;
            foreach ($columns as $key => $column) {
                $column['template_message_id'] = $templateMessage->id;
                $templateMessageColumn = TemplateMessageColumn::create($column);
                if(isset($column['actions'])){
                    $actions = $column['actions'];
                    foreach ($actions as $key => $action) {
                        $action['template_message_column_id'] = $templateMessageColumn->id;
                        TemplateMessageAction::create($action);
                    }
                }
            }
        }
        return response()->json([
            'msg_return' => 'บันทึกสำเร็จ',
            'code_return' => 1,
            'id' => $templateMessage->id,
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
        $datas = [];
        $templateMessage = TemplateMessage::find($id);
        $datas = $templateMessage->toArray();
        $templateMessageColumns = $templateMessage->templateMessageColumns;
        if($templateMessageColumns){
            foreach ($templateMessageColumns as $key => $templateMessageColumn) {
                $datas['columns'][$key] = $templateMessageColumn->toArray();
                $templateMessageActions = $templateMessageColumn->templateMessageActions;
                if($templateMessageActions){
                    foreach ($templateMessageActions as $index => $templateMessageAction) {
                        $datas['columns'][$key]['actions'][$index] = $templateMessageAction->toArray();
                    }
                }
            }
        }

        return response()->json([
            'datas' => $datas,
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
        $templateMessage = TemplateMessage::find($id);
        $templateMessageCheckName = TemplateMessage::where('name',$request->name)->first();
        if($templateMessageCheckName && $request->name != $templateMessage->name){
          return response()->json([
              'msg_return' => 'Duplicate Name',
              'code_return' => 2,
          ]);
        }
        $templateMessage->update($request->all());
        $templateMessageColumns = $templateMessage->templateMessageColumns;
        if($templateMessageColumns){
            foreach ($templateMessageColumns as $key => $templateMessageColumn) {
                $templateMessageActions = $templateMessageColumn->templateMessageActions;
                if($templateMessageActions){
                    foreach ($templateMessageActions as $key => $templateMessageAction) {
                        $templateMessageAction->delete();
                    }
                }
                $templateMessageColumn->delete();
            }
        }

        if(isset($request->columns)){
            $columns = $request->columns;
            foreach ($columns as $key => $column) {
                $column['template_message_id'] = $templateMessage->id;
                $templateMessageColumn = TemplateMessageColumn::create($column);
                if(isset($column['actions'])){
                    $actions = $column['actions'];
                    foreach ($actions as $key => $action) {
                        $action['template_message_column_id'] = $templateMessageColumn->id;
                        TemplateMessageAction::create($action);
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
        $templateMessage = TemplateMessage::find($id);
        $templateMessageColumns = $templateMessage->templateMessageColumns;
        if($templateMessageColumns){
            foreach ($templateMessageColumns as $key => $templateMessageColumn) {
                $templateMessageActions = $templateMessageColumn->templateMessageActions;
                if($templateMessageActions){
                    foreach ($templateMessageActions as $key => $templateMessageAction) {
                        $templateMessageAction->delete();
                    }
                }
                $templateMessageColumn->delete();
            }
        }
        $templateMessage->delete();

        return response()->json([
            'msg_return' => 'ลบสำเร็จ',
            'code_return' => 1,
        ]);
    }
}
