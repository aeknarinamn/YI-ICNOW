<?php

namespace YellowProject\Http\Controllers\Report;

use Illuminate\Http\Request;

use YellowProject\Http\Requests;
use YellowProject\Http\Controllers\Controller;
use YellowProject\LineBizConCustomer;
use Excel;

class ReportNewRegisterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $lineBizconCustomers = LineBizConCustomer::orderBy('created_at','desc')->get();
        $groupDatelineBizconCustomers = $lineBizconCustomers->groupBy(function($item){ return $item->created_at->format('d/m/Y'); });
        $datas = collect();
        foreach ($groupDatelineBizconCustomers as $date => $groupDatelineBizconCustomer){
            $datas->push([
                'report_name' => 'Register_'.$date,
                'total_counts'   => $groupDatelineBizconCustomer->count(),
                'date_form_download' => $groupDatelineBizconCustomer->first()->created_at->format('Y-m-d'),
            ]);
        };
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
        $lineBizconCustomers = LineBizConCustomer::whereDate('created_at', '=', $request->date_form_download)->get();
        $datas = [];
        foreach ($lineBizconCustomers as $key => $lineBizconCustomer) {
            $datas[] = array(
                 // 'Customer Client Id' => $lineBizconCustomer->client_no,
                 // 'mID' => $lineBizconCustomer->mid,
                 'Email' => $lineBizconCustomer->email,
                 'Name' => $lineBizconCustomer->name,
                 'Last Name' => $lineBizconCustomer->last_name,
                 'Phone Number' => "'".$lineBizconCustomer->phone_number,
                 'Timestamp' => $lineBizconCustomer->created_at->format('d/m/Y h:i'),
            );
        }

        Excel::create('new-register'.$request->date_form_download, function($excel) use ($datas) {
            $excel->sheet('new_register', function($sheet) use ($datas)
            {
                $sheet->fromArray($datas);
            });
        })->download('csv');
        // dd($sendTaxcer);
    }
}
