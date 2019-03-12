<?php

namespace YellowProject\Http\Controllers\API\v1\Photo;

use Illuminate\Http\Request;
use YellowProject\Http\Controllers\Controller;
use YellowProject\Photo\CampaignPhoto;
use Carbon\Carbon;
use URL;

class CampaignPhotoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        // dd($request->all());
        $dateNow = Carbon::now()->format('dmY_His');
        $fileImage = $request->file_img;
        CampaignPhoto::checkFolderDefaultPath();
        $destinationPath = 'campaign_file/photo';
        $extension = $fileImage->getClientOriginalExtension(); // getting image extension
        // $fileName = rand(111111,999999).'.'.$extension; // renameing image
        $fileName = $dateNow.'.'.$extension; // renameing image
        $fileImage->move($destinationPath, $fileName); // uploading file to given path

        CampaignPhoto::create([
            'img_url' => URL::to('/')."/".$destinationPath."/".$fileName,
        ]);

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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
