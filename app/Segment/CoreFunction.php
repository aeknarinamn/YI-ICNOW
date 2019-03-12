<?php

namespace YellowProject\Segment;

use Illuminate\Database\Eloquent\Model;
use YellowProject\Segment\Segment;
use YellowProject\Segment\SegmentCondition;
use YellowProject\Segment\SegmentConditionItem;
use YellowProject\Segment\SegmentSubscriber;
use YellowProject\Subscriber;
use YellowProject\SubscriberLine;
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
    public static function queryData($request)
    {
        $getDatas = collect();
        $subscriberListArrays = $request['subscriber_list'];
        $checkLineUserId = in_array("LINE-userID",$subscriberListArrays);
        $subscriberIds = Subscriber::whereIn('category_id',$subscriberListArrays)->pluck('id')->toArray();
        $subscriberListArrays = $subscriberIds;
        $fieldUseArrays = [];
        // $subscriberLines =  \DB::table('dim_subscriber_line as sl')
        //     ->leftJoin('dim_line_user_table as lu', 'sl.line_user_id', '=', 'lu.id')
        //     ->where('lu.is_follow',1)
        //     ->whereIn('sl.subscriber_id',$subscriberListArrays);
        $subscriberLineDatas =  \DB::table('dim_subscriber_line as sl')
            ->leftJoin('dim_line_user_table as lu', 'sl.line_user_id', '=', 'lu.id')
            ->where('lu.is_follow',1)
            ->whereIn('sl.subscriber_id',$subscriberListArrays);
        // $subscriberLines = SubscriberLine::whereIn('subscriber_id',$subscriberListArrays)->whereHas('lineUserProfile', function ($query) {
        //     $query->where('is_follow',1);
        // });
        $lineUserProfileDatas = \DB::table('dim_line_user_table as lu')
            ->where('lu.is_follow',1);
        $subscriberLineEcommerces = \DB::table('dim_subscriber_line')
            ->leftJoin('dim_line_user_table', 'dim_subscriber_line.line_user_id', '=', 'dim_line_user_table.id')
            ->leftJoin('dim_ecommerce_customer', 'dim_subscriber_line.line_user_id', '=', 'dim_ecommerce_customer.line_user_id')
            ->leftJoin('dim_ecommerce_order', 'dim_ecommerce_customer.id', '=', 'dim_ecommerce_order.ecommerce_customer_id')
            ->leftJoin('fact_ecommerce_order_product', 'dim_ecommerce_order.id', '=', 'fact_ecommerce_order_product.order_id')
            ->leftJoin('dim_ecommerce_product', 'fact_ecommerce_order_product.product_id', '=', 'dim_ecommerce_product.id')
            ->leftJoin('fact_ecommerce_product_category', 'dim_ecommerce_product.id', '=', 'fact_ecommerce_product_category.ecommerce_product_id')
            ->select('dim_subscriber_line.subscriber_id'
              ,'dim_subscriber_line.line_user_id'
              ,'dim_line_user_table.mid'
              ,'dim_line_user_table.is_follow'
              ,'dim_ecommerce_customer.id as ecommerce_customer_id'
              ,'dim_ecommerce_order.id as ecommerce_order_id'
              ,'dim_ecommerce_order.order_id'
              ,'dim_ecommerce_order.order_date'
              ,'dim_ecommerce_order.order_status'
              ,'dim_ecommerce_order.total_due'
              ,'dim_ecommerce_product.id as ecommerce_product_id'
              ,'fact_ecommerce_product_category.ecommerce_category_id as ecommerce_category_id'
            )
            ->where('dim_line_user_table.is_follow',1)
            ->whereIn('dim_subscriber_line.subscriber_id',$subscriberListArrays);
            // ->whereNotNull('dim_ecommerce_customer.id')
            // ->get();
        $fields = Field::whereIn('subscriber_id',$subscriberListArrays)->get();
        // dd($fields);
        if(count($request['condition']) > 0){
            $subscriberItemDatas =  \DB::table('dim_subscriber_line as sl')
                ->leftJoin('dim_line_user_table as lu', 'sl.line_user_id', '=', 'lu.id')
                ->leftJoin('fact_subscribers as sb', 'sl.id', '=', 'sb.subscriber_line_id')
                ->where('lu.is_follow',1)
                ->whereIn('sl.subscriber_id',$subscriberListArrays);
            // $lineUserProfileHistoryDatas = \DB::select('select
            //     lu.id,
            //     lu.is_follow,
            //     hb.line_user_id,
            //     hb.action,
            //     hb.created_at
            //     from dim_line_user_table as lu
            //     left join fact_history_add_block as hb on lu.id = hb.line_user_id
            //     where lu.is_follow = 1
            // ');
            $lineUserProfileHistoryDatas = \DB::table('dim_line_user_table as lu')
                ->select('lu.id',
                    'lu.is_follow',
                    'hb.line_user_id',
                    'hb.action',
                    'hb.created_at',
                    'hb.updated_at'
                    // \DB::raw('min(hb.created_at) as min_created_at'),
                    // \DB::raw('max(hb.updated_at) as max_updated_at')
                )
                ->leftJoin('fact_history_add_block as hb', 'lu.id', '=', 'hb.line_user_id')
                ->where('lu.is_follow',1);

            $lineUserProfileCampaignDatas = \DB::table('dim_line_user_table as lu')
                ->leftJoin('fact_campaign_send_message as csm', 'lu.mid', '=', 'csm.mid')
                ->where('lu.is_follow',1);
            $lineUserProfileTrackingDatas = \DB::table('dim_line_user_table as lu')
                ->leftJoin('fact_recieve_tracking_bc as rtb', 'lu.id', '=', 'rtb.line_user_id')
                ->where('lu.is_follow',1);
            foreach ($request['condition'] as $keyCondition => $conditions) {
                $conditionDatas = collect();
                $subscriberMatch = $conditions['subscriber_match'];
                if(count($conditions['condition_items']) > 0){
                    // return response()->json($conditions['condition_items']);
                    foreach ($conditions['condition_items'] as $keyconditionItem => $conditionItems) {
                    // $subscriberLines = SubscriberLine::whereIn('subscriber_id',$subscriberListArrays)->whereHas('lineUserProfile', function ($query) {
                        //     $query->where('is_follow',1);
                        // });
                        $subscriberLines =  clone $subscriberItemDatas;
                        $lineUserProfiles = clone $lineUserProfileHistoryDatas;
                        if($conditionItems['title'] == 'Subscriber Data'){
                            $subscriberConditionDatas = collect();
                            if($conditionItems['remark1'] == 'line_id'){
                                if($conditionItems['value1'] != 0){
                                    $subscriberLines->where('sl.line_user_id',$conditionItems['value1']);
                                }
                            }else{
                                $subscriberLines->where('sb.field_id',$conditionItems['condition1']);
                                if($conditionItems['condition2'] == 'is'){
                                    $subscriberLines->where('sb.value',$conditionItems['value1']);
                                }else if($conditionItems['condition2'] == 'is not'){
                                    $subscriberLines->where('sb.value','<>',$conditionItems['value1']);
                                }else if($conditionItems['condition2'] == 'is empty'){
                                    $arrayInFields = $subscriberLines->pluck('line_user_id')->unique()->toArray();
                                    $subscriberLines =  clone $subscriberItemDatas;
                                    $subscriberLines->whereNotIn('sl.line_user_id',$arrayInFields);
                                }else if($conditionItems['condition2'] == 'is not empty'){
                                    $subscriberLines->whereNotNull('sb.value')->where('sb.value','<>','');
                                }else if($conditionItems['condition2'] == 'contains'){
                                    $subscriberLines->where('sb.value','like','%'.$conditionItems['value1'].'%');
                                }else if($conditionItems['condition2'] == 'does not contain' || $conditionItems['condition2'] == 'does not contains'){
                                    $subscriberLines->where('sb.value','not like','%'.$conditionItems['value1'].'%');
                                }else if($conditionItems['condition2'] == 'starts with'){
                                    $subscriberLines->where('sb.value','like',$conditionItems['value1'].'%');
                                }else if($conditionItems['condition2'] == 'ends with'){
                                    $subscriberLines->where('sb.value','like','%'.$conditionItems['value1']);
                                }else if($conditionItems['condition2'] == 'gather than'){
                                    $subscriberLines->where('sb.value','>',(int)$conditionItems['value1']);
                                }else if($conditionItems['condition2'] == 'less than'){
                                    $subscriberLines->where('sb.value','<',(int)$conditionItems['value1']);
                                }else if($conditionItems['condition2'] == 'gather than or equal'){
                                    $subscriberLines->where('sb.value','>=',(int)$conditionItems['value1']);
                                }else if($conditionItems['condition2'] == 'less than or equal'){
                                    $subscriberLines->where('sb.value','<=',(int)$conditionItems['value1']);
                                }else if($conditionItems['condition2'] == 'is between' && $conditionItems['remark1']  != 'date'){
                                    $subscriberLines->whereBetween('sb.value', [(float)$conditionItems['value1'], (float)$conditionItems['value2']]);
                                }else if($conditionItems['condition2'] == 'is after'){
                                    $subscriberLines->whereDate('sb.value', '>', $conditionItems['value1']);
                                }else if($conditionItems['condition2'] == 'is before'){
                                    $subscriberLines->whereDate('sb.value', '<', $conditionItems['value1']);
                                }else if($conditionItems['condition2'] == 'is on'){
                                    $subscriberLines->whereDate('sb.value', '=', $conditionItems['value1']);
                                }else if($conditionItems['condition2'] == 'is not on'){
                                    $subscriberLines->whereDate('sb.value', '<>', $conditionItems['value1']);
                                }else if($conditionItems['condition2'] == 'is on or before'){
                                    $subscriberLines->whereDate('sb.value', '<=', $conditionItems['value1']);
                                }else if($conditionItems['condition2'] == 'is on or after'){
                                    $subscriberLines->whereDate('sb.value', '>=', $conditionItems['value1']);
                                }else if($conditionItems['condition2'] == 'is between'){
                                    $subscriberLines->whereBetween('sb.value',[$conditionItems['value1'],$conditionItems['value2']]);
                                }else if($conditionItems['condition2'] == 'is not between'){
                                    $subscriberLines->whereNotBetween('sb.value',[$conditionItems['value1'],$conditionItems['value2']]);
                                }
                                else if($conditionItems['condition2'] == 'absolute date'){
                                    $dateNow = Carbon::now();
                                    if($conditionItems['value1'] == 'today'){
                                        $subscriberLines->whereDate('sb.value', '=', $dateNow->format('Y-m-d'));
                                    }else if($conditionItems['value1'] == 'yesterday'){
                                        $subscriberLines->whereDate('sb.value', '=', $dateNow->addDay(-1)->format('Y-m-d'));
                                    }else{
                                        $subscriberLines->whereDate('sb.value', '=', $dateNow->addDay(1)->format('Y-m-d'));
                                    }
                                }
                                else{

                                }
                                
                            }


                            if($checkLineUserId){
                                if($conditionItems['remark1'] == 'line_id'){
                                    if($conditionItems['value1'] != 0){
                                        $lineUserProfiles->where('lu.id',$conditionItems['value1']);
                                    }
                                }else{
                                    if($conditionItems['condition1'] == 'follow_first_date'){
                                        if($conditionItems['condition2'] == 'is empty'){
                                            $lineUserProfiles->whereNull('hb.created_at');
                                        }else if($conditionItems['condition2'] == 'is not empty'){
                                            $lineUserProfiles->whereNotNull('hb.id');
                                        }else if($conditionItems['condition2'] == 'is after'){
                                            $lineUserProfiles->whereDate('hb.created_at', '>', $conditionItems['value1']);
                                        }else if($conditionItems['condition2'] == 'is before'){
                                            $lineUserProfiles->whereDate('hb.created_at', '<', $conditionItems['value1']);
                                        }else if($conditionItems['condition2'] == 'is on'){
                                            $lineUserProfiles->whereDate('hb.created_at', '=', $conditionItems['value1']);
                                        }else if($conditionItems['condition2'] == 'is not on'){
                                            $lineUserProfiles->whereDate('hb.created_at', '<>', $conditionItems['value1']);
                                        }else if($conditionItems['condition2'] == 'is on or before'){
                                            $lineUserProfiles->whereDate('hb.created_at', '<=', $conditionItems['value1']);
                                        }else if($conditionItems['condition2'] == 'is on or after'){
                                            $lineUserProfiles->whereDate('hb.created_at', '>=', $conditionItems['value1']);
                                        }else if($conditionItems['condition2'] == 'is between'){
                                            $lineUserProfiles->whereBetween('hb.created_at',[$conditionItems['value1'],$conditionItems['value2']]);
                                        }else if($conditionItems['condition2'] == 'is not between'){
                                            $lineUserProfiles->whereNotBetween('hb.created_at',[$conditionItems['value1'],$conditionItems['value2']]);
                                        }
                                        $lineUserProfiles->groupBy('lu.id')
                                            ->orderBy(\DB::raw('min(hb.created_at)'));
                                    }else if($conditionItems['condition1'] == 'follow_update_date'){
                                        if($conditionItems['condition2'] == 'is empty'){
                                            $conditionItems['is_empty'] = 0;
                                            $lineUserProfiles->whereNull('hb.updated_at');
                                        }else if($conditionItems['condition2'] == 'is not empty'){
                                            $conditionItems['is_empty'] = 0;
                                            $lineUserProfiles->whereNotNull('hb.id');
                                        }else if($conditionItems['condition2'] == 'is after'){
                                            $lineUserProfiles->whereDate('hb.updated_at', '>', $conditionItems['value1']);
                                        }else if($conditionItems['condition2'] == 'is before'){
                                            $lineUserProfiles->whereDate('hb.updated_at', '<', $conditionItems['value1']);
                                        }else if($conditionItems['condition2'] == 'is on'){
                                            $lineUserProfiles->whereDate('hb.updated_at', '=', $conditionItems['value1']);
                                        }else if($conditionItems['condition2'] == 'is not on'){
                                            $lineUserProfiles->whereDate('hb.updated_at', '<>', $conditionItems['value1']);
                                        }else if($conditionItems['condition2'] == 'is on or before'){
                                            $lineUserProfiles->whereDate('hb.updated_at', '<=', $conditionItems['value1']);
                                        }else if($conditionItems['condition2'] == 'is on or after'){
                                            $lineUserProfiles->whereDate('hb.updated_at', '>=', $conditionItems['value1']);
                                        }else if($conditionItems['condition2'] == 'is between'){
                                            $lineUserProfiles->whereBetween('hb.updated_at',[$conditionItems['value1'],$conditionItems['value2']]);
                                        }else if($conditionItems['condition2'] == 'is not between'){
                                            $lineUserProfiles->whereNotBetween('hb.updated_at',[$conditionItems['value1'],$conditionItems['value2']]);
                                        }
                                        $lineUserProfiles->groupBy('lu.id')
                                            ->orderBy(\DB::raw('max(hb.updated_at)'));
                                    }
                                }

                                $subscriberConditionDatas->push($lineUserProfiles->pluck('id'));
                            }

                            $subscriberConditionDatas->push($subscriberLines->pluck('line_user_id'));
                            // $conditionDatas->push($subscriberLines->pluck('line_user_id'));
                            if($conditionItems['is_empty'] == 1 && $conditionItems['condition1'] != 'follow_first_date' && $conditionItems['condition1'] != 'follow_update_date'){
                                $subscriberLines =  clone $subscriberItemDatas;
                                $subscriberLines->whereNull('sb.value')->orWhere('sb.value','');
                                $subscriberConditionDatas->push($subscriberLines->pluck('line_user_id'));
                                // $conditionDatas->push($subscriberLines->pluck('line_user_id'));
                                // $conditionDatas = $conditionDatas->collapse()->unique();
                                // return response()->json($conditionDatas);
                            }
                            $conditionDatas->push($subscriberConditionDatas->collapse()->unique());
                        }else if($conditionItems['title'] == 'sent activity'){
                            $lineUserProfileCampaigns = clone $lineUserProfileCampaignDatas;
                            
                            $lineUserProfileCampaigns = $lineUserProfileCampaigns->whereNotNull('csm.id');
                            if($conditionItems['condition2'] == 'at anytime'){

                            }else{
                                $dateNow = Carbon::now();
                                if($conditionItems['condition2'] == 'is after'){
                                    $lineUserProfileCampaigns->whereDate('csm.created_at','>',$conditionItems['value1']);
                                }else if($conditionItems['condition2'] == 'is before'){
                                    $lineUserProfileCampaigns->whereDate('csm.created_at','<',$conditionItems['value1']);
                                }else if($conditionItems['condition2'] == 'is on'){
                                    $lineUserProfileCampaigns->whereDate('csm.created_at','=',$conditionItems['value1']);
                                }else if($conditionItems['condition2'] == 'is not on'){
                                    $lineUserProfileCampaigns->whereDate('csm.created_at','<>',$conditionItems['value1']);
                                }else if($conditionItems['condition2'] == 'is on or before'){
                                    $lineUserProfileCampaigns->whereDate('csm.created_at','<=',$conditionItems['value1']);
                                }
                                else if($conditionItems['condition2'] == 'is on or after'){
                                    $lineUserProfileCampaigns->whereDate('csm.created_at','>=',$conditionItems['value1']);
                                }else if($conditionItems['condition2'] == 'is between'){
                                    $lineUserProfileCampaigns->whereBetween('csm.created_at', [$conditionItems['value1'], $conditionItems['value2']]);
                                }else if($conditionItems['condition2'] == 'is not between'){
                                    $lineUserProfileCampaigns->whereNotBetween('csm.created_at', [$conditionItems['value1'], $conditionItems['value2']]);
                                }else if($conditionItems['condition2'] == 'relative date'){
                                    if($conditionItems['value1'] == 'first day'){
                                        if($conditionItems['value2'] == 'of this month'){
                                            $dateCondition = $dateNow->format('F Y');
                                            $newDateCondition = new Carbon('first day of '.$dateCondition);
                                            $lineUserProfileCampaigns->where('csm.created_at','=',$newDateCondition);
                                            // dd($newDateCondition);
                                        }else if($conditionItems['value2'] == 'of this year'){
                                            $dateCondition = $dateNow->format('Y');
                                            $newDateCondition = new Carbon('first day of January '.$dateCondition);
                                            $lineUserProfileCampaigns->where('csm.created_at','=',$newDateCondition);
                                        }
                                    }else if($conditionItems['value1'] == 'last day'){
                                        if($conditionItems['value2'] == 'of this month'){
                                            $dateCondition = $dateNow->format('F Y');
                                            $newDateCondition = new Carbon('last day of '.$dateCondition);
                                            $lineUserProfileCampaigns->where('csm.created_at','=',$newDateCondition);
                                            // dd($newDateCondition);
                                        }else if($conditionItems['value2'] == 'of this year'){
                                            $dateCondition = $dateNow->format('Y');
                                            $newDateCondition = new Carbon('last day of December '.$dateCondition);
                                            $lineUserProfileCampaigns->where('csm.created_at','=',$newDateCondition);
                                        }
                                    }else if($conditionItems['value1'] == 'sunday'){
                                        $weekOfConditionDay = Carbon::SUNDAY;
                                        $dateCondition = $dateNow->addDays($weekOfConditionDay);
                                        $lineUserProfileCampaigns->where('csm.created_at','=',$dateCondition);
                                    }else if($conditionItems['value1'] == 'monday'){
                                        $weekOfConditionDay = Carbon::MONDAY;
                                        $dateCondition = $dateNow->addDays($weekOfConditionDay);
                                        $lineUserProfileCampaigns->where('csm.created_at','=',$dateCondition);
                                    }else if($conditionItems['value1'] == 'tuesday'){
                                        $weekOfConditionDay = Carbon::TUESDAY;
                                        $dateCondition = $dateNow->addDays($weekOfConditionDay);
                                        $lineUserProfileCampaigns->where('csm.created_at','=',$dateCondition);
                                    }else if($conditionItems['value1'] == 'wednesday'){
                                        $weekOfConditionDay = Carbon::WEDNESDAY;
                                        $dateCondition = $dateNow->addDays($weekOfConditionDay);
                                        $lineUserProfileCampaigns->where('csm.created_at','=',$dateCondition);
                                    }else if($conditionItems['value1'] == 'thursday'){
                                        $weekOfConditionDay = Carbon::THURSDAY;
                                        $dateCondition = $dateNow->addDays($weekOfConditionDay);
                                        $lineUserProfileCampaigns->where('csm.created_at','=',$dateCondition);
                                    }else if($conditionItems['value1'] == 'friday'){
                                        $weekOfConditionDay = Carbon::FRIDAY;
                                        $dateCondition = $dateNow->addDays($weekOfConditionDay);
                                        $lineUserProfileCampaigns->where('csm.created_at','=',$dateCondition);
                                    }else if($conditionItems['value1'] == 'saturday'){
                                        $weekOfConditionDay = Carbon::SATURDAY;
                                        $dateCondition = $dateNow->addDays($weekOfConditionDay);
                                        $lineUserProfileCampaigns->where('csm.created_at','=',$dateCondition);
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
                                            $lineUserProfileCampaigns->where('csm.created_at','>=',$dateCondition->format('Y-m-d'));
                                        }else{
                                            $lineUserProfileCampaigns->where('csm.created_at','<=',$dateCondition->format('Y-m-d'));
                                        }
                                    }
                                }else if($conditionItems['condition2'] == 'absolute date'){
                                    if($conditionItems['value1'] == 'today'){
                                        $dateCondition = $dateNow;
                                        $lineUserProfileCampaigns->where('csm.created_at','>=',$dateCondition->format('Y-m-d'));
                                    }else if($conditionItems['value1'] == 'yesterday'){
                                        $dateCondition = $dateNow->addDays(-1);
                                        $lineUserProfileCampaigns->where('csm.created_at','>=',$dateCondition->format('Y-m-d'));
                                    }else if($conditionItems['value1'] == 'tomorrow'){
                                        $dateCondition = $dateNow->addDays(1);
                                        $lineUserProfileCampaigns->where('csm.created_at','>=',$dateCondition->format('Y-m-d'));
                                    }
                                }else{

                                }
                                
                            } 

                            if($conditionItems['condition1'] == 'was not sent'){
                                $dataArrays = $lineUserProfileCampaigns->pluck('lu.id')->unique()->toArray();
                                $lineUserProfileCampaigns = clone $lineUserProfileCampaignDatas;
                                $lineUserProfileCampaigns = $lineUserProfileCampaigns->whereNotIn('lu.id',$dataArrays);
                            }
                            
                            if($conditionItems['condition3'] == 'is'){
                                $lineUserProfileCampaigns->where('csm.campaign_id',$conditionItems['value5']);
                            }else if($conditionItems['condition3'] == 'is not' && $conditionItems['condition1'] != 'was not sent'){
                                $lineUserProfileCampaigns->where('csm.campaign_id','<>',$conditionItems['value5']);
                            }

                            $conditionDatas->push($lineUserProfileCampaigns->pluck('lu.id'));
                            // dd($datas);
                        }else if($conditionItems['title'] == 'BC Tracking'){
                            $lineUserProfileTrackings = clone $lineUserProfileTrackingDatas;

                            if($conditionItems['value1'] != 'All source'){
                                $lineUserProfileTrackings->where('rtb.tracking_source',$conditionItems['value1']);
                            }
                            if($conditionItems['value2'] != 'All Campaign'){
                                $lineUserProfileTrackings->where('rtb.tracking_campaign',$conditionItems['value2']);
                            }
                            if($conditionItems['value3'] != 'All Ref'){
                                $lineUserProfileTrackings->where('rtb.tracking_ref',$conditionItems['value3']);
                            }

                            if($conditionItems['value6'] != "" && $conditionItems['value6'] != 0){
                                $lineUserProfileTrackings->groupBy('lu.id')
                                    ->havingRaw('COUNT(lu.id) >= '.$conditionItems['value6']);
                            }
                            $conditionDatas->push($lineUserProfileTrackings->pluck('lu.id'));
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
                        }else if($conditionItems['title'] == 'eCommerce'){
                            if($conditionItems['condition1'] == 'has purchased'){
                                $subscriberLineEcommerces->whereNotNull('dim_ecommerce_customer.id');
                                if($conditionItems['condition2'] == 'at anytime'){

                                }else if($conditionItems['condition2'] == 'is after'){
                                    $subscriberLineEcommerces->where('dim_ecommerce_order.order_date','>',$conditionItems['value1']);
                                }else if($conditionItems['condition2'] == 'is before'){
                                    $subscriberLineEcommerces->where('dim_ecommerce_order.order_date','<',$conditionItems['value1']);
                                }else if($conditionItems['condition2'] == 'is on'){
                                    $subscriberLineEcommerces->where('dim_ecommerce_order.order_date','=',$conditionItems['value1']);
                                }else if($conditionItems['condition2'] == 'is not on'){
                                    $subscriberLineEcommerces->where('dim_ecommerce_order.order_date','<>',$conditionItems['value1']);
                                }else if($conditionItems['condition2'] == 'is on or before'){
                                    $subscriberLineEcommerces->where('dim_ecommerce_order.order_date','<=',$conditionItems['value1']);
                                }else if($conditionItems['condition2'] == 'is on or after'){
                                    $subscriberLineEcommerces->where('dim_ecommerce_order.order_date','>=',$conditionItems['value1']);
                                }else if($conditionItems['condition2'] == 'is between'){
                                    $subscriberLineEcommerces->where('dim_ecommerce_order.order_date','>',$conditionItems['value1'])->where('dim_ecommerce_order.order_date','<',$conditionItems['value2']);
                                }else if($conditionItems['condition2'] == 'is not between'){
                                    $subscriberLineEcommerces->where('dim_ecommerce_order.order_date','<',$conditionItems['value1'])->where('dim_ecommerce_order.order_date','>',$conditionItems['value2']);
                                }

                                if($conditionItems['condition3'] == 'from any category'){

                                }else if($conditionItems['condition3'] == 'is'){
                                    $subscriberLineEcommerces->where('fact_ecommerce_product_category.id','=',$conditionItems['value6']);
                                }else if($conditionItems['condition3'] == 'is not'){
                                    $subscriberLineEcommerces->where('fact_ecommerce_product_category.id','<>',$conditionItems['value6']);
                                }

                                if($conditionItems['condition4'] == 'any product'){

                                }else if($conditionItems['condition4'] == 'is'){
                                    $subscriberLineEcommerces->where('dim_ecommerce_product.id','=',$conditionItems['value7']);
                                }else if($conditionItems['condition4'] == 'is not'){
                                    $subscriberLineEcommerces->where('dim_ecommerce_product.id','<>',$conditionItems['value7']);
                                }

                                if($conditionItems['condition5'] == 'any total'){

                                }else if($conditionItems['condition5'] == 'is'){
                                    $subscriberLineEcommerces->where('dim_ecommerce_order.total_due','=',$conditionItems['value8']);
                                }else if($conditionItems['condition5'] == 'is not'){
                                    $subscriberLineEcommerces->where('dim_ecommerce_order.total_due','<>',$conditionItems['value8']);
                                }else if($conditionItems['condition5'] == 'is empty'){

                                }else if($conditionItems['condition5'] == 'is not empty'){

                                }else if($conditionItems['condition5'] == 'contains'){
                                    $subscriberLineEcommerces->where('dim_ecommerce_order.total_due','like','%'.$conditionItems['value8'].'%');
                                }else if($conditionItems['condition5'] == 'does not contains'){
                                    $subscriberLineEcommerces->where('dim_ecommerce_order.total_due','not like','%'.$conditionItems['value8'].'%');
                                }else if($conditionItems['condition5'] == 'starts with'){
                                    $subscriberLineEcommerces->where('dim_ecommerce_order.total_due','like','%'.$conditionItems['value8']);
                                }else if($conditionItems['condition5'] == 'ends with'){
                                    $subscriberLineEcommerces->where('dim_ecommerce_order.total_due','like',$conditionItems['value8'].'%');
                                }else if($conditionItems['condition5'] == 'gather than'){
                                    $subscriberLineEcommerces->where('dim_ecommerce_order.total_due','>',$conditionItems['value8']);
                                }else if($conditionItems['condition5'] == 'less than'){
                                    $subscriberLineEcommerces->where('dim_ecommerce_order.total_due','<',$conditionItems['value8']);
                                }else if($conditionItems['condition5'] == 'gather than or equal'){
                                    $subscriberLineEcommerces->where('dim_ecommerce_order.total_due','>=',$conditionItems['value8']);
                                }else if($conditionItems['condition5'] == 'less than or equal'){
                                    $subscriberLineEcommerces->where('dim_ecommerce_order.total_due','<=',$conditionItems['value8']);
                                }else if($conditionItems['condition5'] == 'is between'){
                                    $subscriberLineEcommerces->where('dim_ecommerce_order.total_due','>=',$conditionItems['value8'])->where('dim_ecommerce_order.total_due','<=',$conditionItems['value9']);
                                }
                            }else if($conditionItems['condition1'] == 'has not purchased'){
                                $subscriberLineEcommerces->whereNull('dim_ecommerce_customer.id');
                            }else if($conditionItems['condition1'] == 'Order Status'){
                                $subscriberLineEcommerces->whereNotNull('dim_ecommerce_customer.id');
                                if($conditionItems['condition6'] == 'any status'){

                                }else if($conditionItems['condition6'] == 'is'){
                                    $subscriberLineEcommerces->where('dim_ecommerce_order.order_status','=',$conditionItems['value10']);
                                }else if($conditionItems['condition6'] == 'is not'){
                                    $subscriberLineEcommerces->where('dim_ecommerce_order.order_status','<>',$conditionItems['value10']);
                                }
                                
                            }
                            $conditionDatas->push($subscriberLineEcommerces->pluck('line_user_id'));
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
                        $getDatas->push($lineUserProfileDatas->pluck('id'));
                    }
                    $getDatas->push($subscriberLineDatas->pluck('line_user_id'));
                }
            }
        }else{
            if($checkLineUserId){
                $getDatas->push($lineUserProfileDatas->pluck('id'));
            }
            $getDatas->push($subscriberLineDatas->pluck('line_user_id'));
        }
        // dd($getDatas->collapse());

        // $getDatas = $getDatas->collapse()->unique()->forPage($offset, $limit);
        $getDatas = $getDatas->collapse()->unique();

        return $getDatas;
    }

    public static function getFieldQuery($request)
    {
        $subscriberListArrays = $request['subscriber_list'];
        $checkLineUserId = in_array("LINE-userID",$subscriberListArrays);
        $fieldUseArrays = [];
        $fieldUseArrays['susbcriber_data_field'] = [];
        if(count($request['condition']) > 0){
            foreach ($request['condition'] as $keyCondition => $conditions) {
                $subscriberMatch = $conditions['subscriber_match'];
                if(count($conditions['condition_items']) > 0){
                    foreach ($conditions['condition_items'] as $keyconditionItem => $conditionItems) {
                        if($conditionItems['title'] == 'Subscriber Data'){
                            $subscriberConditionDatas = collect();
                            if($conditionItems['remark1'] == 'line_id'){
                                if($conditionItems['value1'] != 0){
                                    
                                }
                            }else{
                                $fieldUseArrays['susbcriber_data_field'][] = $conditionItems['condition1'];
                            }

                            if($checkLineUserId){
                                if($conditionItems['remark1'] == 'line_id'){
                                    if($conditionItems['value1'] != 0){
                                    }
                                }else{
                                    if($conditionItems['condition1'] == 'follow_first_date'){
                                        
                                    }else if($conditionItems['condition1'] == 'follow_update_date'){
                                        
                                    }
                                }
                            }
                        }else if($conditionItems['title'] == 'sent activity'){
                            if($conditionItems['condition1'] == 'was sent'){
                                if($conditionItems['condition2'] == 'at anytime'){
                                }else{
                                    
                                }
                            }else if($conditionItems['condition1'] == 'was not sent'){
                               
                            }

                            if($conditionItems['condition3'] == 'is'){
                                
                            }else if($conditionItems['condition3'] == 'is not'){
                                
                            }
                            // $conditionDatas->push($lineUserProfiles->pluck('id'));
                            // dd($datas);
                        }else if($conditionItems['title'] == 'BC Tracking'){
                            
                            // dd($datas);
                        }else if($conditionItems['title'] == 'Redeem'){
                            // $couponUsers = CouponUser::orderBy('created_at');
                            if($conditionItems['condition1'] == 'was redeem'){
                                if($conditionItems['condition2'] == 'at anytime'){
                                }else{
                                }
                            }else{
                                
                            }

                            if($conditionItems['value6'] == 'form any coupon'){

                            }else{
                                
                            }
                        }else if($conditionItems['title'] == 'Used'){
                            // $couponUsers = CouponUser::orderBy('created_at');
                            if($conditionItems['condition1'] == 'has used'){
                                if($conditionItems['condition2'] == 'at anytime'){
                                    
                                }else{
                                   
                                }
                            }else{
                                if($conditionItems['condition2'] == 'at anytime'){
                                    
                                }else{
                                    
                                }
                            }

                            if($conditionItems['value6'] == 'form any coupon'){

                            }else{
                                
                            }
                            // $conditionDatas->push($lineUserProfiles->pluck('id'));
                        }else if($conditionItems['title'] == 'eCommerce'){
                            if($conditionItems['condition1'] == 'has purchased'){
                                
                            }else if($conditionItems['condition1'] == 'has not purchased'){
                            }else if($conditionItems['condition1'] == 'Order Status'){
                                
                            }
                        }
                    }

                    if($conditions['subscriber_match'] == 'All'){
                        
                    }else{
                        
                    }
                }else{
                    if($checkLineUserId){
                    }
                }
            }
        }else{
            if($checkLineUserId){
            }
        }

        return $fieldUseArrays;
    }

    public static function setSegmentProcess()
    {
        $segments = Segment::all();
        foreach ($segments as $key => $segment) {
            $segment->update([
                'is_process' => 1
            ]);
        }
    }

    public static function segmentCountData()
    {
        $segments = Segment::where('is_process',1)->get();
        foreach ($segments as $key => $segment) {
            $segment->update([
                'is_process' => 0
            ]);
            $segmentFormat = Segment::segmentSetToFormat($segment->id);
            $datas = self::queryData($segmentFormat);

            $segment->update([
                'count_data' => $datas->count()
            ]);
        }
    }

}
