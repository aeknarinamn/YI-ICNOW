<?php

namespace YellowProject\Http\Controllers\API\v1;

use Illuminate\Http\Request;
use YellowProject\Http\Controllers\Controller;
use YellowProject\KeywordFolder;

class AutoReplyKeywordFolderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $keywordFolders = KeywordFolder::all();
        // return $keywordFolders;
        return response()->json([
            'datas' => $keywordFolders,
            'countAutoReply' => 10
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // return response()->json([
        //     'name' => 'Sam',
        //     'state' => 'create'
        // ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // print_r('before save');
        $keywordFolder = KeywordFolder::create($request->all());
        // print_r('after save');
        return response()->json([
            'msg_return' => 'บันทึกสำเร็จ',
            'code_return' => 1,
        ]);
        // return response()->json([
        //     'name' => 'Sam',
        //     'state' => 'store',
        //     'value' => $request->all()
        // ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $keywordFolder = KeywordFolder::find($id);
        return response()->json([
            'datas' => $keywordFolder,
        ]);
        // return response()->json([
        //     'name' => 'Sam',
        //     'state' => 'show'
        // ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $keywordFolder = KeywordFolder::find($id);
        // return response()->json([
        //     'name' => 'Sam',
        //     'state' => 'edit'
        // ]);
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
        $keywordFolder = KeywordFolder::find($id);
        $keywordFolder->update($request->all());
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
        $keywordFolder = KeywordFolder::find($id);
        $keywordFolder->delete();
        return response()->json([
            'msg_return' => 'ลบสำเร็จ',
            'code_return' => 1,
        ]);
    }
}
