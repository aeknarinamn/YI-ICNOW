<?php

namespace YellowProject\Http\Controllers\API\v1;

use Illuminate\Http\Request;
use YellowProject\Http\Controllers\Controller;
use YellowProject\LineUserProfile;
use YellowProject\HistoryAddBlock;
use YellowProject\Campaign;
use YellowProject\TrackingRecieveBc;
use YellowProject\RecieveMessage;
use YellowProject\PushOrReply;
use YellowProject\Campaign\CampaignSendMessage;
use Carbon\Carbon;

class Dashboard extends Controller
{
    public function report1()
    {
    	$countAllFriend = LineUserProfile::where('is_follow',1)->count();
    	$countFriendAdd = HistoryAddBlock::where('action','follow')->count();
    	$countFriendBlock = HistoryAddBlock::where('action','unfollow')->count();
    	$countMessageSent = 0;

    	$datas['count_all_friend'] = $countAllFriend;
    	$datas['count_add_friend'] = $countFriendAdd;
    	$datas['count_block_friend'] = $countFriendBlock;
    	$datas['count_message_sent'] = $countMessageSent;


    	return response()->json([
            'report_data1' => $datas,
        ]);
    }

    public function reportCalendarCampaign()
    {
        $datas = [];
        $campaigns = Campaign::all();
        foreach ($campaigns as $key => $campaign) {
            $datas[$key]['campaign_name'] = $campaign->name;
            $datas[$key]['created_at'] = $campaign->created_at->format('Y-m-d H:i:s');
        }


        return response()->json([
            'datas' => $datas,
        ]);
    }

