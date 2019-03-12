<?php

namespace YellowProject\Http\Controllers\API\v1;

use Illuminate\Http\Request;
use YellowProject\Http\Controllers\Controller;
use YellowProject\LineSettingBusiness;

class LineSettingBusinessController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lineSettingBussiness = LineSettingBusiness::all();
        return response()->json([
            'datas' => $lineSettingBussiness,
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
        $lineSettingBussiness = LineSettingBusiness::create($request->all());
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
        $lineSettingBussiness = LineSettingBusiness::find($id);
        return response()->json([
            'datas' => $lineSettingBussiness,
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
        $lineSettingBussiness = LineSettingBusiness::find($id);
        $lineSettingBussiness->update($request->all());
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
        $lineSettingBussiness = LineSettingBusiness::find($id);
        $lineSettingBussiness->delete();
        return response()->json([
            'msg_return' => 'ลบสำเร็จ',
            'code_return' => 1,
        ]);
    }


    public function postIssueToken(Request $request, $id)
    {
        $lineSettingBussiness = LineSettingBusiness::find($id);
        if (!is_null($lineSettingBussiness)) {
            $rst  = LineSettingBusiness::issueToken($lineSettingBussiness);
            if ($rst) {
                return json_encode([
                    'msg_return' => 'IssueToken สำเร็จ',
                    'code_return' => 1,
                ], JSON_UNESCAPED_UNICODE);
            } else {
                return json_encode([
                    'msg_return' => 'IssueToken ไม่สำเร็จ',
                    'code_return' => 1,
                ], JSON_UNESCAPED_UNICODE);
            }
           
        } else {

            return json_encode([
                    'msg_return' => 'ไม่พบข้อมูล',
                    'code_return' => 1,
                ], JSON_UNESCAPED_UNICODE);
        }
        
    }
}
