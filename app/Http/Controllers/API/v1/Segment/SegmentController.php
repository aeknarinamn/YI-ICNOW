<?php

namespace YellowProject\Http\Controllers\API\v1\Segment;

use Illuminate\Http\Request;
use YellowProject\Http\Controllers\Controller;
use YellowProject\Segment\Segment;
use YellowProject\Segment\SegmentCondition;
use YellowProject\Segment\SegmentConditionItem;
use YellowProject\Segment\SegmentSubscriber;
use YellowProject\Segment\CoreFunction;
use YellowProject\Subscriber;
use YellowProject\SubscriberLine;
use YellowProject\Campaign;
use YellowProject\Field;
use YellowProject\TrackingBc;
use YellowProject\LineUserProfile;
use YellowProject\HistoryAddBlock;
use Carbon\Carbon;

class SegmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $datas = [];
        $segments = Segment::orderByDesc('updated_at')->get();
        foreach ($segments as $key => $segment) {
            $segmentFolder = $segment->folder;
            $datas[$key]['id'] = $segment->id;
            $datas[$key]['name'] = $segment->name;
            $datas[$key]['updated_at'] = $segment->updated_at->format('Y-m-d H:i');
            $datas[$key]['folder_name'] = ($segmentFolder)? $segmentFolder->name : "";
            $datas[$key]['total_count'] = $segment->count_data;
            $datas[$key]['total_message_sent'] = 0;
            $datas[$key]['count_campaign'] = $segment->campaigns->count();
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
        $segmentCheckName = Segment::where('name',$request->name)->first();
        if($segmentCheckName){
          return response()->json([
              'msg_return' => 'ข้อมูลชื่อซ้ำ',
              'code_return' => 2,
          ]);
        }
        // $getDatas = CoreFunction::queryData($request);
        // $getDatas = $getDatas->collapse()->unique();
        $datas = $request->all();
        $datas['count_data'] = 0;
        $segment = Segment::create($datas);
        foreach ($datas['subscriber_list'] as $key => $subscriberListId) {
            SegmentSubscriber::create([
                'segment_id' => $segment->id,
                'subscriber_id' => $subscriberListId
            ]);
        }
        foreach ($datas['condition'] as $key => $condition) {
            $condition['segment_id'] = $segment->id;
            $segmentCondition = SegmentCondition::create($condition);
            // $segmentCondition = SegmentCondition::create([
            //     'segment_id' => $segment->id,
            //     'subscriber_match' => $condition['subscriber_match']
            // ]);
            foreach ($condition['condition_items'] as $key => $conditionItems) {
                $conditionItems['segment_condition_id'] = $segmentCondition->id;
                SegmentConditionItem::create($conditionItems);
            }
        }

        return response()->json([
            'segment_id' => $segment->id,
            'msg_return' => 'บันทึกสำเร็จ',
            'code_return' => 1,
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
        $datas = collect();
        $segment = Segment::find($id);
        $segmentArray = ([
            'name' => $segment->name,
            'folder_id' => $segment->folder_id
        ]);
        $segmentSusbcribers = $segment->segmentSubscribers->pluck('subscriber_id');
        $segmentArray['subscriber_list'] = $segmentSusbcribers;
        // $datas->put('subscriber_list',$segmentSusbcribers->toArray());
        $segmentConditions = $segment->segmentConditions;
        $segmentConditionArrays = [];
        foreach ($segmentConditions as $index => $segmentCondition) {
            $segmentConditionArrays[$index]['condition_id'] = $segmentCondition->id;
            $segmentConditionArrays[$index]['subscriber_match'] = $segmentCondition->subscriber_match;
            $segmentConditionItems = $segmentCondition->segmentConditionItems;
            if($segmentConditionItems){
                foreach ($segmentConditionItems as $key => $segmentConditionItem) {
                    $segmentConditionArrays[$index]['condition_items'][$key] = $segmentConditionItem->toArray();
                    $segmentConditionArrays[$index]['condition_items'][$key]['condition_item_id'] = $segmentConditionItem->id;
                }
            }
        }
        // $datas->put('condition',$segmentConditionArrays);
        $segmentArray['condition'] = $segmentConditionArrays;
        $datas->push($segmentArray);

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
        $datas = $request->all();
        $datas['count_data'] = 0;
        $segment = Segment::find($id);
        $segmentCheckName = Segment::where('name',$request->name)->first();
        if($segmentCheckName && $request->name != $segment->name){
          return response()->json([
              'msg_return' => 'ข้อมูลชื่อซ้ำ',
              'code_return' => 2,
          ]);
        }
        $segment->update($datas);
        SegmentSubscriber::where('segment_id',$segment->id)->delete();
        $segmentConditions = SegmentCondition::where('segment_id',$segment->id)->get();
        foreach ($segmentConditions as $key => $segmentCondition) {
            SegmentConditionItem::where('segment_condition_id',$segmentCondition->id)->delete();
            $segmentCondition->delete();
        }
        
        foreach ($datas['subscriber_list'] as $key => $subscriberListId) {
            SegmentSubscriber::create([
                'segment_id' => $segment->id,
                'subscriber_id' => $subscriberListId
            ]);
        }
        foreach ($datas['condition'] as $key => $condition) {
            $condition['segment_id'] = $segment->id;
            $segmentCondition = SegmentCondition::create($condition);
            foreach ($condition['condition_items'] as $key => $conditionItems) {
                $conditionItems['segment_condition_id'] = $segmentCondition->id;
                SegmentConditionItem::create($conditionItems);
            }
        }

        return response()->json([
            'msg_return' => 'บันทึกสำเร็จ',
            'code_return' => 1,
            'segment_id' => $segment->id,
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
        $segment = Segment::find($id);
        $segmentConditions = $segment->segmentConditions;
        $segmentSubscribers = $segment->segmentSubscribers;
        if($segmentConditions){
            foreach ($segmentConditions as $key => $segmentCondition) {
                $segmentConditionItems =  $segmentCondition->segmentConditionItems;
                if($segmentConditionItems){
                    foreach ($segmentConditionItems as $key => $segmentConditionItem) {
                        $segmentConditionItem->delete();
                    }
                }
                $segmentCondition->delete();
            }
        }
        if($segmentSubscribers){
            foreach ($segmentSubscribers as $key => $segmentSubscriber) {
                $segmentSubscriber->delete();
            }
        }
        $segment->delete();
        return response()->json([
            'msg_return' => 'ลบสำเร็จ',
            'code_return' => 1,
        ]);
    }

    public function getSubscriber(Request $request)
    {
        $subscriberIds = $request->subscriber_id;
        $datas = Subscriber::has('fields','>',0)->get();

        return response()->json([
            'datas' => $datas,
        ]);
    }

    public function getSubscriberListField(Request $request)
    {
        // return response()->json($request->all());
        $checkLineUserId = in_array("LINE-userID",$request->subscriber_id);
        if($request->subscriber_id != []){
            $subscriberIds = Subscriber::whereIn('category_id',$request->subscriber_id)->pluck('id')->toArray();
        }else{
            $subscriberIds = [];
        }
        // $subscriberIds = $request->subscriber_id;
        $datas = Collect();
        $subscriberListFieldArrays = [];
        $subscribers = Subscriber::whereIn('id', $subscriberIds)->has('fields')->get();
        foreach ($subscribers as $key => $subscriber) {
            $fields = $subscriber->fields;
            foreach ($fields as $key => $field) {
                $field->fieldItems;
                $field->subscriber;
            }
            $datas->push($fields);
            // $subscriberListFieldArrays[$subscriber->id] = $fields->toArray();
        }

        if($checkLineUserId){
            $lineUserId[0]['description'] = 'Follow First Date';
            $lineUserId[0]['field_items'] = [];
            $lineUserId[0]['field_name'] = 'Follow First Date';
            $lineUserId[0]['id'] = 'follow_first_date';
            $lineUserId[0]['is_segment'] = 1;
            $lineUserId[0]['name'] = 'Follow First Date';
            $lineUserId[0]['subscriber']['name'] = 'LineUserId';
            $lineUserId[0]['type'] = 'date';

            $lineUserId[1]['description'] = 'Follow Updated Date';
            $lineUserId[1]['field_items'] = [];
            $lineUserId[1]['field_name'] = 'Follow Updated Date';
            $lineUserId[1]['id'] = 'follow_update_date';
            $lineUserId[1]['is_segment'] = 1;
            $lineUserId[1]['name'] = 'Follow Updated Date';
            $lineUserId[1]['subscriber']['name'] = 'LineUserId';
            $lineUserId[1]['type'] = 'date';

            $datas->push($lineUserId);
        }

        // $datas->put($subscriberListFieldArrays);

        return response()->json([
            'datas' => $datas,
        ]);
    }

    public function getBeacon(Request $request)
    {
        $datas = Collect();
        // $datas = Beacon::all();
        // $datas->push($beacon);

        return response()->json([
            'datas' => $datas,
        ]);
    }

    public function getCampaign(Request $request)
    {
        // $datas = Collect();
        $datas = Campaign::all();
        // $datas->push($campaigns);

        return response()->json([
            'datas' => $datas,
        ]);
    }

    public function getDataSegment(Request $request)
    {
        $offset = (isset($request->offset))? $request->offset : 1;
        $limit = (isset($request->limit))? $request->limit : 1000;
        $subscriberListArrays = $request['subscriber_list'];
        $getFieldListQuery = CoreFunction::getFieldQuery($request);
        $fieldListArrays = $getFieldListQuery['susbcriber_data_field'];
        $subscriberIds = Subscriber::whereIn('category_id',$subscriberListArrays)->pluck('id')->toArray();
        $getDatas = CoreFunction::queryData($request);
        $fields = Field::whereIn('subscriber_id',$subscriberIds)
            ->where('is_segment',1)
            ->get();

        $datas = [];
        $getDatas = $getDatas->forPage($offset, $limit);
        $subscriberItems =  \DB::table('dim_subscriber_line as sl')
                ->leftJoin('fact_subscribers as sb', 'sl.id', '=', 'sb.subscriber_line_id')
                ->whereIn('sl.subscriber_id',$subscriberIds)
                ->whereIn('sb.field_id',$fields->pluck('id')->toArray())
                ->whereIn('sl.line_user_id',$getDatas->toArray())
                ->get();

        foreach ($getDatas as $keySubscriberLine => $dataValue) {
            $subscriberItemDatas = $subscriberItems->filter(function ($value, $key) use($dataValue) {
                return $value->line_user_id == $dataValue;
            });
            $lineUSerProfile = LineUserProfile::find($dataValue);
            $datas[$keySubscriberLine]['Line User ID'] = $lineUSerProfile->id;
            $datas[$keySubscriberLine]['Display Name'] = $lineUSerProfile->name;
                foreach ($fields as $keyField => $field) {
                    $subscriberItemInField = $subscriberItemDatas->filter(function ($value, $key) use ($field) {
                        return $value->field_id == $field->id;
                    })->first();

                    $datas[$keySubscriberLine][$field->name] = ($subscriberItemInField)? $subscriberItemInField->value : null ;
                }
            // }
        }

        return response()->json([
            'datas' => $datas,
            'offset' => $offset,
            'limit' => $limit,
        ]);
    }

    public function countDataSegment(Request $request)
    {
        $getDatas = CoreFunction::queryData($request);
        // $getDatas = $getDatas->collapse()->unique();

        return response()->json([
            'datas_count' => $getDatas->count(),
        ]);
    }

    public function segmentExportdata(Request $request)
    {
        dd($request->all());

        // return response()->json([
        //     'datas' => $datas,
        // ]);
    }

    public function getSusbcriberAll(Request $request)
    {
        $subscribers = Subscriber::all();

        return response()->json([
            'datas' => $subscribers,
        ]);
    }

    public function getTrackingSource()
    {
        $count = 0;
        $datas = [];
        $trackingSources = TrackingBc::all()->unique('tracking_source');
        foreach ($trackingSources as $key => $trackingSource) {
            $datas[$key] = $trackingSource->tracking_source;
            $count++;
        }
        // $trackingSourceDatas = $trackingSources->pluck('tracking_source')->unique();

        return response()->json([
            'datas' => $datas,
        ]);
    }

    public function getTrackingCampaign()
    {
        $count = 0;
        $datas = [];
        $trackingSources = TrackingBc::all()->unique('tracking_campaign');
        foreach ($trackingSources as $key => $trackingSource) {
            $datas[$key] = $trackingSource->tracking_campaign;
            $count++;
        }
        // $trackingSourceDatas = $trackingSources->pluck('tracking_campaign')->unique();

        return response()->json([
            'datas' => $datas,
        ]);
    }

    public function getTrackingRef()
    {
        $count = 0;
        $datas = [];
        $trackingSources = TrackingBc::all()->unique('tracking_ref');
        foreach ($trackingSources as $key => $trackingSource) {
            $datas[$count] = $trackingSource->tracking_ref;
            $count++;
        }
        // $trackingSourceDatas = $trackingSources->pluck('tracking_ref')->unique();

        return response()->json([
            'datas' => $datas,
        ]);
    }

    public function getSegmentName()
    {
        $datas = [];
        $segments = Segment::orderByDesc('updated_at')->get();
        foreach ($segments as $key => $segment) {
            $datas[$key]['id'] = $segment->id;
            $datas[$key]['name'] = $segment->name;
        }

        return response()->json([
            'datas' => $datas,
        ]);
    }

    public function getSBCouponName()
    {
        $datas = [];

        return response()->json([
            'datas' => $datas,
        ]);
    }

    public function getCouponName()
    {
        $datas = [];

        return response()->json([
            'datas' => $datas,
        ]);
    }
}