    public function reportTrackingBC(Request $request)
    {
        $datas = [];
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        // $isUnique = 1;
        $isUnique = $request->is_unique;
        $trackingSources = $request->tracking_source;
        $trackingRefs = $request->tracking_ref;
        $trackingCampaigns = $request->tracking_campagin;
        if($startDate && $endDate){
            $trackingRecieveBcs = TrackingRecieveBc::whereBetween('created_at', array($startDate, $endDate));
            if(count($trackingSources) > 0){
                $trackingRecieveBcs = $trackingRecieveBcs->whereIn('tracking_source',$trackingSources);
            }
            if(count($trackingRefs) > 0){
                $trackingRecieveBcs = $trackingRecieveBcs->whereIn('tracking_ref',$trackingRefs);
            }
            if(count($trackingCampaigns) > 0){
                $trackingRecieveBcs = $trackingRecieveBcs->whereIn('tracking_campaign',$trackingCampaigns);
            }
            $trackingRecieveBcs = $trackingRecieveBcs->get();
        }else{
            if(count($trackingSources) > 0 || count($trackingRefs) > 0 || count($trackingCampaigns) > 0){
                $trackingRecieveBcs = TrackingRecieveBc::orderBy('id');
                if(count($trackingSources) > 0){
                    $trackingRecieveBcs = $trackingRecieveBcs->whereIn('tracking_source',$trackingSources);
                }
                if(count($trackingRefs) > 0){
                    $trackingRecieveBcs = $trackingRecieveBcs->whereIn('tracking_ref',$trackingRefs);
                }
                if(count($trackingCampaigns) > 0){
                    $trackingRecieveBcs = $trackingRecieveBcs->whereIn('tracking_campaign',$trackingCampaigns);
                }
                $trackingRecieveBcs = $trackingRecieveBcs->get();
            }else{
                $trackingRecieveBcs = TrackingRecieveBc::all();
            }
        }

        if($isUnique == 1){
            $count = 0;
            $trackingRecieveBcGroupByTrackingIds = $trackingRecieveBcs->groupBy('tracking_bc_id');
            foreach ($trackingRecieveBcGroupByTrackingIds as $index => $trackingRecieveBcGroupByTrackingId) {
                $trackingRecieveBcGroupByTrackingId = $trackingRecieveBcGroupByTrackingId->unique('line_user_id');
                $trackingRecieveBcGroupBys = $trackingRecieveBcGroupByTrackingId->groupBy(function($date) {
                    return Carbon::parse($date->created_at)->format('Y-m-d');
                });

                foreach ($trackingRecieveBcGroupBys as $key => $trackingRecieveBcGroupBy) {
                    $groupBySources = $trackingRecieveBcGroupBy->groupBy('tracking_source');
                    $groupByCampaigns = $trackingRecieveBcGroupBy->groupBy('tracking_campaign');
                    $groupByRefs = $trackingRecieveBcGroupBy->groupBy('tracking_ref');
                    $datas[$key][$count]['count_all'] = $trackingRecieveBcGroupBy->count();
                    $datas[$key][$count]['count_all_source'] = $groupBySources->count();
                    $datas[$key][$count]['count_all_campaign'] = $groupByCampaigns->count();
                    $datas[$key][$count]['count_all_ref'] = $groupByRefs->count();
                    // foreach ($groupBySources as $keyWord => $groupBySource) {
                    //     $datas[$key]['sources'][$keyWord] = $groupBySource->count();
                    // }
                    // foreach ($groupByCampaigns as $keyWord => $groupByCampaign) {
                    //     $datas[$key]['campaigns'][$keyWord] = $groupByCampaign->count();
                    // }
                    // foreach ($groupByRefs as $keyWord => $groupByRef) {
                    //     $datas[$key]['refs'][$keyWord] = $groupByRef->count();
                    // }
                }
                $count++;
                // dd($trackingRecieveBcGroupBys);
            }
            $dataSets = $datas;
            $datas = [];
            foreach ($dataSets as $date => $dataGroupDates) {
                $sumArray['count_all'] = 0;
                $sumArray['count_all_source'] = 0;
                $sumArray['count_all_campaign'] = 0;
                $sumArray['count_all_ref'] = 0;
                foreach ($dataGroupDates as $k=>$subArray) {
                  foreach ($subArray as $id=>$value) {
                    $sumArray[$id]+=$value;
                  }
                }
                $datas[$date] = $sumArray;
            }
            ksort($datas);
            // dd($trackingRecieveBcGroupByTrackingId);
            // $trackingRecieveBcs = $trackingRecieveBcs->unique(['tracking_bc_id','line_user_id']);
        }else{
            $trackingRecieveBcGroupBys = $trackingRecieveBcs->groupBy(function($date) {
                return Carbon::parse($date->created_at)->format('Y-m-d');
            });

            foreach ($trackingRecieveBcGroupBys as $key => $trackingRecieveBcGroupBy) {
                $groupBySources = $trackingRecieveBcGroupBy->groupBy('tracking_source');
                $groupByCampaigns = $trackingRecieveBcGroupBy->groupBy('tracking_campaign');
                $groupByRefs = $trackingRecieveBcGroupBy->groupBy('tracking_ref');
                $datas[$key]['count_all'] = $trackingRecieveBcGroupBy->count();
                $datas[$key]['count_all_source'] = $groupBySources->count();
                $datas[$key]['count_all_campaign'] = $groupByCampaigns->count();
                $datas[$key]['count_all_ref'] = $groupByRefs->count();
                foreach ($groupBySources as $keyWord => $groupBySource) {
                    $datas[$key]['sources'][$keyWord] = $groupBySource->count();
                }
                foreach ($groupByCampaigns as $keyWord => $groupByCampaign) {
                    $datas[$key]['campaigns'][$keyWord] = $groupByCampaign->count();
                }
                foreach ($groupByRefs as $keyWord => $groupByRef) {
                    $datas[$key]['refs'][$keyWord] = $groupByRef->count();
                }
            }
        }

        // dd($datas);

        // foreach ($trackingRecieveBcs as $key => $trackingRecieveBc) {
        //     $datas[$key]['']
        // }

        return response()->json([
            'datas' => $datas,
        ]);
    }

