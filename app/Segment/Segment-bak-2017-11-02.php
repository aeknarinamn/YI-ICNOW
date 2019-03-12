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

    public static function getSegmentData($segmentId)
    {
        $datas = collect();
        $segment = Segment::find($segmentId);
        $segmentConditions = $segment->segmentConditions;
        $segmentSubscriberIds = $segment->segmentSubscribers->pluck('subscriber_id')->toArray();
        $subscriberLines = SubscriberLine::whereIn('subscriber_id',$segmentSubscriberIds);
        $lineUserProfiles = LineUserProfile::orderBy('created_at');
        // dd($lineUserProfiles);
        // $subscribers = Subscriber::whereIn('id',$segmentSubscriberIds)->get();
        if($segmentConditions->count() > 0){
            foreach ($segmentConditions as $key => $segmentCondition) {
                $segmentConditionItems = $segmentCondition->segmentConditionItems;
                if($segmentConditionItems->count() > 0){
                    foreach ($segmentConditionItems as $key => $segmentConditionItem) {
                        // dd($segmentConditionItem);
                        if($segmentConditionItem->title == 'Subscriber Data'){
                            $subscriberLines->whereHas('subscriberItems', function ($query) use ($segmentConditionItem) {
                                // \Log::debug('in segment condition1 =>'.$segmentConditionItem->condition1);
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
                                }else if($segmentConditionItem->condition2 == 'is between'){
                                    $query->whereBetween('value', [(int)$segmentConditionItem->value1, (int)$segmentConditionItem->value2]);
                                }else if($segmentConditionItem->condition2 == 'is after'){
                                    $query->whereDate('value', '<', $segmentConditionItem->value1);
                                }else if($segmentConditionItem->condition2 == 'is before'){
                                    $query->whereDate('value', '>', $segmentConditionItem->value1);
                                }else if($segmentConditionItem->condition2 == 'is on'){
                                    $query->whereDate('value', '=', $segmentConditionItem->value1);
                                }else if($segmentConditionItem->condition2 == 'is not on'){
                                    $query->whereDate('value', '<>', $segmentConditionItem->value1);
                                }else if($segmentConditionItem->condition2 == 'is on or before'){
                                    $query->whereDate('value', '<=', $segmentConditionItem->value1);
                                }else if($segmentConditionItem->condition2 == 'is on or after'){
                                    $query->whereDate('value', '>=', $segmentConditionItem->value1);
                                }else if($segmentConditionItem->condition2 == 'is between'){
                                    $query->whereDate('value', '>=', $segmentConditionItem->value1);
                                }else if($segmentConditionItem->condition2 == 'is not between'){
                                    $query->whereDate('value','>=',$segmentConditionItem->value1)->whereDate('value','<=',$segmentConditionItem->value2);
                                }else if($segmentConditionItem->condition2 == 'relative date'){

                                }else if($segmentConditionItem->condition2 == 'absolute date'){
                                    $dateNow = Carbon\Carbon::now();
                                    if($segmentConditionItem->value1 == 'today'){
                                        $query->whereDate('value', '=', $dateNow->format('Y-m-d'));
                                    }else if($segmentConditionItem->value1 == 'yesterday'){
                                        $query->whereDate('value', '=', $dateNow->addDay(-1)->format('Y-m-d'));
                                    }else{
                                        $query->whereDate('value', '=', $dateNow->addDay(1)->format('Y-m-d'));
                                    }
                                }else if($segmentConditionItem->condition2 == 'anniversary is'){
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
                            $datas->push($subscriberLines->pluck('line_user_id'));
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
                            $datas->push($lineUserProfiles->pluck('id'));
                            // dd($datas);
                        }else if($segmentConditionItem->title == 'click activity'){
                            if($segmentConditionItem->condition1 == 'has clicked'){
                                if($segmentConditionItem->condition2 == 'at anytime'){
                                    $lineUserProfiles->has('trackingRecieveBcs');
                                }else{
                                    $dateNow = Carbon::now();
                                    $lineUserProfiles->whereHas('trackingRecieveBcs', function ($query) use ($segmentConditionItem,$dateNow) {
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
                            }else if($segmentConditionItem->condition1 == 'has not clicked'){
                                $dateNow = Carbon::now();
                                $lineUserProfiles->whereDoesntHave('trackingRecieveBcs', function ($query) use ($segmentConditionItem,$dateNow) {
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
                                $campaign = Campaign::find($segmentConditionItem->value5);
                                if($campaign){
                                    $lineUserProfiles->whereHas('trackingRecieveBcs', function ($query) use ($segmentConditionItem,$campaign) {
                                        $query->where('tracking_campaign',$campaign->name);
                                    });
                                }
                            }else if($segmentConditionItem->condition3 == 'is not'){
                                $campaign = Campaign::find($segmentConditionItem->value5);
                                if($campaign){
                                    $lineUserProfiles->whereDoesntHave('trackingRecieveBcs', function ($query) use ($segmentConditionItem,$campaign) {
                                        $query->where('tracking_campaign',$campaign->name);
                                    });
                                }
                            }
                            $datas->push($lineUserProfiles->pluck('id'));
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
                            $datas->push($lineUserProfiles->pluck('id'));
                            // dd($datas);
                        }
                    }
                }else{
                    $datas->push($subscriberLines->pluck('line_user_id'));
                }
            }
        }else{
            $datas->push($subscriberLines->pluck('line_user_id'));
        }
        // dd($subscriberLines->get());

        return $datas;
    }

    public static function getSegmentData2($segmentId)
    {
        $datas = collect();
        $segment = Segment::find($segmentId);
        $segmentConditions = $segment->segmentConditions;
        $segmentSubscriberIds = $segment->segmentSubscribers->pluck('subscriber_id')->toArray();
        $subscriberLines = SubscriberLine::whereIn('subscriber_id',$segmentSubscriberIds);
        $lineUserProfiles = LineUserProfile::orderBy('created_at');
        // dd($lineUserProfiles);
        // $subscribers = Subscriber::whereIn('id',$segmentSubscriberIds)->get();
        if($segmentConditions->count() > 0){
            foreach ($segmentConditions as $key => $segmentCondition) {
                $segmentConditionItems = $segmentCondition->segmentConditionItems;
                if($segmentConditionItems->count() > 0){
                    foreach ($segmentConditionItems as $key => $segmentConditionItem) {
                        dd($segmentConditionItem);
                        if($segmentConditionItem->title == 'Subscriber Data'){
                            $subscriberLines->whereHas('subscriberItems', function ($query) use ($segmentConditionItem) {
                                // \Log::debug('in segment condition1 =>'.$segmentConditionItem->condition1);
                                $query->where('field_id',$segmentConditionItem->condition1);
                                if($segmentConditionItem->condition2 == 'is'){
                                    $query->where('value',$segmentConditionItem->value1);
                                }else if($segmentConditionItem->condition2 == 'is not'){
                                    $query->where('value','<>',$segmentConditionItem->value1);
                                }else if($segmentConditionItem->condition2 == 'is empty'){
                                    
                                }else if($segmentConditionItem->condition2 == 'is not empty'){
                                    
                                }else if($segmentConditionItem->condition2 == 'contains'){
                                    $query->where('value','like','%'.$segmentConditionItem->value1.'%');
                                }else if($segmentConditionItem->condition2 == 'does not contain'){
                                    $query->where('value','not like','%'.$segmentConditionItem->value1.'%');
                                }else if($segmentConditionItem->condition2 == 'starts with'){
                                    $query->where('value','like','%'.$segmentConditionItem->value1);
                                }else if($segmentConditionItem->condition2 == 'ends with'){
                                    $query->where('value','like',$segmentConditionItem->value1.'%');
                                }else if($segmentConditionItem->condition2 == 'gather than'){
                                    $query->where('value','>',$segmentConditionItem->value1);
                                }else if($segmentConditionItem->condition2 == 'less than'){
                                    $query->where('value','<',$segmentConditionItem->value1);
                                }else if($segmentConditionItem->condition2 == 'gather than or equal'){
                                    $query->where('value','>=',$segmentConditionItem->value1);
                                }else if($segmentConditionItem->condition2 == 'less than or equal'){
                                    $query->where('value','<=',$segmentConditionItem->value1);
                                }else if($segmentConditionItem->condition2 == 'is between'){
                                    $query->whereBetween('value', [$segmentConditionItem->value1, $segmentConditionItem->value2]);
                                }else if($segmentConditionItem->condition2 == 'is after'){
                                    $query->whereDate('value', '<', $segmentConditionItem->value1);
                                }else if($segmentConditionItem->condition2 == 'is before'){
                                    $query->whereDate('value', '>', $segmentConditionItem->value1);
                                }else if($segmentConditionItem->condition2 == 'is on'){
                                    $query->whereDate('value', '=', $segmentConditionItem->value1);
                                }else if($segmentConditionItem->condition2 == 'is not on'){
                                    $query->whereDate('value', '<>', $segmentConditionItem->value1);
                                }else if($segmentConditionItem->condition2 == 'is on or before'){
                                    $query->whereDate('value', '<=', $segmentConditionItem->value1);
                                }else if($segmentConditionItem->condition2 == 'is on or after'){
                                    $query->whereDate('value', '>=', $segmentConditionItem->value1);
                                }else if($segmentConditionItem->condition2 == 'is between'){
                                    $query->whereDate('value', '>=', $segmentConditionItem->value1);
                                }else if($segmentConditionItem->condition2 == 'is not between'){
                                    $query->whereDate('value','>=',$segmentConditionItem->value1)->whereDate('value','<=',$segmentConditionItem->value2);
                                }else if($segmentConditionItem->condition2 == 'relative date'){

                                }else if($segmentConditionItem->condition2 == 'absolute date'){
                                    $dateNow = Carbon\Carbon::now();
                                    if($segmentConditionItem->value1 == 'today'){
                                        $query->whereDate('value', '=', $dateNow->format('Y-m-d'));
                                    }else if($segmentConditionItem->value1 == 'yesterday'){
                                        $query->whereDate('value', '=', $dateNow->addDay(-1)->format('Y-m-d'));
                                    }else{
                                        $query->whereDate('value', '=', $dateNow->addDay(1)->format('Y-m-d'));
                                    }
                                }else if($segmentConditionItem->condition2 == 'anniversary is'){
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
                            $datas->push($subscriberLines->pluck('line_user_id'));
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
                            $datas->push($lineUserProfiles->pluck('id'));
                            // dd($datas);
                        }else if($segmentConditionItem->title == 'click activity'){
                            if($segmentConditionItem->condition1 == 'has clicked'){
                                if($segmentConditionItem->condition2 == 'at anytime'){
                                    $lineUserProfiles->has('trackingRecieveBcs');
                                }else{
                                    $dateNow = Carbon::now();
                                    $lineUserProfiles->whereHas('trackingRecieveBcs', function ($query) use ($segmentConditionItem,$dateNow) {
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
                            }else if($segmentConditionItem->condition1 == 'has not clicked'){
                                $dateNow = Carbon::now();
                                $lineUserProfiles->whereDoesntHave('trackingRecieveBcs', function ($query) use ($segmentConditionItem,$dateNow) {
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
                                $campaign = Campaign::find($segmentConditionItem->value5);
                                $lineUserProfiles->whereHas('trackingRecieveBcs', function ($query) use ($segmentConditionItem,$campaign) {
                                    $query->where('tracking_campaign',$campaign->name);
                                });
                            }else if($segmentConditionItem->condition3 == 'is not'){
                                $campaign = Campaign::find($segmentConditionItem->value5);
                                $lineUserProfiles->whereDoesntHave('trackingRecieveBcs', function ($query) use ($segmentConditionItem,$campaign) {
                                    $query->where('tracking_campaign',$campaign->name);
                                });
                            }
                            $datas->push($lineUserProfiles->pluck('id'));
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
                            $lineUserProfiles->has('trackingRecieveBcs', '>=', $segmentConditionItem->value4);
                            $datas->push($lineUserProfiles->pluck('id'));
                            // dd($datas);
                        }
                    }
                }else{
                    $datas->push($subscriberLines->get());
                }
            }
        }else{
            $datas->push($subscriberLines->get());
        }
        // dd($subscriberLines->get());

        return $datas;
    }

    public static function segmentCampaign($subscriberLines)
    {
        $mids = [];
        foreach ($subscriberLines as $key => $subscriberLine) {
            foreach ($subscriberLine as $key => $subscriberLineData) {
                // $lineUserProfile = $subscriberLineData->lineUserProfile;
                $lineUserProfile = LineUserProfile::find($subscriberLineData);
                $mids[] = $lineUserProfile->mid;
                // dd($subscriberLineData);
            }
            // dd($subscriberLine);
        }
        // dd($subscriberLines);
        return $mids;
    }
}
