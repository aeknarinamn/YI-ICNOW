<?php

namespace YellowProject\Http\Controllers\API\v1\Subscriber;

use Illuminate\Http\Request;
use YellowProject\Http\Controllers\Controller;
use YellowProject\Subscriber;
use YellowProject\SubscriberLine;
use YellowProject\LineUserProfile;
use YellowProject\Field;
use YellowProject\FieldFolder;

class MasterSubscriberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $subscribers = Subscriber::where('is_master',1)->get();
        foreach ($subscribers as $key => $subscriber) {
            $subscriberFolder = $subscriber->folder;
        }
        return response()->json([
            'datas' => $subscribers,
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
        $subscriberSearch = Subscriber::where('is_master',1)->where('name',$request->name)->first();
        if($subscriberSearch){
          return response()->json([
              'msg_return' => 'ชื่อซ้ำกัน',
              'code_return' => 2,
          ]);
        }
        $subscriber = Subscriber::create($request->all());
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
        $subscriber = Subscriber::find($id);
        $subscriberFolder = $subscriber->folder;
        return response()->json([
            'datas' => $subscriber,
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
        $subscriber = Subscriber::find($id);
        $subscriberSearch = Subscriber::where('is_master',1)->where('name',$request->name)->first();
        if($subscriberSearch && $request->name != $subscriber->name){
          return response()->json([
              'msg_return' => 'ชื่อซ้ำกัน',
              'code_return' => 2,
          ]);
        }
        $subscriber->update($request->all());
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
        $subscriber = Subscriber::find($id);
        if($subscriber->fields->count() > 0){
          return response()->json([
              'msg_return' => 'Subscriber used by fields.',
              'code_return' => 2,
          ]);
        }else{
          $subscriber->delete();
          return response()->json([
              'msg_return' => 'บันทึกสำเร็จ',
              'code_return' => 1,
          ]);
        }
    }

    public function getFieldMasterSubscriber()
    {
        $fieldFolder = FieldFolder::where('name','Field Master Subscriber')->first();
        $fields = Field::where('folder_id',$fieldFolder->id)->get();

        return response()->json([
            'datas' => $fields,
        ]);
    }
}