    public function reportTrackingBCofTheDay(Request $request)
    {
        $datas = [];
        $dataSet = [];
        $dataSetCampaign = [];
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $trackingSources = $request->tracking_source;
        $trackingRefs = $request->tracking_ref;
        $trackingCampaigns = $request->tracking_campagin;
        $dateNow = Carbon::now()->format('Y-m-d');
        // $trackingRecieveBcs = TrackingRecieveBc::whereDate('created_at',$dateNow)->get();
        // $campaignSendMessages = CampaignSendMessage::whereDate('created_at',$dateNow)->get();
        if(count($trackingSources) > 0 || count($trackingRefs) > 0 || count($trackingCampaigns) > 0){
            $trackingRecieveBcs = TrackingRecieveBc::orderBy('id');
            if(count($trackingSources) > 0){
                $trackingRecieveBcs = $trackingRecieveBcs->whereIn('tracking_source',$trackingSources);
            }
            if(count($trackingRefs) > 0){
                $trackingRecieveBcs = $trackingRecieveBcs->whereIn('tracking_ref',$trackingRefs);
            }
            if(count($trackingCampaigns) > 0){
                $trackingRecieveBcs = $trackingRecieveBcs->whereIn('tracking_campaign',$trackingCampaigns);
            }
            $trackingRecieveBcs = $trackingRecieveBcs->get();
        }else{
            $trackingRecieveBcs = TrackingRecieveBc::all();
        }
        $campaignSendMessages = CampaignSendMessage::all();

        $countAllClick = $trackingRecieveBcs->count();
        $countAllUnique = $trackingRecieveBcs->unique('line_user_id')->count();
        $countAllSent = $campaignSendMessages->count();

        $datas['12AM-1.59AM']['total_sent'] = 0;
        $datas['2AM-3.59AM']['total_sent'] = 0;
        $datas['4AM-5.59AM']['total_sent'] = 0;
        $datas['6AM-7.59AM']['total_sent'] = 0;
        $datas['8AM-9.59AM']['total_sent'] = 0;
        $datas['10AM-11.59AM']['total_sent'] = 0;
        $datas['12PM-1.59PM']['total_sent'] = 0;
        $datas['2PM-3.59PM']['total_sent'] = 0;
        $datas['4PM-5.59PM']['total_sent'] = 0;
        $datas['6PM-7.59PM']['total_sent'] = 0;
        $datas['8PM-9.59PM']['total_sent'] = 0;
        $datas['10PM-11.59PM']['total_sent'] = 0;

        $datas['12AM-1.59AM']['total_click'] = 0;
        $datas['2AM-3.59AM']['total_click'] = 0;
        $datas['4AM-5.59AM']['total_click'] = 0;
        $datas['6AM-7.59AM']['total_click'] = 0;
        $datas['8AM-9.59AM']['total_click'] = 0;
        $datas['10AM-11.59AM']['total_click'] = 0;
        $datas['12PM-1.59PM']['total_click'] = 0;
        $datas['2PM-3.59PM']['total_click'] = 0;
        $datas['4PM-5.59PM']['total_click'] = 0;
        $datas['6PM-7.59PM']['total_click'] = 0;
        $datas['8PM-9.59PM']['total_click'] = 0;
        $datas['10PM-11.59PM']['total_click'] = 0;

        // foreach ($trackingRecieveBcs as $key => $trackingRecieveBc) {
        //     $hourlyTrackingBC = $trackingRecieveBc->created_at->format('h.i');
        //     if($hourlyTrackingBC > 0 && $hourlyTrackingBC < 2){
        //         $datas['12AM-1.59AM']['total_click']++;
        //     }else if($hourlyTrackingBC > 2 && $hourlyTrackingBC < 4){
        //         $datas['2AM-3.59AM']['total_click']++;
        //     }else if($hourlyTrackingBC > 4 && $hourlyTrackingBC < 6){
        //         $datas['4AM-5.59AM']['total_click']++;
        //     }else if($hourlyTrackingBC > 6 && $hourlyTrackingBC < 8){
        //         $datas['6AM-7.59AM']['total_click']++;
        //     }else if($hourlyTrackingBC > 8 && $hourlyTrackingBC < 10){
        //         $datas['8AM-9.59AM']['total_click']++;
        //     }else if($hourlyTrackingBC > 10 && $hourlyTrackingBC < 12){
        //         $datas['10AM-11.59AM']['total_click']++;
        //     }else if($hourlyTrackingBC > 12 && $hourlyTrackingBC < 14){
        //         $datas['12PM-1.59PM']['total_click']++;
        //     }else if($hourlyTrackingBC > 14 && $hourlyTrackingBC < 16){
        //         $datas['2PM-3.59PM']['total_click']++;
        //     }else if($hourlyTrackingBC > 16 && $hourlyTrackingBC < 18){
        //         $datas['4PM-5.59PM']['total_click']++;
        //     }else if($hourlyTrackingBC > 18 && $hourlyTrackingBC < 20){
        //         $datas['6PM-7.59PM']['total_click']++;
        //     }else if($hourlyTrackingBC > 20 && $hourlyTrackingBC < 22){
        //         $datas['8PM-9.59PM']['total_click']++;
        //     }else if($hourlyTrackingBC > 22 && $hourlyTrackingBC < 24){
        //         $datas['10PM-11.59PM']['total_click']++;
        //     }
        // }

        foreach ($trackingRecieveBcs as $key => $trackingRecieveBc) {
            $hourlyTrackingBC = $trackingRecieveBc->created_at->format('H.i');
            if($hourlyTrackingBC > 0 && $hourlyTrackingBC < 2){
                $dataSet['12AM-1.59AM'][] = $trackingRecieveBc;
            }else if($hourlyTrackingBC > 2 && $hourlyTrackingBC < 4){
                $dataSet['2AM-3.59AM'][] = $trackingRecieveBc;
            }else if($hourlyTrackingBC > 4 && $hourlyTrackingBC < 6){
                $dataSet['4AM-5.59AM'][] = $trackingRecieveBc;
            }else if($hourlyTrackingBC > 6 && $hourlyTrackingBC < 8){
                $dataSet['6AM-7.59AM'][] = $trackingRecieveBc;
            }else if($hourlyTrackingBC > 8 && $hourlyTrackingBC < 10){
                $dataSet['8AM-9.59AM'][] = $trackingRecieveBc;
            }else if($hourlyTrackingBC > 10 && $hourlyTrackingBC < 12){
                $dataSet['10AM-11.59AM'][] = $trackingRecieveBc;
            }else if($hourlyTrackingBC > 12 && $hourlyTrackingBC < 14){
                $dataSet['12PM-1.59PM'][] = $trackingRecieveBc;
            }else if($hourlyTrackingBC > 14 && $hourlyTrackingBC < 16){
                $dataSet['2PM-3.59PM'][] = $trackingRecieveBc;
            }else if($hourlyTrackingBC > 16 && $hourlyTrackingBC < 18){
                $dataSet['4PM-5.59PM'][] = $trackingRecieveBc;
            }else if($hourlyTrackingBC > 18 && $hourlyTrackingBC < 20){
                $dataSet['6PM-7.59PM'][] = $trackingRecieveBc;
            }else if($hourlyTrackingBC > 20 && $hourlyTrackingBC < 22){
                $dataSet['8PM-9.59PM'][] = $trackingRecieveBc;
            }else if($hourlyTrackingBC > 22 && $hourlyTrackingBC < 24){
                $dataSet['10PM-11.59PM'][] = $trackingRecieveBc;
            }
        }

        foreach ($campaignSendMessages as $key => $campaignSendMessage) {
            $hourlyCampaignSendMessage = $campaignSendMessage->created_at->format('H.i');
            if($hourlyCampaignSendMessage > 0 && $hourlyCampaignSendMessage < 2){
                $dataSetCampaign['12AM-1.59AM'][] = $campaignSendMessage;
            }else if($hourlyCampaignSendMessage > 2 && $hourlyCampaignSendMessage < 4){
                $dataSetCampaign['2AM-3.59AM'][] = $campaignSendMessage;
            }else if($hourlyCampaignSendMessage > 4 && $hourlyCampaignSendMessage < 6){
                $dataSetCampaign['4AM-5.59AM'][] = $campaignSendMessage;
            }else if($hourlyCampaignSendMessage > 6 && $hourlyCampaignSendMessage < 8){
                $dataSetCampaign['6AM-7.59AM'][] = $campaignSendMessage;
            }else if($hourlyCampaignSendMessage > 8 && $hourlyCampaignSendMessage < 10){
                $dataSetCampaign['8AM-9.59AM'][] = $campaignSendMessage;
            }else if($hourlyCampaignSendMessage > 10 && $hourlyCampaignSendMessage < 12){
                $dataSetCampaign['10AM-11.59AM'][] = $campaignSendMessage;
            }else if($hourlyCampaignSendMessage > 12 && $hourlyCampaignSendMessage < 14){
                $dataSetCampaign['12PM-1.59PM'][] = $campaignSendMessage;
            }else if($hourlyCampaignSendMessage > 14 && $hourlyCampaignSendMessage < 16){
                $dataSetCampaign['2PM-3.59PM'][] = $campaignSendMessage;
            }else if($hourlyCampaignSendMessage > 16 && $hourlyCampaignSendMessage < 18){
                $dataSetCampaign['4PM-5.59PM'][] = $campaignSendMessage;
            }else if($hourlyCampaignSendMessage > 18 && $hourlyCampaignSendMessage < 20){
                $dataSetCampaign['6PM-7.59PM'][] = $campaignSendMessage;
            }else if($hourlyCampaignSendMessage > 20 && $hourlyCampaignSendMessage < 22){
                $dataSetCampaign['8PM-9.59PM'][] = $campaignSendMessage;
            }else if($hourlyCampaignSendMessage > 22 && $hourlyCampaignSendMessage < 24){
                $dataSetCampaign['10PM-11.59PM'][] = $campaignSendMessage;
            }
        }

        // $dataCollect->merge([$dataSet]);

        // dd(collect($dataSet));
        // $dataSet = collect($dataSet);
        // dd($dataSet);
        foreach ($dataSetCampaign as $time => $dataValues) {
            $dataSetCampaign[$time] = collect($dataValues);
        }

        foreach ($dataSetCampaign as $time => $dataValues) {
            $datas[$time]['total_sent'] = $dataValues->count();
            $datas[$time]['total_sent_rate'] = ($datas[$time]['total_sent']*100)/$countAllSent;
        }

        foreach ($dataSet as $time => $dataValues) {
            $dataSet[$time] = collect($dataValues);
        }


        foreach ($dataSet as $time => $dataValues) {
            $datas[$time]['total_click'] = $dataValues->count();
            $datas[$time]['total_click_rate'] = ($datas[$time]['total_click']*100)/$countAllClick;
            $datas[$time]['total_unique_click'] = $dataValues->unique('line_user_id')->count();
            $datas[$time]['total_unique_click_rate'] = ($datas[$time]['total_unique_click']*100)/$countAllUnique;
        }

        // dd($datas);
        return response()->json([
            'datas' => $datas,
        ]);
    }

