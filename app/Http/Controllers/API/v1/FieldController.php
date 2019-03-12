<?php

namespace YellowProject\Http\Controllers\api\v1;

use Illuminate\Http\Request;
use YellowProject\Http\Controllers\Controller;
use YellowProject\Field;
use YellowProject\FieldItem;
use YellowProject\Subscriber;
use YellowProject\SubscriberItem;

class FieldController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $fields = Field::whereHas('subscriber', function ($query) {
            $query->where('is_master',0);
        })->get();
        foreach ($fields as $key => $field) {
            $fieldFolder = $field->folder;
            $fieldSubScriber = $field->subscriber;
            $fieldProfillingAction = $field->profillingActions->first();
            if($fieldProfillingAction){
                $profilling = $fieldProfillingAction->profilling;
            }
        }
        return response()->json([
            'datas' => $fields,
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
        // dd($request->all());
        // $request['subscriber_id'] = 1;
        $fieldSearch = Field::where('name',$request->name)->first();
        if($fieldSearch){
          return response()->json([
              'msg_return' => 'ชื่อซ้ำกัน',
              'code_return' => 2,
          ]);
        }
        $field = Field::create($request->all());
        if($request->type == 'enum_dropdown' || $request->type == 'enum_radio'){
            //Field Item
            foreach ($request->enumDatas as $data) {
                FieldItem::create([
                    'dim_fields_id' => $field->id,
                    'value'         => $data,
                ]);
            }
        }

        return response()->json([
            'msg_return' => 'บันทึกสำเร็จ',
            'code_return' => 1,
            'field_id' => $field->id
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
        $field = Field::find($id);
        $fieldFolder = $field->folder;
        $fieldProfillingAction = $field->profillingActions->first();
        if($fieldProfillingAction){
            $profilling = $fieldProfillingAction->profilling;
        }
        $fieldItems = $field->fieldItems;
        $countUseField = SubscriberItem::where('field_id',$field->id)->count();
        return response()->json([
            'datas' => $field,
            'count_use_field' => $countUseField,
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
        $field = Field::find($id);
        $fieldSearch = Field::where('name',$request->name)->first();
        if($fieldSearch && $request->name != $field->name){
          return response()->json([
              'msg_return' => 'ชื่อซ้ำกัน',
              'code_return' => 2,
          ]);
        }
        $field->update($request->all());
        $fieldItemIDs = $field->fieldItems->pluck('id');
        if($request->type == 'enum_dropdown' || $request->type == 'enum_radio'){
            foreach ($request->enumDatas as $index => $data) {
                $seachIndex = (string)$fieldItemIDs->search($index);
                if($seachIndex != ""){
                    $fieldItemUpdate = FieldItem::find($index);
                    $fieldItemUpdate->value = $data;
                    $fieldItemUpdate->save();
                    // reject id fieldItemIDs after update
                    $fieldItemIDs = $fieldItemIDs->reject(function($value, $key) use ($index) {
                        return $value == $index;
                    });
                }else{
                    $fieldItemStore = FieldItem::create([
                        'dim_fields_id' => $id,
                        'value'         => $data,
                    ]);
                }
            }

            FieldItem::whereIn('id', $fieldItemIDs)->delete();
        }
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
        $field = Field::find($id);
        $fieldItems = FieldItem::where('dim_fields_id',$id);
        if($fieldItems->count() > 0){
            $fieldItems->delete();
        }
        $field->delete();
        return response()->json([
            'msg_return' => 'ลบสำเร็จ',
            'code_return' => 1,
        ]);
    }

    public function getMasterSubscriberField()
    {
        $datas = [];
        $masterSubscriber = Subscriber::where('name','Master Subscriber')->first();
        $fieldMasterSusbcribers = $masterSubscriber->fields;
        foreach ($fieldMasterSusbcribers as $key => $fieldMasterSusbcriber) {
            $datas[$key]['field_id'] = $fieldMasterSusbcriber->id;
            $datas[$key]['field_name'] = $fieldMasterSusbcriber->name;
        }

        return response()->json([
            'datas' => $datas,
        ]);
    }
}
