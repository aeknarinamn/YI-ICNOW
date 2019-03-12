<?php

namespace YellowProject\Segment;

use Illuminate\Database\Eloquent\Model;
use YellowProject\Segment\Segment;
use YellowProject\Segment\SegmentCondition;
use YellowProject\Segment\SegmentConditionItem;
use YellowProject\Segment\SegmentSubscriber;
use YellowProject\Subscriber;
use YellowProject\SubscriberLine;
use YellowProject\Beacon;
use YellowProject\Campaign;
use YellowProject\Field;
use YellowProject\TrackingBc;
use YellowProject\LineUserProfile;
use YellowProject\CouponUser;
use YellowProject\Coupon;
use YellowProject\HistoryAddBlock;
use YellowProject\Segment\SegmentQuery;
use Carbon\Carbon;

class CoreFunction extends Model
{
    public static function queryData($request,$segment)
    {
        $getDatas = collect();
    	$checkLineUserId = in_array("LINE-userID",$request->subscriber_list);
        $subscriberIds = Subscriber::whereIn('category_id',$request->subscriber_list)->pluck('id')->toArray();
        $request->subscriber_list = $subscriberIds;
        $subscriberLines = SubscriberLine::whereIn('subscriber_id',$request->subscriber_list)->whereHas('lineUserProfile', function ($query) {
                $query->whereHas('historyAddBlocks', function ($query) {
                    $query->select('*', \DB::raw('max(created_at) as latest_date'))
                    ->groupBy('line_user_id')
                    ->orderBy('latest_date')
                    ->where('action','follow');
                });
            });
        $lineUserProfiles = LineUserProfile::orderBy('created_at')->whereHas('historyAddBlocks', function ($query) {
                $query->select('*', \DB::raw('max(created_at) as latest_date'))
                ->groupBy('line_user_id')
                ->orderBy('latest_date')
                ->where('action','follow');
            });
        $fields = Field::whereIn('subscriber_id',$request->subscriber_list)->get();
        // dd($fields);
        if(count($request['condition']) > 0){
            foreach ($request['condition'] as $keyCondition => $conditions) {
                $conditionDatas = collect();
                $subscriberMatch = $conditions['subscriber_match'];
                if(count($conditions['condition_items']) > 0){
                    // return response()->json($conditions['condition_items']);
                    foreach ($conditions['condition_items'] as $keyconditionItem => $conditionItems) {
                        $subscriberLines = SubscriberLine::whereIn('subscriber_id',$request->subscriber_list)->whereHas('lineUserProfile', function ($query) use ($conditionItems) {
                                $query->whereHas('historyAddBlocks', function ($query) use ($conditionItems) {
                                    $query->select('*', \DB::raw('max(created_at) as latest_date'))
                                    ->groupBy('line_user_id')
                                    ->orderBy('latest_date')
                                    ->where('action','follow');
                                });
                            });
                        $lineUserProfiles = LineUserProfile::orderBy('created_at')->whereHas('historyAddBlocks', function ($query) use ($conditionItems) {
                                $query->select('*', \DB::raw('max(created_at) as latest_date'))
                                ->groupBy('line_user_id')
                                ->orderBy('latest_date')
                                ->where('action','follow');
                            });
                        if($conditionItems['title'] == 'Subscriber Data'){
                            $subscriberConditionDatas = collect();
                            if($conditionItems['remark1'] == 'line_id'){
                                if($conditionItems['value1'] != 0){
                                    $subscriberLines->where('line_user_id',$conditionItems['value1']);
                                }
                            }else{
                                $subscriberLines->whereHas('subscriberItems', function ($query) use ($conditionItems) {
                                    $query->where('field_id',$conditionItems['condition1']);
                                    if($conditionItems['condition2'] == 'is'){
                                        $query->where('value',$conditionItems['value1']);
                                    }else if($conditionItems['condition2'] == 'is not'){
                                        $query->where('value','<>',$conditionItems['value1']);
                                    }else if($conditionItems['condition2'] == 'is empty'){
                                        $query->where('value','');
                                    }else if($conditionItems['condition2'] == 'is not empty'){
                                        $query->where('value','<>','');
                                    }else if($conditionItems['condition2'] == 'contains'){
                                        $query->where('value','like','%'.$conditionItems['value1'].'%');
                                    }else if($conditionItems['condition2'] == 'does not contain' || $conditionItems['condition2'] == 'does not contains'){
                                        $query->where('value','not like','%'.$conditionItems['value1'].'%');
                                    }else if($conditionItems['condition2'] == 'starts with'){
                                        $query->where('value','like',$conditionItems['value1'].'%');
                                    }else if($conditionItems['condition2'] == 'ends with'){
                                        $query->where('value','like','%'.$conditionItems['value1']);
                                    }else if($conditionItems['condition2'] == 'gather than'){
                                        $query->where('value','>',(int)$conditionItems['value1']);
                                    }else if($conditionItems['condition2'] == 'less than'){
                                        $query->where('value','<',(int)$conditionItems['value1']);
                                    }else if($conditionItems['condition2'] == 'gather than or equal'){
                                        $query->where('value','>=',(int)$conditionItems['value1']);
                                    }else if($conditionItems['condition2'] == 'less than or equal'){
                                        $query->where('value','<=',(int)$conditionItems['value1']);
                                    }else if($conditionItems['condition2'] == 'is between' && $conditionItems['remark1']  != 'date'){
                                        $query->whereBetween('value', [(float)$conditionItems['value1'], (float)$conditionItems['value2']]);
                                    }else if($conditionItems['condition2'] == 'is after'){
                                        $query->whereDate('value', '>', $conditionItems['value1']);
                                    }else if($conditionItems['condition2'] == 'is before'){
                                        $query->whereDate('value', '<', $conditionItems['value1']);
                                    }else if($conditionItems['condition2'] == 'is on'){
                                        $query->whereDate('value', '=', $conditionItems['value1']);
                                    }else if($conditionItems['condition2'] == 'is not on'){
                                        $query->whereDate('value', '<>', $conditionItems['value1']);
                                    }else if($conditionItems['condition2'] == 'is on or before'){
                                        $query->whereDate('value', '<=', $conditionItems['value1']);
                                    }else if($conditionItems['condition2'] == 'is on or after'){
                                        $query->whereDate('value', '>=', $conditionItems['value1']);
                                    }else if($conditionItems['condition2'] == 'is between'){
                                        $query->whereBetween('value',[$conditionItems['value1'],$conditionItems['value2']]);
                                    }else if($conditionItems['condition2'] == 'is not between'){
                                        $query->whereNotBetween('value',[$conditionItems['value1'],$conditionItems['value2']]);
                                    }
                                    else if($conditionItems['condition2'] == 'absolute date'){
                                        $dateNow = Carbon::now();
                                        if($conditionItems['value1'] == 'today'){
                                            $query->whereDate('value', '=', $dateNow->format('Y-m-d'));
                                        }else if($conditionItems['value1'] == 'yesterday'){
                                            $query->whereDate('value', '=', $dateNow->addDay(-1)->format('Y-m-d'));
                                        }else{
                                            $query->whereDate('value', '=', $dateNow->addDay(1)->format('Y-m-d'));
                                        }
                                    }
                                    else{

                                    }
                                });
                            }

                            if($checkLineUserId){
                                if($conditionItems['remark1'] == 'line_id'){
                                    if($conditionItems['value1'] != 0){
                                        $lineUserProfiles->where('id',$conditionItems['value1']);
                                    }
                                }else{
                                    if($conditionItems['condition1'] == 'follow_first_date'){
                                        $lineUserProfiles->whereHas('historyAddBlocks', function ($query) use ($conditionItems) {
                                            $query->select('*', \DB::raw('min(created_at) as latest_date'))
                                            ->groupBy('line_user_id')
                                            ->orderBy('latest_date')
                                            ->where('action','follow');
                                            if($conditionItems['condition2'] == 'is empty'){
                                                $query->where('created_at','');
                                            }else if($conditionItems['condition2'] == 'is not empty'){
                                                $query->where('created_at','<>','');
                                            }else if($conditionItems['condition2'] == 'is after'){
                                                $query->whereDate('created_at', '>', $conditionItems['value1']);
                                            }else if($conditionItems['condition2'] == 'is before'){
                                                $query->whereDate('created_at', '<', $conditionItems['value1']);
                                            }else if($conditionItems['condition2'] == 'is on'){
                                                $query->whereDate('created_at', '=', $conditionItems['value1']);
                                            }else if($conditionItems['condition2'] == 'is not on'){
                                                $query->whereDate('created_at', '<>', $conditionItems['value1']);
                                            }else if($conditionItems['condition2'] == 'is on or before'){
                                                $query->whereDate('created_at', '<=', $conditionItems['value1']);
                                            }else if($conditionItems['condition2'] == 'is on or after'){
                                                $query->whereDate('created_at', '>=', $conditionItems['value1']);
                                            }else if($conditionItems['condition2'] == 'is between'){
                                                $query->whereBetween('created_at',[$conditionItems['value1'],$conditionItems['value2']]);
                                            }else if($conditionItems['condition2'] == 'is not between'){
                                                $query->whereNotBetween('created_at',[$conditionItems['value1'],$conditionItems['value2']]);
                                            }
                                        });
                                    }else if($conditionItems['condition1'] == 'follow_update_date'){
                                        $lineUserProfiles->whereHas('historyAddBlocks', function ($query) use ($conditionItems) {
                                            $query->select('*', \DB::raw('max(updated_at) as latest_date'))
                                            ->groupBy('line_user_id')
                                            ->orderBy('latest_date')
                                            ->where('action','follow');
                                            if($conditionItems['condition2'] == 'is empty'){
                                                $query->where('updated_at','');
                                            }else if($conditionItems['condition2'] == 'is not empty'){
                                                $query->where('updated_at','<>','');
                                            }else if($conditionItems['condition2'] == 'is after'){
                                                $query->whereDate('updated_at', '>', $conditionItems['value1']);
                                            }else if($conditionItems['condition2'] == 'is before'){
                                                $query->whereDate('updated_at', '<', $conditionItems['value1']);
                                            }else if($conditionItems['condition2'] == 'is on'){
                                                $query->whereDate('updated_at', '=', $conditionItems['value1']);
                                            }else if($conditionItems['condition2'] == 'is not on'){
                                                $query->whereDate('updated_at', '<>', $conditionItems['value1']);
                                            }else if($conditionItems['condition2'] == 'is on or before'){
                                                $query->whereDate('updated_at', '<=', $conditionItems['value1']);
                                            }else if($conditionItems['condition2'] == 'is on or after'){
                                                $query->whereDate('updated_at', '>=', $conditionItems['value1']);
                                            }else if($conditionItems['condition2'] == 'is between'){
                                                $query->whereBetween('updated_at',[$conditionItems['value1'],$conditionItems['value2']]);
                                            }else if($conditionItems['condition2'] == 'is not between'){
                                                $query->whereNotBetween('updated_at',[$conditionItems['value1'],$conditionItems['value2']]);
                                            }
                                        });
                                    }
                                }

                                $subscriberConditionDatas->push($lineUserProfiles->pluck('id'));
                            }
                            
                            $subscriberConditionDatas->push($subscriberLines->pluck('line_user_id'));
                            // $conditionDatas->push($subscriberLines->pluck('line_user_id'));
                            if($conditionItems['is_empty']){
                                $subscriberLines = SubscriberLine::whereIn('subscriber_id',$request->subscriber_list);
                                $subscriberLines->whereHas('subscriberItems', function ($query) use ($conditionItems) {
                                    $query->where('field_id',$conditionItems['condition1']);
                                    $query->where('value','');
                                });
                                $subscriberConditionDatas->push($subscriberLines->pluck('line_user_id'));
                                // $conditionDatas->push($subscriberLines->pluck('line_user_id'));
                                // $conditionDatas = $conditionDatas->collapse()->unique();
                                // return response()->json($conditionDatas);
                            }
                            $conditionDatas->push($subscriberConditionDatas->collapse()->unique());
                        }else if($conditionItems['title'] == 'sent activity'){
                            if($conditionItems['condition1'] == 'was sent'){
                                if($conditionItems['condition2'] == 'at anytime'){
                                    $lineUserProfiles->has('campaignSendMessages');
                                }else{
                                    $dateNow = Carbon::now();
                                    $lineUserProfiles->whereHas('campaignSendMessages', function ($query) use ($conditionItems,$dateNow) {
                                        if($conditionItems['condition2'] == 'is after'){
                                            $query->whereDate('created_at','>',$conditionItems['value1']);
                                        }else if($conditionItems['condition2'] == 'is before'){
                                            $query->whereDate('created_at','<',$conditionItems['value1']);
                                        }else if($conditionItems['condition2'] == 'is on'){
                                            $query->whereDate('created_at','=',$conditionItems['value1']);
                                        }else if($conditionItems['condition2'] == 'is not on'){
                                            $query->whereDate('created_at','<>',$conditionItems['value1']);
                                        }else if($conditionItems['condition2'] == 'is on or before'){
                                            $query->whereDate('created_at','<=',$conditionItems['value1']);
                                        }
                                        else if($conditionItems['condition2'] == 'is on or after'){
                                            $query->whereDate('created_at','>=',$conditionItems['value1']);
                                        }else if($conditionItems['condition2'] == 'is between'){
                                            $query->whereBetween('created_at', [$conditionItems['value1'], $conditionItems['value2']]);
                                        }else if($conditionItems['condition2'] == 'is not between'){
                                            $query->whereNotBetween('created_at', [$conditionItems['value1'], $conditionItems['value2']]);
                                        }else if($conditionItems['condition2'] == 'relative date'){
                                            if($conditionItems['value1'] == 'first day'){
                                                if($conditionItems['value2'] == 'of this month'){
                                                    $dateCondition = $dateNow->format('F Y');
                                                    $newDateCondition = new Carbon('first day of '.$dateCondition);
                                                    $query->where('created_at','=',$newDateCondition);
                                                    // dd($newDateCondition);
                                                }else if($conditionItems['value2'] == 'of this year'){
                                                    $dateCondition = $dateNow->format('Y');
                                                    $newDateCondition = new Carbon('first day of January '.$dateCondition);
                                                    $query->where('created_at','=',$newDateCondition);
                                                }
                                            }else if($conditionItems['value1'] == 'last day'){
                                                if($conditionItems['value2'] == 'of this month'){
                                                    $dateCondition = $dateNow->format('F Y');
                                                    $newDateCondition = new Carbon('last day of '.$dateCondition);
                                                    $query->where('created_at','=',$newDateCondition);
                                                    // dd($newDateCondition);
                                                }else if($conditionItems['value2'] == 'of this year'){
                                                    $dateCondition = $dateNow->format('Y');
                                                    $newDateCondition = new Carbon('last day of December '.$dateCondition);
                                                    $query->where('created_at','=',$newDateCondition);
                                                }
                                            }else if($conditionItems['value1'] == 'sunday'){
                                                $weekOfConditionDay = Carbon::SUNDAY;
                                                $dateCondition = $dateNow->addDays($weekOfConditionDay);
                                                $query->where('created_at','=',$dateCondition);
                                            }else if($conditionItems['value1'] == 'monday'){
                                                $weekOfConditionDay = Carbon::MONDAY;
                                                $dateCondition = $dateNow->addDays($weekOfConditionDay);
                                                $query->where('created_at','=',$dateCondition);
                                            }else if($conditionItems['value1'] == 'tuesday'){
                                                $weekOfConditionDay = Carbon::TUESDAY;
                                                $dateCondition = $dateNow->addDays($weekOfConditionDay);
                                                $query->where('created_at','=',$dateCondition);
                                            }else if($conditionItems['value1'] == 'wednesday'){
                                                $weekOfConditionDay = Carbon::WEDNESDAY;
                                                $dateCondition = $dateNow->addDays($weekOfConditionDay);
                                                $query->where('created_at','=',$dateCondition);
                                            }else if($conditionItems['value1'] == 'thursday'){
                                                $weekOfConditionDay = Carbon::THURSDAY;
                                                $dateCondition = $dateNow->addDays($weekOfConditionDay);
                                                $query->where('created_at','=',$dateCondition);
                                            }else if($conditionItems['value1'] == 'friday'){
                                                $weekOfConditionDay = Carbon::FRIDAY;
                                                $dateCondition = $dateNow->addDays($weekOfConditionDay);
                                                $query->where('created_at','=',$dateCondition);
                                            }else if($conditionItems['value1'] == 'saturday'){
                                                $weekOfConditionDay = Carbon::SATURDAY;
                                                $dateCondition = $dateNow->addDays($weekOfConditionDay);
                                                $query->where('created_at','=',$dateCondition);
                                            }else if($conditionItems['value1'] == 'Day #'){
                                                $dateCondition = "";
                                                if($conditionItems['value3'] == 'day'){
                                                    $dateCondition = $dateNow->addDays($conditionItems['value2']);
                                                }else if($conditionItems['value3'] == 'week'){
                                                    $dateCondition = $dateNow->addWeeks($conditionItems['value2']);
                                                }else if($conditionItems['value3'] == 'month'){
                                                    $dateCondition = $dateNow->addMonths($conditionItems['value2']);
                                                }else if($conditionItems['value3'] == 'year'){
                                                    $dateCondition = $dateNow->addYears($conditionItems['value2']);
                                                }

                                                if($conditionItems['value4'] == 'from now'){
                                                    $query->where('created_at','>=',$dateCondition->format('Y-m-d'));
                                                }else{
                                                    $query->where('created_at','<=',$dateCondition->format('Y-m-d'));
                                                }
                                            }
                                        }else if($conditionItems['condition2'] == 'absolute date'){
                                            if($conditionItems['value1'] == 'today'){
                                                $dateCondition = $dateNow;
                                                $query->where('created_at','>=',$dateCondition->format('Y-m-d'));
                                            }else if($conditionItems['value1'] == 'yesterday'){
                                                $dateCondition = $dateNow->addDays(-1);
                                                $query->where('created_at','>=',$dateCondition->format('Y-m-d'));
                                            }else if($conditionItems['value1'] == 'tomorrow'){
                                                $dateCondition = $dateNow->addDays(1);
                                                $query->where('created_at','>=',$dateCondition->format('Y-m-d'));
                                            }
                                        }else{
                                            
                                        }
                                    });
                                }
                            }else if($conditionItems['condition1'] == 'was not sent'){
                                $dateNow = Carbon::now();
                                $lineUserProfiles->whereDoesntHave('campaignSendMessages', function ($query) use ($conditionItems,$dateNow) {
                                    // dd($segmentConditionItem);
                                    if($conditionItems['condition2'] == 'is after'){
                                        $query->where('created_at','>',$conditionItems['value1']);
                                    }else if($conditionItems['condition2'] == 'is before'){
                                        $query->where('created_at','<',$conditionItems['value1']);
                                    }else if($conditionItems['condition2'] == 'is on'){
                                        $query->where('created_at','=',$conditionItems['value1']);
                                    }else if($conditionItems['condition2'] == 'is not on'){
                                        $query->where('created_at','<>',$conditionItems['value1']);
                                    }else if($conditionItems['condition2'] == 'is on or after'){
                                        $query->where('created_at','<=',$conditionItems['value1']);
                                    }else if($conditionItems['condition2'] == 'is between'){
                                        $query->whereBetween('created_at', [$conditionItems['value1'], $conditionItems['value2']]);
                                    }else if($conditionItems['condition2'] == 'is not between'){
                                        $query->whereNotBetween('created_at', [$conditionItems['value1'], $conditionItems['value2']]);
                                    }else if($conditionItems['condition2'] == 'relative date'){
                                        if($conditionItems['value1'] == 'first day'){
                                            if($conditionItems['value2'] == 'of this month'){
                                                $dateCondition = $dateNow->format('F Y');
                                                $newDateCondition = new Carbon('first day of '.$dateCondition);
                                                $query->where('created_at','=',$newDateCondition);
                                                // dd($newDateCondition);
                                            }else if($conditionItems['value2'] == 'of this year'){
                                                $dateCondition = $dateNow->format('Y');
                                                $newDateCondition = new Carbon('first day of January '.$dateCondition);
                                                $query->where('created_at','=',$newDateCondition);
                                            }
                                        }else if($conditionItems['value1'] == 'last day'){
                                            if($conditionItems['value2'] == 'of this month'){
                                                $dateCondition = $dateNow->format('F Y');
                                                $newDateCondition = new Carbon('last day of '.$dateCondition);
                                                $query->where('created_at','=',$newDateCondition);
                                                // dd($newDateCondition);
                                            }else if($conditionItems['value2'] == 'of this year'){
                                                $dateCondition = $dateNow->format('Y');
                                                $newDateCondition = new Carbon('last day of December '.$dateCondition);
                                                $query->where('created_at','=',$newDateCondition);
                                            }
                                        }else if($conditionItems['value1'] == 'sunday'){
                                            $weekOfConditionDay = Carbon::SUNDAY;
                                            $dateCondition = $dateNow->addDays($weekOfConditionDay);
                                            $query->where('created_at','=',$dateCondition);
                                        }else if($conditionItems['value1'] == 'monday'){
                                            $weekOfConditionDay = Carbon::MONDAY;
                                            $dateCondition = $dateNow->addDays($weekOfConditionDay);
                                            $query->where('created_at','=',$dateCondition);
                                        }else if($conditionItems['value1'] == 'tuesday'){
                                            $weekOfConditionDay = Carbon::TUESDAY;
                                            $dateCondition = $dateNow->addDays($weekOfConditionDay);
                                            $query->where('created_at','=',$dateCondition);
                                        }else if($conditionItems['value1'] == 'wednesday'){
                                            $weekOfConditionDay = Carbon::WEDNESDAY;
                                            $dateCondition = $dateNow->addDays($weekOfConditionDay);
                                            $query->where('created_at','=',$dateCondition);
                                        }else if($conditionItems['value1'] == 'thursday'){
                                            $weekOfConditionDay = Carbon::THURSDAY;
                                            $dateCondition = $dateNow->addDays($weekOfConditionDay);
                                            $query->where('created_at','=',$dateCondition);
                                        }else if($conditionItems['value1'] == 'friday'){
                                            $weekOfConditionDay = Carbon::FRIDAY;
                                            $dateCondition = $dateNow->addDays($weekOfConditionDay);
                                            $query->where('created_at','=',$dateCondition);
                                        }else if($conditionItems['value1'] == 'saturday'){
                                            $weekOfConditionDay = Carbon::SATURDAY;
                                            $dateCondition = $dateNow->addDays($weekOfConditionDay);
                                            $query->where('created_at','=',$dateCondition);
                                        }else if($conditionItems['value1'] == 'Day #'){
                                            $dateCondition = "";
                                            if($conditionItems['value3'] == 'day'){
                                                $dateCondition = $dateNow->addDays($conditionItems['value2']);
                                            }else if($conditionItems['value3'] == 'week'){
                                                $dateCondition = $dateNow->addWeeks($conditionItems['value2']);
                                            }else if($conditionItems['value3'] == 'month'){
                                                $dateCondition = $dateNow->addMonths($conditionItems['value2']);
                                            }else if($conditionItems['value3'] == 'year'){
                                                $dateCondition = $dateNow->addYears($conditionItems['value2']);
                                            }

                                            if($conditionItems['value4'] == 'from now'){
                                                $query->where('created_at','>=',$dateCondition->format('Y-m-d'));
                                            }else{
                                                $query->where('created_at','<=',$dateCondition->format('Y-m-d'));
                                            }
                                        }
                                    }else if($conditionItems['condition2'] == 'absolute date'){
                                        if($conditionItems['value1'] == 'today'){
                                            $dateCondition = $dateNow;
                                            $query->where('created_at','>=',$dateCondition->format('Y-m-d'));
                                        }else if($conditionItems['value1'] == 'yesterday'){
                                            $dateCondition = $dateNow->addDays(-1);
                                            $query->where('created_at','>=',$dateCondition->format('Y-m-d'));
                                        }else if($conditionItems['value1'] == 'tomorrow'){
                                            $dateCondition = $dateNow->addDays(1);
                                            $query->where('created_at','>=',$dateCondition->format('Y-m-d'));
                                        }
                                    }else{
                                        
                                    }
                                });
                            }

                            if($conditionItems['condition3'] == 'is'){
                                $lineUserProfiles->whereHas('campaignSendMessages', function ($query) use ($conditionItems) {
                                    $query->where('campaign_id',$conditionItems['value5']);
                                });
                            }else if($conditionItems['condition3'] == 'is not'){
                                $lineUserProfiles->whereDoesntHave('campaignSendMessages', function ($query) use ($conditionItems) {
                                    $query->where('campaign_id',$conditionItems['value5']);
                                });
                            }
                            $conditionDatas->push($lineUserProfiles->pluck('id'));
                            // dd($datas);
                        }else if($conditionItems['title'] == 'BC Tracking'){
                            $lineUserProfiles->whereHas('trackingRecieveBcs', function ($query) use ($conditionItems) {
                                if($conditionItems['value1'] != 'All source'){
                                    $query->where('tracking_source',$conditionItems['value1']);
                                }
                                if($conditionItems['value2'] != 'All Campaign'){
                                    $query->where('tracking_campaign',$conditionItems['value2']);
                                }
                                if($conditionItems['value3'] != 'All Ref'){
                                    $query->where('tracking_ref',$conditionItems['value3']);
                                }
                            });
                            if($conditionItems['value6'] != "" && $conditionItems['value6'] != 0){
                                $lineUserProfiles->has('trackingRecieveBcs', '>=', $conditionItems['value6']);
                            }
                            $conditionDatas->push($lineUserProfiles->pluck('id'));
                            // dd($datas);
                        }else if($conditionItems['title'] == 'Redeem'){
                            // $couponUsers = CouponUser::orderBy('created_at');
                            if($conditionItems['condition1'] == 'was redeem'){
                                if($conditionItems['condition2'] == 'at anytime'){
                                    $lineUserProfiles->has('couponUsers');
                                }else{
                                    $dateNow = Carbon::now();
                                    $lineUserProfiles->whereHas('couponUsers', function ($query) use ($conditionItems,$dateNow) {
                                        if($conditionItems['condition2'] == 'is after'){
                                            $query->whereDate('created_at','>',$conditionItems['value1']);
                                        }else if($conditionItems['condition2'] == 'is before'){
                                            $query->whereDate('created_at','<',$conditionItems['value1']);
                                        }else if($conditionItems['condition2'] == 'is on'){
                                            $query->whereDate('created_at','=',$conditionItems['value1']);
                                        }else if($conditionItems['condition2'] == 'is not on'){
                                            $query->whereDate('created_at','<>',$conditionItems['value1']);
                                        }else if($conditionItems['condition2'] == 'is on or before'){
                                            $query->whereDate('created_at','<=',$conditionItems['value1']);
                                        }
                                        else if($conditionItems['condition2'] == 'is on or after'){
                                            $query->whereDate('created_at','>=',$conditionItems['value1']);
                                        }else if($conditionItems['condition2'] == 'is between'){
                                            $query->whereBetween('created_at', [$conditionItems['value1'], $conditionItems['value2']]);
                                        }else if($conditionItems['condition2'] == 'is not between'){
                                            $query->whereNotBetween('created_at', [$conditionItems['value1'], $conditionItems['value2']]);
                                        }else{

                                        }
                                    });
                                }
                            }else{
                                $dateNow = Carbon::now();
                                $lineUserProfiles->whereDoesntHave('couponUsers', function ($query) use ($conditionItems,$dateNow) {
                                    if($conditionItems['condition2'] == 'is after'){
                                        $query->whereDate('created_at','>',$conditionItems['value1']);
                                    }else if($conditionItems['condition2'] == 'is before'){
                                        $query->whereDate('created_at','<',$conditionItems['value1']);
                                    }else if($conditionItems['condition2'] == 'is on'){
                                        $query->whereDate('created_at','=',$conditionItems['value1']);
                                    }else if($conditionItems['condition2'] == 'is not on'){
                                        $query->whereDate('created_at','<>',$conditionItems['value1']);
                                    }else if($conditionItems['condition2'] == 'is on or before'){
                                        $query->whereDate('created_at','<=',$conditionItems['value1']);
                                    }
                                    else if($conditionItems['condition2'] == 'is on or after'){
                                        $query->whereDate('created_at','>=',$conditionItems['value1']);
                                    }else if($conditionItems['condition2'] == 'is between'){
                                        $query->whereBetween('created_at', [$conditionItems['value1'], $conditionItems['value2']]);
                                    }else if($conditionItems['condition2'] == 'is not between'){
                                        $query->whereNotBetween('created_at', [$conditionItems['value1'], $conditionItems['value2']]);
                                    }else{

                                    }
                                });
                            }

                            if($conditionItems['value6'] == 'form any coupon'){
                                
                            }else{
                                $coupon = Coupon::where('name',$conditionItems['value6'])->first();
                                $lineUserProfiles->whereHas('couponUsers', function ($query) use ($conditionItems,$coupon) {
                                    $query->where('coupon_id',$coupon->id);
                                });
                            }
                            $conditionDatas->push($lineUserProfiles->pluck('id'));
                        }else if($conditionItems['title'] == 'Used'){
                            // $couponUsers = CouponUser::orderBy('created_at');
                            if($conditionItems['condition1'] == 'has used'){
                                if($conditionItems['condition2'] == 'at anytime'){
                                    $lineUserProfiles->whereHas('couponUsers', function ($query) use ($conditionItems) {
                                        $query->where('flag_status','reedeem');
                                    });
                                }else{
                                    $dateNow = Carbon::now();
                                    $lineUserProfiles->whereHas('couponUsers', function ($query) use ($conditionItems,$dateNow) {
                                        $query->where('flag_status','reedeem');
                                        if($conditionItems['condition2'] == 'is after'){
                                            $query->whereDate('created_at','>',$conditionItems['value1']);
                                        }else if($conditionItems['condition2'] == 'is before'){
                                            $query->whereDate('created_at','<',$conditionItems['value1']);
                                        }else if($conditionItems['condition2'] == 'is on'){
                                            $query->whereDate('created_at','=',$conditionItems['value1']);
                                        }else if($conditionItems['condition2'] == 'is not on'){
                                            $query->whereDate('created_at','<>',$conditionItems['value1']);
                                        }else if($conditionItems['condition2'] == 'is on or before'){
                                            $query->whereDate('created_at','<=',$conditionItems['value1']);
                                        }
                                        else if($conditionItems['condition2'] == 'is on or after'){
                                            $query->whereDate('created_at','>=',$conditionItems['value1']);
                                        }else if($conditionItems['condition2'] == 'is between'){
                                            $query->whereBetween('created_at', [$conditionItems['value1'], $conditionItems['value2']]);
                                        }else if($conditionItems['condition2'] == 'is not between'){
                                            $query->whereNotBetween('created_at', [$conditionItems['value1'], $conditionItems['value2']]);
                                        }else{

                                        }
                                    });
                                }
                            }else{
                                if($conditionItems['condition2'] == 'at anytime'){
                                    $lineUserProfiles->whereHas('couponUsers', function ($query) use ($conditionItems) {
                                        $query->where('flag_status',null);
                                    });
                                }else{
                                    $dateNow = Carbon::now();
                                    $lineUserProfiles->whereHas('couponUsers', function ($query) use ($conditionItems,$dateNow) {
                                        $query->where('flag_status',null);
                                        if($conditionItems['condition2'] == 'is after'){
                                            $query->whereDate('created_at','>',$conditionItems['value1']);
                                        }else if($conditionItems['condition2'] == 'is before'){
                                            $query->whereDate('created_at','<',$conditionItems['value1']);
                                        }else if($conditionItems['condition2'] == 'is on'){
                                            $query->whereDate('created_at','=',$conditionItems['value1']);
                                        }else if($conditionItems['condition2'] == 'is not on'){
                                            $query->whereDate('created_at','<>',$conditionItems['value1']);
                                        }else if($conditionItems['condition2'] == 'is on or before'){
                                            $query->whereDate('created_at','<=',$conditionItems['value1']);
                                        }
                                        else if($conditionItems['condition2'] == 'is on or after'){
                                            $query->whereDate('created_at','>=',$conditionItems['value1']);
                                        }else if($conditionItems['condition2'] == 'is between'){
                                            $query->whereBetween('created_at', [$conditionItems['value1'], $conditionItems['value2']]);
                                        }else if($conditionItems['condition2'] == 'is not between'){
                                            $query->whereNotBetween('created_at', [$conditionItems['value1'], $conditionItems['value2']]);
                                        }else{

                                        }
                                    });
                                }
                            }

                            if($conditionItems['value6'] == 'form any coupon'){
                                
                            }else{
                                $coupon = Coupon::where('name',$conditionItems['value6'])->first();
                                $lineUserProfiles->whereHas('couponUsers', function ($query) use ($conditionItems,$coupon) {
                                    $query->where('coupon_id',$coupon->id);
                                });
                            }
                            $conditionDatas->push($lineUserProfiles->pluck('id'));
                        }
                    }

                    if($conditions['subscriber_match'] == 'All'){
                        // return response()->json($conditionDatas);
                        // dd($conditionDatas);
                        $dataInterSect = $conditionDatas->collapse()->unique();
                        foreach ($conditionDatas as $key => $conditionData) {
                            $dataInterSect = $dataInterSect->intersect($conditionData);
                        }
                        $getDatas->push($dataInterSect);
                        // return response()->json($dataInterSect);
                    }else{
                        $conditionDatas = $conditionDatas->collapse()->unique();
                        $getDatas->push($conditionDatas);
                    }
                }else{
                    if($checkLineUserId){
                        $getDatas->push($lineUserProfiles->pluck('id'));
                    }
                    $getDatas->push($subscriberLines->pluck('line_user_id'));
                }
            }
        }else{
            if($checkLineUserId){
                $getDatas->push($lineUserProfiles->pluck('id'));
            }
            $getDatas->push($subscriberLines->pluck('line_user_id'));
        }

        if(isset($getDatas)){
            $getDatas = $getDatas->collapse()->unique();
            foreach ($getDatas as $keySubscriberLine => $dataValue) {
            	$dataStore = [];
            	$dataStore['segment_id'] = $segment->id;
            	$dataStore['line_user_id'] = $dataValue;
                SegmentQuery::create($dataStore);
            }
        }

        // foreach ($variable as $key => $value) {
        // 	SegmentQuery::create([

        // 	]);
        // }
    }