    public function reportCampaignStatistic(Request $request)
    {
        $datas = [];
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $onlyNewUser = $request->only_new_user;
        $campaignIds = $request->campaign_id;
        // $campaignIds = [];
        // $campaignNames = [];
        // if(count($campaignIds) > 0){
        //     foreach ($campaignIds as $key => $campaignId) {
        //         $campaign = Campaign::find($campaignId);
        //         if($campaign){
        //             $campaignNames[] = $campaign->name;
        //         }
        //     }
        // }
        if(($startDate && $endDate) && ($startDate != '' && $endDate != '')){
            if(count($campaignIds) > 0){
                $campaignSendMessages = CampaignSendMessage::whereBetween('created_at', array($startDate, $endDate))->whereIn('campaign_id',$campaignIds)->get();
                $trackingRecieveBcs = TrackingRecieveBc::whereBetween('created_at', array($startDate, $endDate))->whereIn('campaign_id',$campaignIds)->get();
            }else{
                $campaignSendMessages = CampaignSendMessage::whereBetween('created_at', array($startDate, $endDate))->get();
                $trackingRecieveBcs = TrackingRecieveBc::whereBetween('created_at', array($startDate, $endDate))->get();
            }
        }else{
            if(count($campaignIds) > 0){
                $campaignSendMessages = CampaignSendMessage::whereIn('campaign_id',$campaignIds)->get();
                $trackingRecieveBcs = TrackingRecieveBc::whereIn('campaign_id',$campaignIds)->get();
            }else{
                $campaignSendMessages = CampaignSendMessage::all();
                $trackingRecieveBcs = TrackingRecieveBc::all();
            }
        }

        if($onlyNewUser){
            $trackingRecieveBcs = $trackingRecieveBcs->unique('line_user_id');
        }

        $campaignSendMessageGroupBys = $campaignSendMessages->groupBy(function($date) {
            return Carbon::parse($date->created_at)->format('Y-m-d');
        });

        $trackingRecieveBcGroupBys = $trackingRecieveBcs->groupBy(function($date) {
            return Carbon::parse($date->created_at)->format('Y-m-d');
        });

        // dd($trackingRecieveBcGroupBys);

        if($onlyNewUser){
            foreach ($campaignSendMessageGroupBys as $key => $campaignSendMessageGroupBy) {
                $datas[$key]['total_count_sent'] = $campaignSendMessageGroupBy->count();
                $campaignClicks = $trackingRecieveBcGroupBys->get($key);
                if($campaignClicks){
                    $datas[$key]['total_count_click'] = $campaignClicks->count();
                    $datas[$key]['total_count_unique_click'] = $campaignClicks->unique('mid')->count();
                }else{
                    $datas[$key]['total_count_click'] = 0;
                    $datas[$key]['total_count_unique_click'] = 0;
                }
                // dd($campaignSendMessageGroupBy);
            }
        }else{
            foreach ($campaignSendMessageGroupBys as $key => $campaignSendMessageGroupBy) {
                $datas[$key]['total_count_sent'] = $campaignSendMessageGroupBy->count();
                $campaignClicks = $trackingRecieveBcGroupBys->get($key);
                if($campaignClicks){
                    $datas[$key]['total_count_click'] = $campaignClicks->count();
                    $datas[$key]['total_count_unique_click'] = $campaignClicks->unique('mid')->count();
                }else{
                    $datas[$key]['total_count_click'] = 0;
                    $datas[$key]['total_count_unique_click'] = 0;
                }

                // dd($campaignSendMessageGroupBy);
            }
        }
        // dd($datas);

        return response()->json([
            'datas' => $datas,
        ]);
    }

