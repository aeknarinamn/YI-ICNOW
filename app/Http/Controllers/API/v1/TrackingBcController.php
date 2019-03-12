<?php

namespace YellowProject\Http\Controllers\API\v1;

use Illuminate\Http\Request;
use YellowProject\Http\Controllers\Controller;
use YellowProject\TrackingBc;

class TrackingBcController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $trackingBcs = TrackingBc::orderBy('updated_at','desc')->get();
        foreach ($trackingBcs as $key => $trackingBc) {
            // $convert = trim(preg_replace('/\s\s+/', ' ', $trackingBc->desc));
            // $convert = strip_tags($convert);
            $convert = $trackingBc->desc;
            $length = strlen($convert);
            if($length >= 20){
              $convert = mb_substr($convert,0,20).'...';
              // $convert =  mb_convert_encoding($convert, 'UTF-8', 'UTF-8');
            }
            $trackingBc->desc = $convert;
        }
        return response()->json([
            'datas' => $trackingBcs,
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
        if(isset($request->code) && $request->code != ""){
            $trackingBcSearch = TrackingBc::where('code',$request->code)->first();
            if($trackingBcSearch){
              return response()->json([
                  'msg_return' => 'ชื่อ Tracking ซ้ำกัน',
                  'code_return' => 21,
              ]);
            }
        }
        $baseUrl = \URL::to('/');
        if(!$request->is_route_name){
            $request['code'] = TrackingBc::generateCode();
        }
        $request['generated_short_url'] = $baseUrl."/bc/".$request['code'];
        $request['generated_full_url'] = $baseUrl."/bc/".$request['code']."?"."yl_source=".$request->tracking_source."&yl_campaign=".$request->tracking_campaign."&yl_ref=".$request->tracking_ref;
        $trackingBc = TrackingBc::create($request->all());
        return response()->json([
            'msg_return' => 'บันทึกสำเร็จ',
            'code_return' => 1,
            'datas' => $trackingBc
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
        $trackingBc = TrackingBc::find($id);

        return response()->json([
            'datas' => $trackingBc,
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
        $baseUrl = \URL::to('/');
        $trackingBc = TrackingBc::find($id);
        if(isset($request->code) && $request->code != "" && $trackingBc->code != $request->code){
            $trackingBcSearch = TrackingBc::where('code',$request->code)->first();
            if($trackingBcSearch){
              return response()->json([
                  'msg_return' => 'ชื่อ Tracking ซ้ำกัน',
                  'code_return' => 21,
              ]);
            }
        }
        $request['generated_short_url'] = $baseUrl."/bc/".$request['code'];
        $request['generated_full_url'] = $baseUrl."/bc/".$request['code']."?"."yl_source=".$request->tracking_source."&yl_campaign=".$request->tracking_campaign."&yl_ref=".$request->tracking_ref;
        $trackingBc->update($request->all());

        return response()->json([
            'msg_return' => 'บันทึกสำเร็จ',
            'code_return' => 1,
            'datas' => $trackingBc
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
        $trackingBc = TrackingBc::find($id);
        $trackingBc->delete();
        return response()->json([
            'msg_return' => 'บันทึกสำเร็จ',
            'code_return' => 1,
        ]);
    }
}
