<?php

namespace YellowProject\Http\Controllers\API\v1\Segment;

use Illuminate\Http\Request;
use YellowProject\Http\Controllers\Controller;
use YellowProject\SubscriberLine;
use YellowProject\Subscriber;
use YellowProject\Segment\QuickSegment;
use YellowProject\Segment\QuickSegmentItem;
use Carbon\Carbon;
use Excel;

class QuickSegmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $datas = [];
        $quickSegments = QuickSegment::all();
        foreach ($quickSegments as $key => $quickSegment) {
            $datas[$key]['id'] = $quickSegment->id;
            $datas[$key]['name'] = $quickSegment->name;
            $datas[$key]['total_count'] = ($quickSegment->quickSegmentItem)? $quickSegment->quickSegmentItem->count() : 0;
            $datas[$key]['total_message'] = 0;
            $datas[$key]['last_edit'] = $quickSegment->updated_at->format('Y-m-d H:i:s');
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
        $quickSegment = QuickSegment::create($request->all());
        return response()->json([
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
        $quickSegment = QuickSegment::find($id);

        return response()->json([
            'datas' => $quickSegment,
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
        $quickSegment = QuickSegment::find($id);
        $quickSegment->update($request->all());
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
        $quickSegment = QuickSegment::find($id);
        $quickSegmentItems = $quickSegment->quickSegmentItem;
        foreach ($quickSegmentItems as $key => $quickSegmentItem) {
            $quickSegmentItem->delete();
        }
        $quickSegment->delete();

        return response()->json([
            'msg_return' => 'ลบสำเร็จ',
            'code_return' => 1,
        ]);
    }

    public function uploadQuickSegment(Request $request,$id)
    {
        // dd($request->all());
        $quickSegmentMain = QuickSegment::find($id);
        $quickSegmentItems = $quickSegmentMain->quickSegmentItem;
        $datas = [];
        $userIds = [];
        $countNewUser = 0;
        $countDuplicateUser = 0;
        if($request->items){
            QuickSegmentItem::where('quick_segment_id',$id)->delete();
            foreach ($request->items as $key => $quickSegment) {
                $quickSegment['quick_segment_id'] = $id;
                $quickSegment['line_user_id'] = $quickSegment['UserID'];
                $userIds[] = $quickSegment['UserID'];
                QuickSegmentItem::create($quickSegment);
            }
        }

        if($quickSegmentItems->count() > 0){
            $quickSegmentItemArrays = $quickSegmentItems->pluck('line_user_id')->toArray();
            $newUserValue = array_diff($userIds,$quickSegmentItemArrays);
            $duplicateUserValue = array_intersect($userIds,$quickSegmentItemArrays);
            $countNewUser = count($newUserValue);
            $countDuplicateUser = count($duplicateUserValue);
            // dd($duplicateUserValue);
        }else{
            $countNewUser = count($userIds);
        }

        $quickSegmentMain->update([
            'count_new_user' => $countNewUser,
            'count_duplicate_user' => $countDuplicateUser,
        ]);
        // dd($userIds);

        return response()->json([
            'msg_return' => 'บันทึกสำเร็จ',
            'code_return' => 1,
        ]);
    }

    public function importResult()
    {
        $datas = [];
        $quickSegments = QuickSegment::all();
        foreach ($quickSegments as $key => $quickSegment) {
            $datas[$key]['name'] = $quickSegment->name;
            $datas[$key]['new_user_import'] =  $quickSegment->count_new_user;
            $datas[$key]['duplicate_user'] = $quickSegment->count_duplicate_user;
            $datas[$key]['date_import'] = ($quickSegment->quickSegmentItem && $quickSegment->quickSegmentItem->first())? $quickSegment->quickSegmentItem->first()->created_at->format('Y-m-d H:i:s') : '-';
        }


        return response()->json([
            'datas' => $datas,
        ]);
    }

    public function exportQuickSegment($id)
    {
        $datas = [];
        $dataExports = [];
        $quickSegment = QuickSegment::find($id);
        $quickSegmentItemsLineUserIds = $quickSegment->quickSegmentItem->pluck('line_user_id')->toArray();
        $subscriberMasters = Subscriber::where('is_master',1)->pluck('id')->toArray();
        $subscriberDatas = SubscriberLine::whereIn('subscriber_id',$subscriberMasters)->whereIn('line_user_id',$quickSegmentItemsLineUserIds)->get()->groupBy('line_user_id');
        $count = 1;

        foreach ($subscriberDatas as $idKey => $subscribers) {
            $datas[$idKey]['No.'] = $count;
            $datas[$idKey]['userID'] = $idKey;
            foreach ($subscribers as $subscriber) {
                foreach ($subscriber->subscriberItems as $subscriberItem) {
                    $datas[$idKey][$subscriberItem->field->name] = "'".$subscriberItem->value;
                }
                $datas[$idKey]['created_at_'.$subscriber->subscriber->name] = $subscriber->created_at->format('Y-m-d H:i:s');
            }
            $count++;
        }

        if(count($datas) > 0){
            array_multisort(array_map('count', $datas), SORT_DESC, $datas);
            $allKeys = array_keys($datas[0]);

            foreach ($datas as $index => $data) {
                foreach ($allKeys as $key) {
                    if(!array_key_exists($key, $data)){
                        $dataExports[$index][$key] = "N/A";
                    }else{
                        $dataExports[$index][$key] = $data[$key];
                    }
                }
                $dataExports[$index]['No.'] = $index+1;
            }
        }

        $dateNow = Carbon::now()->format('dmY_His');

        Excel::create('segment_data_'.$dateNow, function($excel) use ($dataExports) {
            $excel->sheet('subscriber_data', function($sheet) use ($dataExports)
            {
                $sheet->fromArray($dataExports);
            });
        })->download('xls');
    }

    public function getQuickSegmentName()
    {
        $datas = [];
        $quickSegments = QuickSegment::all();
        foreach ($quickSegments as $key => $quickSegment) {
            $datas[$key]['id'] = $quickSegment->id;
            $datas[$key]['name'] = $quickSegment->name;
        }

        return response()->json([
            'datas' => $datas,
        ]);
    }
}