    public function reportUpCommingEvent(Request $request)
    {
        $datas = [];
        $campaigns  = Campaign::orderBy('updated_at','desc')->get();
        foreach ($campaigns as $key => $campaign) {
            $campaignSentMessages = $campaign->campaignSendMessages;
            $sentDate = "";
            $lastSentDate = "";
            $status = "";
            
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
            $datas[$key]['sent_date'] = ($sentDate != '')? $sentDate : '-';
            $datas[$key]['last_sent_date'] = ($lastSentDate != '')? $lastSentDate : '-';
            $datas[$key]['status'] = ($status != '')? $status : '-';
        }


        return response()->json([
            'datas' => $datas,
        ]);
    }

    public function reportFriendAddBlock(Request $request)
    {
        $datas = [];
        $historyAddBlocks  = HistoryAddBlock::all();
        $historyAddBlockGroupBys = $historyAddBlocks->groupBy(function($date) {
            return Carbon::parse($date->updated_at)->format('Y-m-d');
        });

        foreach ($historyAddBlockGroupBys as $key => $recieveMessageGroupBy) {
            $datas[$key]['add'] = $recieveMessageGroupBy->where('action','follow')->count();
            $datas[$key]['block'] = $recieveMessageGroupBy->where('action','unfollow')->count();
        }
        // $countAdd = $historyAddBlocks->where('action','follow')->count();
        // $countBlock = $historyAddBlocks->where('action','unfollow')->count();
        // $datas[0]['add'] = $countAdd;
        // $datas[0]['block'] = $countBlock;

        return response()->json([
            'datas' => $datas,
        ]);
    }

