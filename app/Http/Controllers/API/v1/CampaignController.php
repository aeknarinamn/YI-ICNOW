<?php

namespace YellowProject\Http\Controllers\API\v1;

use Illuminate\Http\Request;
use YellowProject\Http\Controllers\Controller;

use YellowProject\Campaign;
use YellowProject\CampaignItem;
use YellowProject\CampaignMessage;
use YellowProject\CampaignSticker;
use YellowProject\Campaign\CampaignSchedule;
use YellowProject\Campaign\ScheduleCampaign;
use YellowProject\Segment\Segment;
use YellowProject\Segment\QuickSegment;
use YellowProject\Campaign\CampaignSetTaskToSentMessage;

class CampaignController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $datas = [];
        $campaigns  = Campaign::orderBy('updated_at','desc')->get();
        foreach ($campaigns as $key => $campaign) {
            $campaignSentMessages = $campaign->campaignSendMessages;
            $sentDate = "";
            $lastSentDate = "";
            $status = "";
            $segmentCount = 0;
            if($campaign->segment_type == 'normal'){
                $segment = $campaign->segment;
                if($segment){
                    $segmentSusbcriberDatas = Segment::getSegmentData($segment->id);
                    $segmentCount = (isset($segmentSusbcriberDatas))? $segmentSusbcriberDatas->count() : 0;
                }
            }else{
                $segment = $campaign->quickSegment;
                if($segment){
                    $segmentSusbcriberDatas = QuickSegment::getDatas($segment->id);
                    $segmentCount = (isset($segmentSusbcriberDatas))? $segmentSusbcriberDatas->count() : 0;
                }
            }
            if($campaign->campaignSendMessages->count() > 0){
                $sentDate = $campaign->campaignSendMessages->first()->created_at->format('Y-m-d H:i:s');
                $lastSentDate = $campaign->campaignSendMessages->last()->created_at->format('Y-m-d H:i:s');
                $status = "Sent";
            }else{
                if($campaign->is_start_schedule){
                    $campaignSchedule = $campaign->schedule;
                    if($campaignSchedule->schedule_type == 'One-time'){
                        $status = 'Scheduled '.$campaignSchedule->schedule_start_date;
                    }else{
                        $status = 'Recurring';
                    }
                }
            }
            $datas[$key]['id'] = $campaign->id;
            $datas[$key]['campaign_name'] = $campaign->name;
            $datas[$key]['segment_name'] = ($segment)? $segment->name : "-";
            $datas[$key]['segment_count'] = $segmentCount;
            $datas[$key]['sent_date'] = ($sentDate != '')? $sentDate : '-';
            $datas[$key]['last_sent_date'] = ($lastSentDate != '')? $lastSentDate : '-';
            $datas[$key]['status'] = ($status != '')? $status : 'Draft';
           // $campaignSegment = $campaign->segment;
        }
       
        return response()->json([
            'datas'          => $datas,
            'countAutoReply' => $campaigns->count(),
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
        $campaignCheckName = Campaign::where('name',$request->name)->first();
        if($campaignCheckName){
          return response()->json([
              'msg_return' => 'ข้อมูลชื่อซ้ำ',
              'code_return' => 2,
          ]);
        }
        $arrErrsField = array();
        $colectErrsField= collect();
        if (!isset($request->name) || (isset($request->name) && trim($request->name) == "") ) {
            $arrErrsField[] = 'name';
        }

        // if (!isset($request->alt_text) || (isset($request->alt_text))) {
        //     $arrErrsField[] = 'alt_text';
        // }

        // if (!isset($request->segment_id) || (isset($request->segment_id))) {
        //     $arrErrsField[] = 'segment';
        // }


        if (sizeof($arrErrsField) > 0) {
           
            return response()->json([
                'msg_return' => 'VALID_REQUIRE',
                'code_return' => 1,
                'items'       => $arrErrsField
            ]);
        }

        $dup = Campaign::checkDuplicate($request->name);
        if ($dup) {
           
            return response()->json([
                'msg_return' => 'ERR_DUPPLICATE',
                'code_return' => 1,
                'items'       => ['name']
            ]);
        }

        $scheduleItem = $request->schedule;

        $campaignSchedule = CampaignSchedule::create([
            'schedule_type' => $scheduleItem['schedule_type'],
            'schedule_start_date' => $scheduleItem['schedule_start_date'],
            'schedule_end_date' => $scheduleItem['schedule_end_date'],
            'schedule_recurrence_type' => $scheduleItem['schedule_recurrence_type'],
            'schedule_recurrence_sun' => $scheduleItem['schedule_recurrence_sun'],
            'schedule_recurrence_mon' => $scheduleItem['schedule_recurrence_mon'],
            'schedule_recurrence_tue' => $scheduleItem['schedule_recurrence_tue'],
            'schedule_recurrence_wed' => $scheduleItem['schedule_recurrence_wed'],
            'schedule_recurrence_thu' => $scheduleItem['schedule_recurrence_thu'],
            'schedule_recurrence_fri' => $scheduleItem['schedule_recurrence_fri'],
            'schedule_recurrence_sat' => $scheduleItem['schedule_recurrence_sat'],
            'schedule_recurrence_running' => $scheduleItem['schedule_recurrence_running'],
            'schedule_schedule_send_time' => $scheduleItem['schedule_schedule_send_time'],
        ]);

        $campaign = Campaign::firstOrNew([
            'name'                  => $request->name,
            'alt_text'              => $request->alt_text,
            'send_time'             => $request->send_time,
            'datetime'              => $request->datetime,
            'start_date'            => $request->start_date,
            'end_date'              => $request->end_date,
            'is_active'             => $request->is_active,
            'folder_id'             => $request->folder_id,
            'segment_id'            => $request->segment_id,
            'send_status'           => $request->send_status,
            'schedule_id'           => $campaignSchedule->id,
            'segment_type'          => $request->segment_type,
        ]);

        if ($campaign->exists) {

            return response()->json([
                'msg_return' => 'ERR_DUPPLICATE',
                'code_return' => 1,
                'items'       => ['name']
            ]);

        } else {
            // campaign created from 'new'; does not exist in database.
            $campaign->save();
            
            if (isset($request->items) && sizeof($request->items) > 0) {
                foreach ($request->items as $item) {
                    switch ($item['type']) {
                        case 'text':
                            foreach ($item['value'] as $value) {
                                $campaignMessage = new CampaignMessage([
                                    'message' => $value['playload'],
                                    'display' => $value['display'],
                                ]);
                            }
                            $campaignMessage->save();
                            $campaignItem = new CampaignItem([
                                'campaign_id'               => $campaign->id,
                                'message_type_id'           => $item['type'],
                                'seq_no'                    => $item['seq_no'],
                                'campaign_message_id'     => $campaignMessage->id,
                                'campaign_sticker_id'     => null,
                            ]);
                            $campaignItem->save();
                           break;
                        case 'sticker':
                            foreach ($item['value'] as $value) {
                                $campaignSticker = new CampaignSticker([
                                    'packageId' => $value['package_id'],
                                    'stickerId' => $value['stricker_id'],
                                    'display'   => $value['display'],
                                ]);
                            }
                            $campaignSticker->save();
                            $campaignItem = new CampaignItem([
                                'campaign_id'               => $campaign->id,
                                'message_type_id'           => $item['type'],
                                'seq_no'                    => $item['seq_no'],
                                'campaign_message_id'     => null,
                                'campaign_sticker_id'     => $campaignSticker->id,
                            ]);    
                            $campaignItem->save();
                           break;
                        case 'imagemap':
                            $campaignItem = new CampaignItem([
                                'campaign_id' => $campaign->id,
                                'message_type_id'  => $item['type'],
                                'seq_no'  => $item['seq_no'],
                                'campaign_message_id'  => null,
                                'campaign_sticker_id'  => null,
                                'campaign_richmessage_id' => $item['value'][0]['auto_reply_richmessage_id'],
                            ]);    
                            $campaignItem->save();
                           break;
                       default:
                           # code...
                           break;
                    }
                }
            }

            return response()->json([
                'msg_return' => 'INSERT_SUCCESS',
                'campaign_id' => $campaign->id,
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
        $campaign  = Campaign::find($id);
        if ($campaign) {
            
            $items  = $campaign->campaignItems;
            $campaignSchedule = $campaign->schedule;

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

            $arrCampaignSchedule = array(
                'schedule_type' => $campaignSchedule->schedule_type,
                'schedule_start_date' => $campaignSchedule->schedule_start_date,
                'schedule_end_date' => $campaignSchedule->schedule_end_date,
                'schedule_recurrence_type' => $campaignSchedule->schedule_recurrence_type,
                'schedule_recurrence_sun' => $campaignSchedule->schedule_recurrence_sun,
                'schedule_recurrence_mon' => $campaignSchedule->schedule_recurrence_mon,
                'schedule_recurrence_tue' => $campaignSchedule->schedule_recurrence_tue,
                'schedule_recurrence_wed' => $campaignSchedule->schedule_recurrence_wed,
                'schedule_recurrence_thu' => $campaignSchedule->schedule_recurrence_thu,
                'schedule_recurrence_fri' => $campaignSchedule->schedule_recurrence_fri,
                'schedule_recurrence_sat' => $campaignSchedule->schedule_recurrence_sat,
                'schedule_recurrence_running' => $campaignSchedule->schedule_recurrence_running,
                'schedule_schedule_send_time' => $campaignSchedule->schedule_schedule_send_time,
            );
            

            $data  = array(
                "id"                    =>  $campaign->id,
                "name"                  =>  $campaign->name,
                "alt_text"              =>  $campaign->alt_text,
                "segment_id"            =>  $campaign->segment_id,
                "folder_id"             =>  $campaign->folder_id,
                "send_status"           =>  $campaign->send_status,
                "start_date"            =>  $campaign->start_date,
                "end_date"              =>  $campaign->end_date,
                "send_time"             =>  $campaign->send_time,
                "is_active"             =>  $campaign->is_active,
                "items"                 =>  $arr,
                "schedule"              =>  $arrCampaignSchedule,
                "segment_type"          =>  $campaign->segment_type,
            );
    
            // return collect($data)->toJson();
            return response()->json([
                'datas' => collect($data),
            ]);
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
        // dd($request->all());
        $campaign  = Campaign::find($id);
        $campaignCheckName = Campaign::where('name',$request->name)->first();
        if($campaignCheckName && $request->name != $campaign->name){
          return response()->json([
              'msg_return' => 'ข้อมูลชื่อซ้ำ',
              'code_return' => 2,
          ]);
        }
        if ($campaign) {
            $arrErrsField = array();
            $colectErrsField= collect();

            if (!isset($request->name) || (isset($request->name) && trim($request->name) == "")) {
                $arrErrsField[] = 'name';
            }

            if (isset($request->send_status) && $request->send_status == "send_it") {
                $rst  = Campaign::sentCampaign($campaign);

                return response()->json([
                    'msg_return'    => 'SENT_SUCCESS',
                    'code_return'   => 1,
                ]);
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

            // $dup = Campaign::checkDuplicate($request->name, $id);
            // if ($dup) {
               
            //     return response()->json([
            //         'msg_return' => 'ERR_DUPPLICATE',
            //         'code_return' => 1,
            //         'items'       => ['name']
            //     ]);
            // }

            $scheduleItem = $request->schedule;

            if($campaign->schedule_id == ""){
                $campaignSchedule = CampaignSchedule::create($scheduleItem);
            }else{
                $campaignSchedule = CampaignSchedule::find($campaign->schedule_id);
                $campaignSchedule->update($scheduleItem);
            }

            $campaign = $campaign->update([
                'name'                  => $request->name,
                'alt_text'              => $request->alt_text,
                'send_time'             => $request->send_time,
                'datetime'              => $request->datetime,
                'start_date'            => $request->start_date,
                'end_date'              => $request->end_date,
                'is_active'             => $request->is_active,
                'folder_id'             => $request->folder_id,
                'segment_id'            => $request->segment_id,
                'send_status'           => $request->send_status,
                'schedule_id'           => $campaignSchedule->id,
                'segment_type'          => $request->segment_type,
            ]);
            $campaign  = Campaign::find($id); //Select Again After Code Update

            if (isset($request->items) && sizeof($request->items) > 0) {
                $campaignItems = $campaign->campaignItems;

                if(sizeof($campaignItems) > 0) {
                    foreach ($campaignItems as  $campaignItem) {
                       switch ( $campaignItem->messageType->type) {
                            case 'text':
                                $campaignItem->message()->delete();
                                break;
                            case 'sticker':
                                $campaignItem->sticker()->delete();
                                break;
                        }

                    }
                    $campaign->campaignItems()->delete();
                }
                
                foreach ($request->items as $item) {

                    switch ($item['type']) {
                        case 'text':
                            foreach ($item['value'] as $value) {
                                $campaignMessage = new CampaignMessage([
                                    'message' => $value['playload'],
                                    'display' => $value['display'],

                                ]);
                            }
                            $campaignMessage->save();
                            $campaignItem = new CampaignItem([
                                'campaign_id' => $id,
                                'message_type_id'           => $item['type'],
                                'seq_no'                    => $item['seq_no'],
                                'campaign_message_id'     => $campaignMessage->id,
                                'campaign_sticker_id'     => null,
                            ]);
                            $campaignItem->save();
                           break;
                        case 'sticker':
                            foreach ($item['value'] as $value) {
                                $campaignSticker = new CampaignSticker([
                                    'packageId' => $value['package_id'],
                                    'stickerId' => $value['stricker_id'],
                                    'display' => $value['display'],
                                ]);
                            }
                            $campaignSticker->save();
                            $campaignItem = new CampaignItem([
                                'campaign_id' => $id,
                                'message_type_id'           => $item['type'],
                                'seq_no'                    => $item['seq_no'],
                                'campaign_message_id'     => null,
                                'campaign_sticker_id'     => $campaignSticker->id,
                            ]);    
                            $campaignItem->save();

                           break;
                        case 'imagemap':
                            $campaignItem = new CampaignItem([
                                'campaign_id' => $id,
                                'message_type_id'  => $item['type'],
                                'seq_no'  => $item['seq_no'],
                                'campaign_message_id'  => null,
                                'campaign_sticker_id'  => null,
                                'campaign_richmessage_id' => $item['value'][0]['auto_reply_richmessage_id'],
                            ]);    
                            $campaignItem->save();
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
        $campaign  = Campaign::find($id);
        if ($campaign) {
            if ($campaign->active) {
                return response()->json([
                    'msg_return' => 'ลบไม่สำเร็จ ข้อมูลถูกใช้งานอยู่',
                    'code_return' => 1,
                ]);
            } else {
               $campaign->delete();

                return response()->json([
                    'msg_return' => 'ลบสำเร็จ',
                    'code_return' => 1,
                ]); 
            }
        }
    }

    public function postActive(Request $request, $id)
    {
        $campaign  = Campaign::find($id);
       
        if ($campaign) {
            $campaign->update([
                'active'  => $request->active,
            ]);

            return response()->json([
                'msg_return' => 'บันทึกสำเร็จ',
                'code_return' => 1,
            ]);
        }
    }

    public function sendCampaign(Request $request,$id)
    {
        // $campaign = Campaign::find($id);
        // if($campaign->segment_type == 'normal'){
        //     $segment = $campaign->segment;
        //     $datas = Segment::getSegmentData($segment->id);
        // }else{
        //     $segment = $campaign->quickSegment;
        //     $datas = QuickSegment::getDatas($segment->id);
        // }
        
        // $mids = Segment::segmentCampaign($datas);
        // Campaign::sentCampaign($campaign,$mids);

        // dd($mids);

        CampaignSetTaskToSentMessage::create([
            'campaign_id' => $id,
            'type' => 'normal',
            'check_status' => 1
        ]);

        return response()->json([
            'msg_return' => 'ส่งข้อมูลสำเร็จ',
            'code_return' => 1,
        ]);
    }

    public function scheduleActive(Request $request, $id)
    {
        $campaign  = Campaign::find($id);
       
        if ($campaign) {
            // $campaign->update([
            //     'is_start_schedule'  => 1,
            // ]);

            // $scheduleCampaign = ScheduleCampaign::where('campaign_id',$campaign->id)->first();
            // if($scheduleCampaign){
            //     $scheduleCampaign->update([
            //         'status' => 'process',
            //     ]);
            // }else{
            //     ScheduleCampaign::create([
            //         'campaign_id' => $campaign->id,
            //         'status' => 'process',
            //     ]);
            // }

            // Campaign::setScheduleData($campaign);

            CampaignSetTaskToSentMessage::create([
                'campaign_id' => $id,
                'type' => 'schedule',
                'check_status' => 1
            ]);

            return response()->json([
                'msg_return' => 'บันทึกสำเร็จ',
                'code_return' => 1,
            ]);
        }
    }

    public function scheduleUnActive(Request $request, $id)
    {
        $campaign  = Campaign::find($id);
       
        if ($campaign) {
            $campaign->update([
                'is_start_schedule'  => 0,
            ]);

            return response()->json([
                'msg_return' => 'บันทึกสำเร็จ',
                'code_return' => 1,
            ]);
        }
    }
}
