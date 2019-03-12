<?php

namespace YellowProject\Http\Controllers\Report;

use Illuminate\Http\Request;

use YellowProject\Http\Requests;
use YellowProject\Http\Controllers\Controller;
use YellowProject\RecieveMessage;
use Carbon\Carbon;
use Excel;

class ReportAutoReplyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $datas = collect();
        $recieveMessages = RecieveMessage::orderBy('created_at','desc')->get();
        foreach ($recieveMessages->unique('keyword') as $key => $recieveMessage) {
            $recieveKeyword = RecieveMessage::where('keyword',$recieveMessage->keyword)->get();
            // $datas = collect[(
            //     'keyword' => $recieveMessage->keyword,
            //     'count'   => number_format($recieveKeyword->count()),
            //     'date_time' => $recieveMessage->created_at->format('d/m/Y H:i'),
            // )];
            // $recieveDatas[$key]['keyword'] = $recieveMessage->keyword;
            // $recieveDatas[$key]['count'] = number_format($recieveKeyword->count());
            // $recieveDatas[$key]['date_time'] = $recieveMessage->created_at->format('d/m/Y H:i');
            $datas->push([
                'keyword' => $recieveMessage->keyword,
                'count'   => number_format($recieveKeyword->count()),
                'date_time' => $recieveMessage->created_at->format('d-m-Y H:i'),
            ]);
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

    public function download(Request $request)
    {
        // dd($request->all());
        // $dateRange = explode('-', $request->date_range);
        // dd($dateRange);
        // $fromdate = trim($dateRange['0']);
        // $fromdate = RecieveMessage::convertDate($fromdate);
        // $todate = trim($dateRange['1']);
        // $todate = RecieveMessage::convertDate($todate);
        // $recieveMessages = RecieveMessage::whereBetween('created_at', array($fromdate, $todate))->get();
        $recieveMessages = RecieveMessage::all();
        $datas = [];
        foreach ($recieveMessages as $key => $recieveMessage) {
            $datas[] = array(
                 'keyword'      => "'".$recieveMessage->keyword,
                 'Timestamp'    => $recieveMessage->created_at->format('d/m/Y H:i'),
            );
        }

        Excel::create('auto_reply', function($excel) use ($datas) {
            $excel->sheet('auto_reply', function($sheet) use ($datas)
            {
                $sheet->fromArray($datas);
            });
        })->download('csv');

        // Excel::create('auto_reply'.$fromdate."-".$todate, function($excel) use ($datas) {
        //     $excel->sheet('auto_reply', function($sheet) use ($datas)
        //     {
        //         $sheet->fromArray($datas);
        //     });
        // })->download('csv');
        // dd($sendTaxcer);
    }
}
