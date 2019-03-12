<?php

namespace YellowProject\Http\Controllers\API\v1\ICNOW\Mini;

use Illuminate\Http\Request;
use YellowProject\Http\Controllers\Controller;
use YellowProject\ICNOW\Mini\Mini;
use Excel;

class MiniController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filters = $request->filter_items;
        $dtCode = $filters['dt_code'];
        $dtName = $filters['dt_name'];
        $miniCode = $filters['mini_code'];
        $miniName = $filters['mini_name'];
        $wallsCode = $filters['walls_code'];
        $wallsName = $filters['walls_name'];
        $status = $filters['status'];
        $minis = Mini::select(
            'id',
            'dt_code',
            'dt_name',
            'mini_code',
            'mini_name',
            'walls_code',
            'walls_name',
            'is_active'
        );
        if($dtCode != ""){
            $minis = $minis->where('dt_code','like','%'.$dtCode.'%');
        }
        if($dtName != ""){
            $minis = $minis->where('dt_name','like','%'.$dtName.'%');
        }
        if($miniCode != ""){
            $minis = $minis->where('mini_code','like','%'.$miniCode.'%');
        }
        if($miniName != ""){
            $minis = $minis->where('mini_name','like','%'.$miniName.'%');
        }
        if($wallsCode != ""){
            $minis = $minis->where('walls_code','like','%'.$wallsCode.'%');
        }
        if($wallsName != ""){
            $minis = $minis->where('walls_name','like','%'.$wallsName.'%');
        }
        if($status != ""){
            $minis = $minis->where('is_active',$status);
        }
        $minis = $minis->get();

        return response()->json([
            'datas' => $minis,
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
        $datas = $request->datas;
        $loginUrl = \URL::to('/')."/dt".$datas['dt_code'];
        $datas['login_url'] = $loginUrl;
        $mini = Mini::create($datas);
        
        return response()->json([
            'msg_return' => 'บันทึกสำเร็จ',
            'code_return' => 1,
            'id' => $mini->id
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
        $mini = Mini::find($id);

        return response()->json([
            'datas' => $mini,
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
        $datas = $request->datas;
        $mini = Mini::find($id);
        $mini->update($datas);

        return response()->json([
            'msg_return' => 'บันทึกสำเร็จ',
            'code_return' => 1,
            'id' => $mini->id
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
        $mini = Mini::find($id);
        $mini->delete();

        return response()->json([
            'msg_return' => 'ลบข้อมูลสำเร็จ',
            'code_return' => 1,
        ]);
    }

    public function importData(Request $request)
    {

        // $datas = $request->datas;
        // foreach ($datas as $key => $data) {
        //     $mini = Mini::where('dt_code',$data['dt_code'])->first();
        //     if($mini){
        //         $mini->update($data);
        //     }else{
        //         $loginUrl = \URL::to('/')."/dt".$data['dt_code'];
        //         $data['login_url'] = $loginUrl;
        //         Mini::create($data);
        //     }
            
        // }

        $results = Excel::load($request->file_item, function($reader) {

        })->all()->toArray();

        foreach ($results as $key => $result) {
            $result['is_active'] = 1;
            $mini = Mini::where('mini_code',$result['mini_code'])->first();
            if($mini){
                $mini->update($result);
            }else{
                Mini::create($result);
            }
        }

        return response()->json([
            'msg_return' => 'บันทึกสำเร็จ',
            'code_return' => 1,
        ]);
    }
}
