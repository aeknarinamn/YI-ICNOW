<?php

namespace YellowProject\Segment;

use Illuminate\Database\Eloquent\Model;
use YellowProject\Segment\SegmentFolder;
use YellowProject\Segment\SegmentCondition;
use YellowProject\Segment\SegmentSubscriber;
use YellowProject\Subscriber;
use YellowProject\SubscriberLine;
use YellowProject\Campaign;
use YellowProject\LineUserProfile;
use YellowProject\TrackingRecieveBc;
use YellowProject\Coupon;
use YellowProject\Segment\CoreFunction;
use Carbon\Carbon;

class Segment extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dim_segment';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'folder_id',
        'count_data',
    ];

    public function campaigns()
    {
        return $this->hasMany(Campaign::class,'segment_id','id');
    }

    public function segmentConditions()
    {
        return $this->hasMany(SegmentCondition::class,'segment_id','id');
    }

    public function folder()
    {
        return $this->belongsTo(SegmentFolder::class, 'folder_id', 'id');
    }

    public function segmentSubscribers()
    {
        return $this->hasMany(SegmentSubscriber::class,'segment_id','id');
    }

    public static function segmentSetToFormat($segmentId)
    {
        $datas = [];
        $segment = Segment::find($segmentId);
        $segmentSubscriberIds = $segment->segmentSubscribers->pluck('subscriber_id')->toArray();
        $segmentConditions = $segment->segmentConditions;
        $datas['condition'] = $segmentConditions->toArray();
        $datas['subscriber_list'] = $segmentSubscriberIds;
        foreach ($segmentConditions as $key => $segmentCondition) {
            $segmentConditionItems = $segmentCondition->segmentConditionItems;
            $datas['condition'][$key]['condition_items'] = $segmentConditionItems->toArray();
        }

        return $datas;
        // $getDatas = CoreFunction::queryData($datas);
        // dd($getDatas);
    }

    public static function getSegmentData($segmentId)
    {
        $getDatas = collect();
        $segment = Segment::find($segmentId);
        $segmentConditions = $segment->segmentConditions;
        $segmentSubscriberIds = $segment->segmentSubscribers->pluck('subscriber_id')->toArray();
        $checkLineUserId = in_array("LINE-userID",$segmentSubscriberIds);
        $segmentSubscriberIds = Subscriber::whereIn('category_id',$segmentSubscriberIds)->pluck('id')->toArray();
        $subscriberLines = SubscriberLine::whereIn('subscriber_id',$segmentSubscriberIds)->whereHas('lineUserProfile', function ($query) {
                $query->where('is_follow',1);
            });
        $lineUserProfiles = LineUserProfile::orderBy('created_at')->where('is_follow',1);
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
            ->whereIn('dim_subscriber_line.subscriber_id',$segmentSubscriberIds);
        // dd($lineUserProfiles);
        // $subscribers = Subscriber::whereIn('id',$segmentSubscriberIds)->get();
        if($segmentConditions->count() > 0){
            foreach ($segmentConditions as $key => $segmentCondition) {
                $subscriberMatch = $segmentCondition->subscriber_match;
                $conditionDatas = collect();
                $segmentConditionItems = $segmentCondition->segmentConditionItems;
                if($segmentConditionItems->count() > 0){
                    foreach ($segmentConditionItems as $key => $segmentConditionItem) {
                        // dd($segmentConditionItem);
                        $subscriberLines = SubscriberLine::whereIn('subscriber_id',$segmentSubscriberIds)->whereHas('lineUserProfile', function ($query) {
                            $query->where('is_follow',1);
                        });
                        $lineUserProfiles = LineUserProfile::orderBy('created_at')->where('is_follow',1);
                        if($segmentConditionItem->title == 'Subscriber Data'){
                            $subscriberConditionDatas = collect();
                            if($segmentConditionItem->remark1 == 'line_id'){
                                if($segmentConditionItem->value1 != 0){
                                    $subscriberLines->where('line_user_id',$segmentConditionItem->value1);
                                }
                            }else{
                                $subscriberLines->whereHas('subscriberItems', function ($query) use ($segmentConditionItem) {
                                    $query->where('field_id',$segmentConditionItem->condition1);
                                    if($segmentConditionItem->condition2 == 'is'){
                                        $query->where('value',$segmentConditionItem->value1);
                                    }else if($segmentConditionItem->condition2 == 'is not'){
                                        $query->where('value','<>',$segmentConditionItem->value1);
                                    }else if($segmentConditionItem->condition2 == 'is empty'){
                                        $query->where('value','');
                                    }else if($segmentConditionItem->condition2 == 'is not empty'){
                                        $query->where('value','<>','');
                                    }else if($segmentConditionItem->condition2 == 'contains'){
                                        $query->where('value','like','%'.$segmentConditionItem->value1.'%');
                                    }else if($segmentConditionItem->condition2 == 'does not contain' || $segmentConditionItem->condition2 == 'does not contains'){
                                        $query->where('value','not like','%'.$segmentConditionItem->value1.'%');
                                    }else if($segmentConditionItem->condition2 == 'starts with'){
                                        $query->where('value','like',$segmentConditionItem->value1.'%');
                                    }else if($segmentConditionItem->condition2 == 'ends with'){
                                        $query->where('value','like','%'.$segmentConditionItem->value1);
                                    }else if($segmentConditionItem->condition2 == 'gather than'){
                                        $query->where('value','>',(int)$segmentConditionItem->value1);
                                    }else if($segmentConditionItem->condition2 == 'less than'){
                                        $query->where('value','<',(int)$segmentConditionItem->value1);
                                    }else if($segmentConditionItem->condition2 == 'gather than or equal'){
                                        $query->where('value','>=',(int)$segmentConditionItem->value1);
                                    }else if($segmentConditionItem->condition2 == 'less than or equal'){
                                        $query->where('value','<=',(int)$segmentConditionItem->value1);
                                    }else if($segmentConditionItem->condition2 == 'is between' && $segmentConditionItem->condition2 != 'date'){
                                        $query->whereBetween('value', [(float)$segmentConditionItem->value1, (float)$segmentConditionItem->value2]);
                                    }else if($segmentConditionItem->condition2 == 'is after'){
                                        $query->whereDate('value', '>', $segmentConditionItem->value1);
                                    }else if($segmentConditionItem->condition2 == 'is before'){
                                        $query->whereDate('value', '<', $segmentConditionItem->value1);
                                    }else if($segmentConditionItem->condition2 == 'is on'){
                                        $query->whereDate('value', '=', $segmentConditionItem->value1);
                                    }else if($segmentConditionItem->condition2 == 'is not on'){
                                        $query->whereDate('value', '<>', $segmentConditionItem->value1);
                                    }else if($segmentConditionItem->condition2 == 'is on or before'){
                                        $query->whereDate('value', '<=', $segmentConditionItem->value1);
                                    }else if($segmentConditionItem->condition2 == 'is on or after'){
                                        $query->whereDate('value', '>=', $segmentConditionItem->value1);
                                    }else if($segmentConditionItem->condition2 == 'is between'){
                                        $query->whereBetween('value',[$segmentConditionItem->value1,$segmentConditionItem->value2]);
                                    }else if($segmentConditionItem->condition2 == 'is not between'){
                                        $query->whereNotBetween('value',[$segmentConditionItem->value1,$segmentConditionItem->value2]);
                                    }else if($segmentConditionItem->condition2 == 'absolute date'){
                                        $dateNow = Carbon\Carbon::now();
                                        if($segmentConditionItem->value1 == 'today'){
                                            $query->whereDate('value', '=', $dateNow->format('Y-m-d'));
                                        }else if($segmentConditionItem->value1 == 'yesterday'){
                                            $query->whereDate('value', '=', $dateNow->addDay(-1)->format('Y-m-d'));
                                        }else{
                                            $query->whereDate('value', '=', $dateNow->addDay(1)->format('Y-m-d'));
                                        }
                                    }else{

                                    }
                                });
                            }

                            if($checkLineUserId){
                                if($segmentConditionItem->remark1 == 'line_id'){
                                    if($segmentConditionItem->value1 != 0){
                                        $lineUserProfiles->where('id',$segmentConditionItem->value1);
                                    }
                                }else{
                                    if($segmentConditionItem->condition1 == 'follow_first_date'){
                                        $lineUserProfiles->whereHas('historyAddBlocks', function ($query) use ($segmentConditionItem) {
                                            $query->select('*', \DB::raw('min(created_at) as latest_date'))
                                            ->groupBy('line_user_id')
                                            ->orderBy('latest_date')
                                            ->where('action','follow');
                                            if($segmentConditionItem->condition2 == 'is empty'){
                                                $query->where('created_at','');
                                            }else if($segmentConditionItem->condition2 == 'is not empty'){
                                                $query->where('created_at','<>','');
                                            }else if($segmentConditionItem->condition2 == 'is after'){
                                                $query->whereDate('created_at', '>', $segmentConditionItem->value1);
                                            }else if($segmentConditionItem->condition2 == 'is before'){
                                                $query->whereDate('created_at', '<', $segmentConditionItem->value1);
                                            }else if($segmentConditionItem->condition2 == 'is on'){
                                                $query->whereDate('created_at', '=', $segmentConditionItem->value1);
                                            }else if($segmentConditionItem->condition2 == 'is not on'){
                                                $query->whereDate('created_at', '<>', $segmentConditionItem->value1);
                                            }else if($segmentConditionItem->condition2 == 'is on or before'){
                                                $query->whereDate('created_at', '<=', $segmentConditionItem->value1);
                                            }else if($segmentConditionItem->condition2 == 'is on or after'){
                                                $query->whereDate('created_at', '>=', $segmentConditionItem->value1);
                                            }else if($segmentConditionItem->condition2 == 'is between'){
                                                $query->whereBetween('created_at',[$segmentConditionItem->value1,$segmentConditionItem->value2]);
                                            }else if($segmentConditionItem->condition2 == 'is not between'){
                                                $query->whereNotBetween('created_at',[$segmentConditionItem->value1,$segmentConditionItem->value2]);
                                            }
                                        });
                                    }else if($segmentConditionItem->condition1 == 'follow_update_date'){
                                        $lineUserProfiles->whereHas('historyAddBlocks', function ($query) use ($segmentConditionItem) {
                                            $query->select('*', \DB::raw('max(updated_at) as latest_date'))
                                            ->groupBy('line_user_id')
                                            ->orderBy('latest_date')
                                            ->where('action','follow');
                                            if($segmentConditionItem->condition2 == 'is empty'){
                                                $query->where('updated_at','');
                                            }else if($segmentConditionItem->condition2 == 'is not empty'){
                                                $query->where('updated_at','<>','');
                                            }else if($segmentConditionItem->condition2 == 'is after'){
                                                $query->whereDate('updated_at', '>', $segmentConditionItem->value1);
                                            }else if($segmentConditionItem->condition2 == 'is before'){
                                                $query->whereDate('updated_at', '<', $segmentConditionItem->value1);
                                            }else if($segmentConditionItem->condition2 == 'is on'){
                                                $query->whereDate('updated_at', '=', $segmentConditionItem->value1);
                                            }else if($segmentConditionItem->condition2 == 'is not on'){
                                                $query->whereDate('updated_at', '<>', $segmentConditionItem->value1);
                                            }else if($segmentConditionItem->condition2 == 'is on or before'){
                                                $query->whereDate('updated_at', '<=', $segmentConditionItem->value1);
                                            }else if($segmentConditionItem->condition2 == 'is on or after'){
                                                $query->whereDate('updated_at', '>=', $segmentConditionItem->value1);
                                            }else if($segmentConditionItem->condition2 == 'is between'){
                                                $query->whereBetween('updated_at',[$segmentConditionItem->value1,$segmentConditionItem->value2]);
                                            }else if($segmentConditionItem->condition2 == 'is not between'){
                                                $query->whereNotBetween('updated_at',[$segmentConditionItem->value1,$segmentConditionItem->value2]);
                                            }
                                        });
                                    }
                                }

                                $subscriberConditionDatas->push($lineUserProfiles->pluck('id'));
                            }

                            $subscriberConditionDatas->push($subscriberLines->pluck('line_user_id'));
                            if($segmentConditionItem->is_empty || $segmentConditionItem->condition2 == 'is empty'){
                                $subscriberLines = SubscriberLine::whereIn('subscriber_id',$segmentSubscriberIds)->whereHas('lineUserProfile', function ($query) {
                                    $query->where('is_follow',1);
                                });
                                $subscriberLines->whereDoesntHave('subscriberItems', function ($query) use ($segmentConditionItem) {
                                    $query->where('field_id',$segmentConditionItem->condition1);
                                    // $query->where('value','');
                                });
                                $subscriberConditionDatas->push($subscriberLines->pluck('line_user_id'));
                            }
                            $conditionDatas->push($subscriberConditionDatas->collapse()->unique());
                        }else if($segmentConditionItem->title == 'sent activity'){
                            if($segmentConditionItem->condition1 == 'was sent'){
                                if($segmentConditionItem->condition2 == 'at anytime'){
                                    $lineUserProfiles->has('campaignSendMessages');
                                }else{
                                    $dateNow = Carbon::now();
                                    $lineUserProfiles->whereHas('campaignSendMessages', function ($query) use ($segmentConditionItem,$dateNow) {
                                        // dd($segmentConditionItem);
                                        if($segmentConditionItem->condition2 == 'is after'){
                                            $query->where('created_at','>',$segmentConditionItem->value1);
                                        }else if($segmentConditionItem->condition2 == 'is before'){
                                            $query->where('created_at','<',$segmentConditionItem->value1);
                                        }else if($segmentConditionItem->condition2 == 'is on'){
                                            $query->where('created_at','=',$segmentConditionItem->value1);
                                        }else if($segmentConditionItem->condition2 == 'is not on'){
                                            $query->where('created_at','<>',$segmentConditionItem->value1);
                                        }else if($segmentConditionItem->condition2 == 'is on or after'){
                                            $query->where('created_at','<=',$segmentConditionItem->value1);
                                        }else if($segmentConditionItem->condition2 == 'is between'){
                                            $query->whereBetween('created_at', [$segmentConditionItem->value1, $segmentConditionItem->value2]);
                                        }else if($segmentConditionItem->condition2 == 'is not between'){
                                            $query->whereNotBetween('created_at', [$segmentConditionItem->value1, $segmentConditionItem->value2]);
                                        }else if($segmentConditionItem->condition2 == 'relative date'){
                                            if($segmentConditionItem->value1 == 'first day'){
                                                if($segmentConditionItem->value2 == 'of this month'){
                                                    $dateCondition = $dateNow->format('F Y');
                                                    $newDateCondition = new Carbon('first day of '.$dateCondition);
                                                    $query->where('created_at','=',$newDateCondition);
                                                    // dd($newDateCondition);
                                                }else if($segmentConditionItem->value2 == 'of this year'){
                                                    $dateCondition = $dateNow->format('Y');
                                                    $newDateCondition = new Carbon('first day of January '.$dateCondition);
                                                    $query->where('created_at','=',$newDateCondition);
                                                }
                                            }else if($segmentConditionItem->value1 == 'last day'){
                                                if($segmentConditionItem->value2 == 'of this month'){
                                                    $dateCondition = $dateNow->format('F Y');
                                                    $newDateCondition = new Carbon('last day of '.$dateCondition);
                                                    $query->where('created_at','=',$newDateCondition);
                                                    // dd($newDateCondition);
                                                }else if($segmentConditionItem->value2 == 'of this year'){
                                                    $dateCondition = $dateNow->format('Y');
                                                    $newDateCondition = new Carbon('last day of December '.$dateCondition);
                                                    $query->where('created_at','=',$newDateCondition);
                                                }
                                            }else if($segmentConditionItem->value1 == 'sunday'){
                                                $weekOfConditionDay = Carbon::SUNDAY;
                                                $dateCondition = $dateNow->addDays($weekOfConditionDay);
                                                $query->where('created_at','=',$dateCondition);
                                            }else if($segmentConditionItem->value1 == 'monday'){
                                                $weekOfConditionDay = Carbon::MONDAY;
                                                $dateCondition = $dateNow->addDays($weekOfConditionDay);
                                                $query->where('created_at','=',$dateCondition);
                                            }else if($segmentConditionItem->value1 == 'tuesday'){
                                                $weekOfConditionDay = Carbon::TUESDAY;
                                                $dateCondition = $dateNow->addDays($weekOfConditionDay);
                                                $query->where('created_at','=',$dateCondition);
                                            }else if($segmentConditionItem->value1 == 'wednesday'){
                                                $weekOfConditionDay = Carbon::WEDNESDAY;
                                                $dateCondition = $dateNow->addDays($weekOfConditionDay);
                                                $query->where('created_at','=',$dateCondition);
                                            }else if($segmentConditionItem->value1 == 'thursday'){
                                                $weekOfConditionDay = Carbon::THURSDAY;
                                                $dateCondition = $dateNow->addDays($weekOfConditionDay);
                                                $query->where('created_at','=',$dateCondition);
                                            }else if($segmentConditionItem->value1 == 'friday'){
                                                $weekOfConditionDay = Carbon::FRIDAY;
                                                $dateCondition = $dateNow->addDays($weekOfConditionDay);
                                                $query->where('created_at','=',$dateCondition);
                                            }else if($segmentConditionItem->value1 == 'saturday'){
                                                $weekOfConditionDay = Carbon::SATURDAY;
                                                $dateCondition = $dateNow->addDays($weekOfConditionDay);
                                                $query->where('created_at','=',$dateCondition);
                                            }else if($segmentConditionItem->value1 == 'Day #'){
                                                $dateCondition = "";
                                                if($segmentConditionItem->value3 == 'day'){
                                                    $dateCondition = $dateNow->addDays($segmentConditionItem->value2);
                                                }else if($segmentConditionItem->value3 == 'week'){
                                                    $dateCondition = $dateNow->addWeeks($segmentConditionItem->value2);
                                                }else if($segmentConditionItem->value3 == 'month'){
                                                    $dateCondition = $dateNow->addMonths($segmentConditionItem->value2);
                                                }else if($segmentConditionItem->value3 == 'year'){
                                                    $dateCondition = $dateNow->addYears($segmentConditionItem->value3);
                                                }

                                                if($segmentConditionItem->value4 == 'from now'){
                                                    $query->where('created_at','>=',$dateCondition->format('Y-m-d'));
                                                }else{
                                                    $query->where('created_at','<=',$dateCondition->format('Y-m-d'));
                                                }
                                            }
                                        }else if($segmentConditionItem->condition2 == 'absolute date'){
                                            if($segmentConditionItem->value1 == 'today'){
                                                $dateCondition = $dateNow;
                                                $query->where('created_at','>=',$dateCondition->format('Y-m-d'));
                                            }else if($segmentConditionItem->value1 == 'yesterday'){
                                                $dateCondition = $dateNow->addDays(-1);
                                                $query->where('created_at','>=',$dateCondition->format('Y-m-d'));
                                            }else if($segmentConditionItem->value1 == 'tomorrow'){
                                                $dateCondition = $dateNow->addDays(1);
                                                $query->where('created_at','>=',$dateCondition->format('Y-m-d'));
                                            }
                                        }else{
                                            
                                        }
                                    });
                                }
                            }else if($segmentConditionItem->condition1 == 'was not sent'){
                                $dateNow = Carbon::now();
                                $lineUserProfiles->whereDoesntHave('campaignSendMessages', function ($query) use ($segmentConditionItem,$dateNow) {
                                    // dd($segmentConditionItem);
                                    if($segmentConditionItem->condition2 == 'is after'){
                                        $query->where('created_at','>',$segmentConditionItem->value1);
                                    }else if($segmentConditionItem->condition2 == 'is before'){
                                        $query->where('created_at','<',$segmentConditionItem->value1);
                                    }else if($segmentConditionItem->condition2 == 'is on'){
                                        $query->where('created_at','=',$segmentConditionItem->value1);
                                    }else if($segmentConditionItem->condition2 == 'is not on'){
                                        $query->where('created_at','<>',$segmentConditionItem->value1);
                                    }else if($segmentConditionItem->condition2 == 'is on or after'){
                                        $query->where('created_at','<=',$segmentConditionItem->value1);
                                    }else if($segmentConditionItem->condition2 == 'is between'){
                                        $query->whereBetween('created_at', [$segmentConditionItem->value1, $segmentConditionItem->value2]);
                                    }else if($segmentConditionItem->condition2 == 'is not between'){
                                        $query->whereNotBetween('created_at', [$segmentConditionItem->value1, $segmentConditionItem->value2]);
                                    }else if($segmentConditionItem->condition2 == 'relative date'){
                                        if($segmentConditionItem->value1 == 'first day'){
                                            if($segmentConditionItem->value2 == 'of this month'){
                                                $dateCondition = $dateNow->format('F Y');
                                                $newDateCondition = new Carbon('first day of '.$dateCondition);
                                                $query->where('created_at','=',$newDateCondition);
                                                // dd($newDateCondition);
                                            }else if($segmentConditionItem->value2 == 'of this year'){
                                                $dateCondition = $dateNow->format('Y');
                                                $newDateCondition = new Carbon('first day of January '.$dateCondition);
                                                $query->where('created_at','=',$newDateCondition);
                                            }
                                        }else if($segmentConditionItem->value1 == 'last day'){
                                            if($segmentConditionItem->value2 == 'of this month'){
                                                $dateCondition = $dateNow->format('F Y');
                                                $newDateCondition = new Carbon('last day of '.$dateCondition);
                                                $query->where('created_at','=',$newDateCondition);
                                                // dd($newDateCondition);
                                            }else if($segmentConditionItem->value2 == 'of this year'){
                                                $dateCondition = $dateNow->format('Y');
                                                $newDateCondition = new Carbon('last day of December '.$dateCondition);
                                                $query->where('created_at','=',$newDateCondition);
                                            }
                                        }else if($segmentConditionItem->value1 == 'sunday'){
                                            $weekOfConditionDay = Carbon::SUNDAY;
                                            $dateCondition = $dateNow->addDays($weekOfConditionDay);
                                            $query->where('created_at','=',$dateCondition);
                                        }else if($segmentConditionItem->value1 == 'monday'){
                                            $weekOfConditionDay = Carbon::MONDAY;
                                            $dateCondition = $dateNow->addDays($weekOfConditionDay);
                                            $query->where('created_at','=',$dateCondition);
                                        }else if($segmentConditionItem->value1 == 'tuesday'){
                                            $weekOfConditionDay = Carbon::TUESDAY;
                                            $dateCondition = $dateNow->addDays($weekOfConditionDay);
                                            $query->where('created_at','=',$dateCondition);
                                        }else if($segmentConditionItem->value1 == 'wednesday'){
                                            $weekOfConditionDay = Carbon::WEDNESDAY;
                                            $dateCondition = $dateNow->addDays($weekOfConditionDay);
                                            $query->where('created_at','=',$dateCondition);
                                        }else if($segmentConditionItem->value1 == 'thursday'){
                                            $weekOfConditionDay = Carbon::THURSDAY;
                                            $dateCondition = $dateNow->addDays($weekOfConditionDay);
                                            $query->where('created_at','=',$dateCondition);
                                        }else if($segmentConditionItem->value1 == 'friday'){
                                            $weekOfConditionDay = Carbon::FRIDAY;
                                            $dateCondition = $dateNow->addDays($weekOfConditionDay);
                                            $query->where('created_at','=',$dateCondition);
                                        }else if($segmentConditionItem->value1 == 'saturday'){
                                            $weekOfConditionDay = Carbon::SATURDAY;
                                            $dateCondition = $dateNow->addDays($weekOfConditionDay);
                                            $query->where('created_at','=',$dateCondition);
                                        }else if($segmentConditionItem->value1 == 'Day #'){
                                            $dateCondition = "";
                                            if($segmentConditionItem->value3 == 'day'){
                                                $dateCondition = $dateNow->addDays($segmentConditionItem->value2);
                                            }else if($segmentConditionItem->value3 == 'week'){
                                                $dateCondition = $dateNow->addWeeks($segmentConditionItem->value2);
                                            }else if($segmentConditionItem->value3 == 'month'){
                                                $dateCondition = $dateNow->addMonths($segmentConditionItem->value2);
                                            }else if($segmentConditionItem->value3 == 'year'){
                                                $dateCondition = $dateNow->addYears($segmentConditionItem->value2);
                                            }

                                            if($segmentConditionItem->value4 == 'from now'){
                                                $query->where('created_at','>=',$dateCondition->format('Y-m-d'));
                                            }else{
                                                $query->where('created_at','<=',$dateCondition->format('Y-m-d'));
                                            }
                                        }
                                    }else if($segmentConditionItem->condition2 == 'absolute date'){
                                        if($segmentConditionItem->value1 == 'today'){
                                            $dateCondition = $dateNow;
                                            $query->where('created_at','>=',$dateCondition->format('Y-m-d'));
                                        }else if($segmentConditionItem->value1 == 'yesterday'){
                                            $dateCondition = $dateNow->addDays(-1);
                                            $query->where('created_at','>=',$dateCondition->format('Y-m-d'));
                                        }else if($segmentConditionItem->value1 == 'tomorrow'){
                                            $dateCondition = $dateNow->addDays(1);
                                            $query->where('created_at','>=',$dateCondition->format('Y-m-d'));
                                        }
                                    }else{
                                        
                                    }
                                });
                            }

                            if($segmentConditionItem->condition3 == 'is'){
                                $lineUserProfiles->whereHas('campaignSendMessages', function ($query) use ($segmentConditionItem) {
                                    $query->where('campaign_id',$segmentConditionItem->value5);
                                });
                            }else if($segmentConditionItem->condition3 == 'is not'){
                                $lineUserProfiles->whereDoesntHave('campaignSendMessages', function ($query) use ($segmentConditionItem) {
                                    $query->where('campaign_id',$segmentConditionItem->value5);
                                });
                            }
                            $conditionDatas->push($lineUserProfiles->pluck('id'));
                        }else if($segmentConditionItem->title == 'BC Tracking'){
                            $lineUserProfiles->whereHas('trackingRecieveBcs', function ($query) use ($segmentConditionItem) {
                                if($segmentConditionItem->value1 != 'All source'){
                                    $query->where('tracking_source',$segmentConditionItem->value1);
                                }
                                if($segmentConditionItem->value2 != 'All Campaign'){
                                    $query->where('tracking_campaign',$segmentConditionItem->value2);
                                }
                                if($segmentConditionItem->value3 != 'All Ref'){
                                    $query->where('tracking_ref',$segmentConditionItem->value3);
                                }
                            });
                            if($segmentConditionItem->value6 != "" && $segmentConditionItem->value6 != 0){
                                $lineUserProfiles->has('trackingRecieveBcs', '>=', $segmentConditionItem->value6);
                            }
                            $conditionDatas->push($lineUserProfiles->pluck('id'));
                            // dd($datas);
                        }else if($segmentConditionItem->title == 'Redeem'){
                            // $couponUsers = CouponUser::orderBy('created_at');
                            if($segmentConditionItem->condition1 == 'was redeem'){
                                if($segmentConditionItem->condition2 == 'at anytime'){
                                    $lineUserProfiles->has('couponUsers');
                                }else{
                                    $dateNow = Carbon::now();
                                    $lineUserProfiles->whereHas('couponUsers', function ($query) use ($segmentConditionItem,$dateNow) {
                                        if($segmentConditionItem->condition2 == 'is after'){
                                            $query->whereDate('created_at','>',$segmentConditionItem->value1);
                                        }else if($segmentConditionItem->condition2 == 'is before'){
                                            $query->whereDate('created_at','<',$segmentConditionItem->value1);
                                        }else if($segmentConditionItem->condition2 == 'is on'){
                                            $query->whereDate('created_at','=',$segmentConditionItem->value1);
                                        }else if($segmentConditionItem->condition2 == 'is not on'){
                                            $query->whereDate('created_at','<>',$segmentConditionItem->value1);
                                        }else if($segmentConditionItem->condition2 == 'is on or before'){
                                            $query->whereDate('created_at','<=',$segmentConditionItem->value1);
                                        }
                                        else if($segmentConditionItem->condition2 == 'is on or after'){
                                            $query->whereDate('created_at','>=',$segmentConditionItem->value1);
                                        }else if($segmentConditionItem->condition2 == 'is between'){
                                            $query->whereBetween('created_at', [$segmentConditionItem->value1, $segmentConditionItem->value2]);
                                        }else if($segmentConditionItem->condition2 == 'is not between'){
                                            $query->whereNotBetween('created_at', [$segmentConditionItem->value1, $segmentConditionItem->value2]);
                                        }else{

                                        }
                                    });
                                }
                            }else{
                                $dateNow = Carbon::now();
                                $lineUserProfiles->whereDoesntHave('couponUsers', function ($query) use ($segmentConditionItem,$dateNow) {
                                    if($segmentConditionItem->condition2 == 'is after'){
                                        $query->whereDate('created_at','>',$segmentConditionItem->value1);
                                    }else if($segmentConditionItem->condition2 == 'is before'){
                                        $query->whereDate('created_at','<',$segmentConditionItem->value1);
                                    }else if($segmentConditionItem->condition2 == 'is on'){
                                        $query->whereDate('created_at','=',$segmentConditionItem->value1);
                                    }else if($segmentConditionItem->condition2 == 'is not on'){
                                        $query->whereDate('created_at','<>',$segmentConditionItem->value1);
                                    }else if($segmentConditionItem->condition2 == 'is on or before'){
                                        $query->whereDate('created_at','<=',$segmentConditionItem->value1);
                                    }
                                    else if($segmentConditionItem->condition2 == 'is on or after'){
                                        $query->whereDate('created_at','>=',$segmentConditionItem->value1);
                                    }else if($segmentConditionItem->condition2 == 'is between'){
                                        $query->whereBetween('created_at', [$segmentConditionItem->value1, $segmentConditionItem->value2]);
                                    }else if($segmentConditionItem->condition2 == 'is not between'){
                                        $query->whereNotBetween('created_at', [$segmentConditionItem->value1, $segmentConditionItem->value2]);
                                    }else{

                                    }
                                });
                            }

                            if($segmentConditionItem->value6 == 'form any coupon'){
                                
                            }else{
                                $coupon = Coupon::where('name',$segmentConditionItem->value6)->first();
                                $lineUserProfiles->whereHas('couponUsers', function ($query) use ($segmentConditionItem,$coupon) {
                                    $query->where('coupon_id',$coupon->id);
                                });
                            }
                            $conditionDatas->push($lineUserProfiles->pluck('id'));
                        }else if($segmentConditionItem->title == 'Used'){
                            // $couponUsers = CouponUser::orderBy('created_at');
                            if($segmentConditionItem->condition1 == 'has used'){
                                if($segmentConditionItem->condition2 == 'at anytime'){
                                    $lineUserProfiles->whereHas('couponUsers', function ($query) use ($segmentConditionItem) {
                                        $query->where('flag_status','reedeem');
                                    });
                                }else{
                                    $dateNow = Carbon::now();
                                    $lineUserProfiles->whereHas('couponUsers', function ($query) use ($segmentConditionItem,$dateNow) {
                                        $query->where('flag_status','reedeem');
                                        if($segmentConditionItem->condition2 == 'is after'){
                                            $query->whereDate('created_at','>',$segmentConditionItem->value1);
                                        }else if($segmentConditionItem->condition2 == 'is before'){
                                            $query->whereDate('created_at','<',$segmentConditionItem->value1);
                                        }else if($segmentConditionItem->condition2 == 'is on'){
                                            $query->whereDate('created_at','=',$segmentConditionItem->value1);
                                        }else if($segmentConditionItem->condition2 == 'is not on'){
                                            $query->whereDate('created_at','<>',$segmentConditionItem->value1);
                                        }else if($segmentConditionItem->condition2 == 'is on or before'){
                                            $query->whereDate('created_at','<=',$segmentConditionItem->value1);
                                        }
                                        else if($segmentConditionItem->condition2 == 'is on or after'){
                                            $query->whereDate('created_at','>=',$segmentConditionItem->value1);
                                        }else if($segmentConditionItem->condition2 == 'is between'){
                                            $query->whereBetween('created_at', [$segmentConditionItem->value1, $segmentConditionItem->value2]);
                                        }else if($segmentConditionItem->condition2 == 'is not between'){
                                            $query->whereNotBetween('created_at', [$segmentConditionItem->value1, $segmentConditionItem->value2]);
                                        }else{

                                        }
                                    });
                                }
                            }else{
                                if($segmentConditionItem->condition2 == 'at anytime'){
                                    $lineUserProfiles->whereHas('couponUsers', function ($query) use ($segmentConditionItem) {
                                        $query->where('flag_status',null);
                                    });
                                }else{
                                    $dateNow = Carbon::now();
                                    $lineUserProfiles->whereHas('couponUsers', function ($query) use ($segmentConditionItem,$dateNow) {
                                        $query->where('flag_status',null);
                                        if($segmentConditionItem->condition2 == 'is after'){
                                            $query->whereDate('created_at','>',$segmentConditionItem->value1);
                                        }else if($segmentConditionItem->condition2 == 'is before'){
                                            $query->whereDate('created_at','<',$segmentConditionItem->value1);
                                        }else if($segmentConditionItem->condition2 == 'is on'){
                                            $query->whereDate('created_at','=',$segmentConditionItem->value1);
                                        }else if($segmentConditionItem->condition2 == 'is not on'){
                                            $query->whereDate('created_at','<>',$segmentConditionItem->value1);
                                        }else if($segmentConditionItem->condition2 == 'is on or before'){
                                            $query->whereDate('created_at','<=',$segmentConditionItem->value1);
                                        }
                                        else if($segmentConditionItem->condition2 == 'is on or after'){
                                            $query->whereDate('created_at','>=',$segmentConditionItem->value1);
                                        }else if($segmentConditionItem->condition2 == 'is between'){
                                            $query->whereBetween('created_at', [$segmentConditionItem->value1, $segmentConditionItem->value2]);
                                        }else if($segmentConditionItem->condition2 == 'is not between'){
                                            $query->whereNotBetween('created_at', [$segmentConditionItem->value1, $segmentConditionItem->value2]);
                                        }else{

                                        }
                                    });
                                }
                            }

                            if($segmentConditionItem->value6 == 'form any coupon'){
                                
                            }else{
                                $coupon = Coupon::where('name',$segmentConditionItem->value6)->first();
                                $lineUserProfiles->whereHas('couponUsers', function ($query) use ($segmentConditionItem,$coupon) {
                                    $query->where('coupon_id',$coupon->id);
                                });
                            }
                            $conditionDatas->push($lineUserProfiles->pluck('id'));
                        }else if($segmentConditionItem->title == 'eCommerce'){
                            if($segmentConditionItem->condition1 == 'has purchased'){
                                $subscriberLineEcommerces->whereNotNull('dim_ecommerce_customer.id');
                                if($segmentConditionItem->condition2 == 'at anytime'){

                                }else if($segmentConditionItem->condition2 == 'is after'){
                                    $subscriberLineEcommerces->where('dim_ecommerce_order.order_date','>',$segmentConditionItem->value1);
                                }else if($segmentConditionItem->condition2 == 'is before'){
                                    $subscriberLineEcommerces->where('dim_ecommerce_order.order_date','<',$segmentConditionItem->value1);
                                }else if($segmentConditionItem->condition2 == 'is on'){
                                    $subscriberLineEcommerces->where('dim_ecommerce_order.order_date','=',$segmentConditionItem->value1);
                                }else if($segmentConditionItem->condition2 == 'is not on'){
                                    $subscriberLineEcommerces->where('dim_ecommerce_order.order_date','<>',$segmentConditionItem->value1);
                                }else if($segmentConditionItem->condition2 == 'is on or before'){
                                    $subscriberLineEcommerces->where('dim_ecommerce_order.order_date','<=',$segmentConditionItem->value1);
                                }else if($segmentConditionItem->condition2 == 'is on or after'){
                                    $subscriberLineEcommerces->where('dim_ecommerce_order.order_date','>=',$segmentConditionItem->value1);
                                }else if($segmentConditionItem->condition2 == 'is between'){
                                    $subscriberLineEcommerces->where('dim_ecommerce_order.order_date','>',$segmentConditionItem->value1)->where('dim_ecommerce_order.order_date','<',$segmentConditionItem->value2);
                                }else if($segmentConditionItem->condition2 == 'is not between'){
                                    $subscriberLineEcommerces->where('dim_ecommerce_order.order_date','<',$segmentConditionItem->value1)->where('dim_ecommerce_order.order_date','>',$segmentConditionItem->value2);
                                }

                                if($segmentConditionItem->condition3 == 'from any category'){

                                }else if($segmentConditionItem->condition3 == 'is'){
                                    $subscriberLineEcommerces->where('fact_ecommerce_product_category.id','=',$segmentConditionItem->value6);
                                }else if($segmentConditionItem->condition3 == 'is not'){
                                    $subscriberLineEcommerces->where('fact_ecommerce_product_category.id','<>',$segmentConditionItem->value6);
                                }

                                if($segmentConditionItem->condition4 == 'any product'){

                                }else if($segmentConditionItem->condition4 == 'is'){
                                    $subscriberLineEcommerces->where('dim_ecommerce_product.id','=',$segmentConditionItem->value7);
                                }else if($segmentConditionItem->condition4 == 'is not'){
                                    $subscriberLineEcommerces->where('dim_ecommerce_product.id','<>',$segmentConditionItem->value7);
                                }

                                if($segmentConditionItem->condition5 == 'any total'){

                                }else if($segmentConditionItem->condition5 == 'is'){
                                    $subscriberLineEcommerces->where('dim_ecommerce_order.total_due','=',$segmentConditionItem->value8);
                                }else if($segmentConditionItem->condition5 == 'is not'){
                                    $subscriberLineEcommerces->where('dim_ecommerce_order.total_due','<>',$segmentConditionItem->value8);
                                }else if($segmentConditionItem->condition5 == 'is empty'){

                                }else if($segmentConditionItem->condition5 == 'is not empty'){

                                }else if($segmentConditionItem->condition5 == 'contains'){
                                    $subscriberLineEcommerces->where('dim_ecommerce_order.total_due','like','%'.$segmentConditionItem->value8.'%');
                                }else if($segmentConditionItem->condition5 == 'does not contains'){
                                    $subscriberLineEcommerces->where('dim_ecommerce_order.total_due','not like','%'.$segmentConditionItem->value8.'%');
                                }else if($segmentConditionItem->condition5 == 'starts with'){
                                    $subscriberLineEcommerces->where('dim_ecommerce_order.total_due','like','%'.$segmentConditionItem->value8);
                                }else if($segmentConditionItem->condition5 == 'ends with'){
                                    $subscriberLineEcommerces->where('dim_ecommerce_order.total_due','like',$segmentConditionItem->value8.'%');
                                }else if($segmentConditionItem->condition5 == 'gather than'){
                                    $subscriberLineEcommerces->where('dim_ecommerce_order.total_due','>',$segmentConditionItem->value8);
                                }else if($segmentConditionItem->condition5 == 'less than'){
                                    $subscriberLineEcommerces->where('dim_ecommerce_order.total_due','<',$segmentConditionItem->value8);
                                }else if($segmentConditionItem->condition5 == 'gather than or equal'){
                                    $subscriberLineEcommerces->where('dim_ecommerce_order.total_due','>=',$segmentConditionItem->value8);
                                }else if($segmentConditionItem->condition5 == 'less than or equal'){
                                    $subscriberLineEcommerces->where('dim_ecommerce_order.total_due','<=',$segmentConditionItem->value8);
                                }else if($segmentConditionItem->condition5 == 'is between'){
                                    $subscriberLineEcommerces->where('dim_ecommerce_order.total_due','>=',$segmentConditionItem->value8)->where('dim_ecommerce_order.total_due','<=',$segmentConditionItem->value9);
                                }
                            }else if($segmentConditionItem->condition1 == 'has not purchased'){
                                $subscriberLineEcommerces->whereNull('dim_ecommerce_customer.id');
                            }else if($segmentConditionItem->condition1 == 'Order Status'){
                                $subscriberLineEcommerces->whereNotNull('dim_ecommerce_customer.id');
                                if($segmentConditionItem->condition6 == 'any status'){

                                }else if($segmentConditionItem->condition6 == 'is'){
                                    $subscriberLineEcommerces->where('dim_ecommerce_order.order_status','=',$segmentConditionItem->value10);
                                }else if($segmentConditionItem->condition6 == 'is not'){
                                    $subscriberLineEcommerces->where('dim_ecommerce_order.order_status','<>',$segmentConditionItem->value10);
                                }
                                
                            }
                            $conditionDatas->push($subscriberLineEcommerces->pluck('line_user_id'));
                        }
                    }

                    if($subscriberMatch == 'All'){
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
        // dd($subscriberLines->get());

        $getDatas = $getDatas->collapse()->unique();

        return $getDatas;
    }

    public static function segmentCampaign($subscriberLines)
    {
        $mids = [];
        foreach ($subscriberLines as $key => $subscriberLineData) {
            // foreach ($subscriberLine as $key => $subscriberLineData) {
                // $lineUserProfile = $subscriberLineData->lineUserProfile;
            $lineUserProfile = LineUserProfile::find($subscriberLineData);
            $mids[] = $lineUserProfile->mid;
                // dd($subscriberLineData);
            // }
            // dd($subscriberLine);
        }
        // dd($subscriberLines);
        return $mids;
    }
}