    public function reportRecieveMessageMonitor(Request $request)
    {
        $datas = [];
        $recieveMessages = RecieveMessage::select('*', \DB::raw('count(*) as countKeyword'), \DB::raw('max(updated_at) as updated_at'))
                 ->groupBy('keyword','bot_reply')->orderByDesc('updated_at')->get();

        return response()->json([
            'datas' => $recieveMessages->take(50),
        ]);
    }

    public function reportKeywordStatistic(Request $request)
    {
        $datas = [];
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        
        if($startDate && $endDate){
            // $pushOrReplys = PushOrReply::whereBetween('created_at', array($startDate, $endDate))->where('action','reply')->get();
            $pushOrReplys = PushOrReply::whereBetween('created_at', array($startDate, $endDate))->get();
            // $recieveMessages = RecieveMessage::whereBetween('created_at', array($startDate, $endDate))->whereNotNull('bot_reply')->get();
            $recieveMessages = RecieveMessage::whereBetween('created_at', array($startDate, $endDate))->get();
        }else{
            $pushOrReplys = PushOrReply::all();
            // $recieveMessages = RecieveMessage::whereNotNull('bot_reply')->get();
            $recieveMessages = RecieveMessage::all();
        }

        $pushOrReplyGroupBys = $pushOrReplys->groupBy(function($date) {
            return Carbon::parse($date->created_at)->format('Y-m-d');
        });

        $recieveMessageGroupBys = $recieveMessages->groupBy(function($date) {
            return Carbon::parse($date->created_at)->format('Y-m-d');
        });

        foreach ($recieveMessageGroupBys as $key => $recieveMessageGroupBy) {
            $datas[$key]['count_user_input'] = $recieveMessageGroupBy->count();
        }

        foreach ($pushOrReplyGroupBys as $key => $pushOrReplyGroupBy) {
            $datas[$key]['count_reply'] = $pushOrReplyGroupBy->count();
        }

        return response()->json([
            'datas' => $datas,
        ]);
    }