    public static function clearQueryData($segment)
    {
    	SegmentQuery::where('segment_id',$segment->id)->delete();
    }

    public static function countQueryData($segment)
    {
    	$segmentQueryCount = SegmentQuery::where('segment_id',$segment->id)->count();

    	return $segmentQueryCount;
    }

    public static function getQueryData($request,$segment)
    {
    	$lineUserIds = SegmentQuery::where('segment_id',$segment->id)->pluck('line_user_id');
    	$fields = Field::whereIn('subscriber_id',$request->subscriber_list)->get();
    	$datas = [];
    	foreach ($lineUserIds as $keySubscriberLine => $dataValue) {
            $subscriberLine = SubscriberLine::whereIn('subscriber_id',$request->subscriber_list)->where('line_user_id',$dataValue)->first();
            $lineUSerProfile = LineUserProfile::find($dataValue);
            $historyAddBlocks = HistoryAddBlock::where('line_user_id',$lineUSerProfile->id)->where('action','follow')->get();
            $datas[$keySubscriberLine]['Line User ID'] = $lineUSerProfile->id;
            $datas[$keySubscriberLine]['avatar'] = $lineUSerProfile->avatar;
            $datas[$keySubscriberLine]['Display Name'] = $lineUSerProfile->name;
            $datas[$keySubscriberLine]['Follow First Date'] = ($historyAddBlocks->first())? $historyAddBlocks->first()->created_at->format('Y-m-d H:i:s') : '';
            $datas[$keySubscriberLine]['Follow Updated Date'] = ($historyAddBlocks->last())? $historyAddBlocks->last()->created_at->format('Y-m-d H:i:s') : '';
            if($subscriberLine){
                $subscriberItems = $subscriberLine->subscriberItems;
                foreach ($fields as $keyField => $field) {
                    $datas[$keySubscriberLine][$field->name] = ($subscriberItems->where('field_id',$field->id)->first())? $subscriberItems->where('field_id',$field->id)->first()->value : null ;
                }
            }
        }

        return $datas;
    }
}
