<?php

namespace YellowProject\Http\Controllers\API\v1;

use Illuminate\Http\Request;
use YellowProject\Http\Controllers\Controller;
use YellowProject\SubscriberFolder;

class SubscriberFolderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $subscriberFolders = SubscriberFolder::all();
        return response()->json([
            'datas' => $subscriberFolders,
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
        $subscriberFolder = SubscriberFolder::create($request->all());
        // print_r('after save');
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
        $subscriberFolder = SubscriberFolder::find($id);
        return response()->json([
            'datas' => $subscriberFolder,
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
        $subscriberFolder = SubscriberFolder::find($id);
        $subscriberFolder->update($request->all());
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
        $subscriberFolder = SubscriberFolder::find($id);
        $subscriberFolder->delete();
        return response()->json([
            'msg_return' => 'ลบสำเร็จ',
            'code_return' => 1,
        ]);
    }
}