    public function reportCampaignStatisticCampaign(Request $request)
    {
        $datas = [];
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $onlyNewUser = $request->only_new_user;
        // $campaignIds = $request->campaign_id;
        $campaignIds = [];
        $campaignNames = [];
        if(count($campaignIds) > 0){
            foreach ($campaignIds as $key => $campaignId) {
                $campaign = Campaign::find($campaignId);
                if($campaign){
                    $campaignNames[] = $campaign->name;
                }
            }
        }
        if($startDate && $endDate){
            if(count($campaignIds) > 0){
                $campaignSendMessages = CampaignSendMessage::whereBetween('created_at', array($startDate, $endDate))->whereIn('campaign_id',$campaignIds)->get();
                $trackingRecieveBcs = TrackingRecieveBc::whereBetween('created_at', array($startDate, $endDate))->whereIn('tracking_campaign',$campaignNames)->get();
            }else{
                $campaignSendMessages = CampaignSendMessage::whereBetween('created_at', array($startDate, $endDate))->get();
                $trackingRecieveBcs = TrackingRecieveBc::whereBetween('created_at', array($startDate, $endDate))->get();
            }
        }else{
            if(count($campaignIds) > 0){
                $campaignSendMessages = CampaignSendMessage::whereIn('campaign_id',$campaignIds)->get();
                $trackingRecieveBcs = TrackingRecieveBc::whereIn('tracking_campaign',$campaignNames)->get();
            }else{
                $campaignSendMessages = CampaignSendMessage::all();
                $trackingRecieveBcs = TrackingRecieveBc::all();
            }
        }

        if($onlyNewUser){
            $trackingRecieveBcs = $trackingRecieveBcs->unique('line_user_id');
        }

        $campaignSendMessageGroupBys = $campaignSendMessages->groupBy('campaign_id');

        $trackingRecieveBcGroupBys = $trackingRecieveBcs->groupBy('tracking_campaign');

        
        foreach ($campaignSendMessageGroupBys as $key => $campaignSendMessageGroupBy) {
            $campaign = Campaign::find($key);
            $cmapaignName = $campaign->name;
            $datas[$cmapaignName]['total_count_sent'] = $campaignSendMessageGroupBy->count();
            $campaignClicks = $trackingRecieveBcGroupBys->get($cmapaignName);
            if($campaignClicks){
                // $datas[$cmapaignName]['total_count_click'] = $campaignClicks->count();
                // $datas[$cmapaignName]['total_count_unique_click'] = $campaignClicks->unique('mid')->count();
            }else{
                // $datas[$cmapaignName]['total_count_click'] = 0;
                // $datas[$cmapaignName]['total_count_unique_click'] = 0;
            }
        }

        return response()->json([
            'datas' => $datas,
        ]);
    }

    public function reportTrackingBCByTrackingBC(Request $request)
    {
        $datas = [];
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $isUnique = $request->is_unique;
        $trackingSources = $request->tracking_source;
        $trackingRefs = $request->tracking_ref;
        $trackingCampaigns = $request->tracking_campagin;
        

        return response()->json([
            'datas' => $datas,
        ]);
    }
}
