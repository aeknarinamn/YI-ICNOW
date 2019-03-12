<?php

namespace YellowProject\Http\Controllers;

use Illuminate\Http\Request;
use YellowProject\Campaign;
use YellowProject\MessageFolder;
// use YellowProject\MessageFolder;

class CampaignController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // dd($request->toArray());
        $campaigns = Campaign::where('campaign_type','campaign')->with('messages');
        dd($campaigns);
        // $messageFolders = MessageFolder::orderBy('name');
        //if (sizeof($request->input('name'))) $campaigns->where('name', 'like', '%'.$request->input('name').'%');
        if (sizeof($request->input('name'))) $campaigns->where('name', 'like', '%'.$request->input('name').'%');
        if ($request->input('messageFolderID') != ""){
            $campaigns->where('message_folder_id',$request->input('messageFolderID'));
            $messageFolderID = $request->input('messageFolderID');
        }
        if ($request->input('deleted')) $campaigns->onlyTrashed();
        dd($campaigns);
        // return json_encode(collect($campaigns)->toArray());

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //                     
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
    public function edit(Request $request,$id)
    {
        $id = 1;
        $request = array(
        'name'=>'Nuy Tet Pro Ject',
        'send_status'=>'schedule',
        'schedule_type'=>'one_time',
        'monthly_on'=>'first',
        'segment_id'=>'1',
        'message_folder_id'=>'1',
        'report_tag_id' => '',
        'company_id'=>'1',
        'campaignName'=>'Nuy Tet Pro Ject',
        'campaign_type'=> 'campaign',
        'recurring_sent_date' => array('one_time_start_date'=>'-','recurring_end_date'=>'-'),
        'message_type'=> array('1'=>'text','2'=>'sticker','3'=>'photo'),
        'payload'=>array(1=>'Test Message 1',array('stkID'=>'110','stkPKGID'=>'1','stkVer'=>'100','pathSticker'=>'img/sticker/1_Moon_James/110.png')),
        'order_id'=>array('1'=>'1','2'=>'2','3'=>'3'),
        'photo'=>array('3'=>'Testimg',
          'campaign_type'=>'campaign'),
        );
        update($request,$id);
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
        dd($request->all());
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

    public function get($request)
    {
        dd($request);
    }
}
