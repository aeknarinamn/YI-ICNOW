<?php

namespace YellowProject\Http\Controllers;

use Illuminate\Http\Request;

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
       $lineSettingBusiness  = LineSettingBusiness::all();
       $resturnDatas  = collect();
       foreach ($lineSettingBusiness as $lineSettingBusines) {
            $resturnDatas->push(collect($lineSettingBusines)->except(['channel_secret', 'channel_access_token']));
       }
       
       return $resturnDatas->toJson();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        dd($request->all());

         return 'create';
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $arrErrsField = array();
        $colectErrsField= collect();
        if (!isset($request->channel_id) || (isset($request->channel_id) && trim($request->channel_id) == "") ) {
            $arrErrsField[] = 'channel_id';
        }
        if (!isset($request->channel_secret) || (isset($request->channel_secret) && trim($request->channel_secret) == "") ) {
            $arrErrsField[] = 'channel_secret';
        }
        if (!isset($request->channel_access_token) || (isset($request->channel_access_token) && trim($request->channel_access_token) == "") ) {
            $arrErrsField[] = 'channel_access_token';
        } 
        if (!isset($request->name) || (isset($request->name) && trim($request->name) == "") ) {
            $arrErrsField[] = 'name';
        }
        if (sizeof($arrErrsField) > 0) {
            $colectErrsField = $colectErrsField->push([
                'VALID_REQUIRE' => $arrErrsField
            ]);
            return $colectErrsField->toJson();
        }
       

        //Validat ซ้ำ channel_id
        $dup = LineSettingBusiness::checkDuplicate($request->channel_id);
        if ($dup) {
           $colectErrsField = $colectErrsField->push([
                'ERR_DUPPLICATE' => [
                    'channel_id'
                ]
            ]); 

           return $colectErrsField;
        }


        $lineSettingBusiness = LineSettingBusiness::firstOrNew([
            'active'                => false,
            'channel_id'            => $request->channel_id,
            'channel_secret'        => $request->channel_secret,
            'channel_access_token'  => $request->channel_access_token,
            'name'                  => $request->name
        ]);

        if ($lineSettingBusiness->exists) {
            $arrErrsField[] = 'channel_id';
            $arrErrsField[] = 'channel_secret';
            $arrErrsField[] = 'channel_access_token';
            $arrErrsField[] = 'name';

            $colectErrsField = $colectErrsField->push([
                'ERR_DUPPLICATE' => $arrErrsField
            ]);

            return $colectErrsField->toJson();

        } else {
            // lineSettingBusiness created from 'new'; does not exist in database.
            $lineSettingBusiness->save();

            return json_encode(["INSERT_SUCCESS"]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $lineSettingBusiness  = LineSettingBusiness::find($id);

        if ($lineSettingBusiness) {

           $resturnDatas  = collect();
           foreach ($lineSettingBusiness as $lineSettingBusines) {
                $resturnDatas->push(collect($lineSettingBusines));
           }

           return $resturnDatas->toJson();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return 'edit';
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
        $message ="";
        $lineSettingBusiness  = LineSettingBusiness::find($id);

        if ($lineSettingBusiness) {
            $lineSettingBusiness->update([
                'active'                => $request->active,
                'channel_id'            => $request->channel_id,
                'channel_secret'        => $request->channel_secret,
                'channel_access_token'  => $request->channel_access_token,
                'name'                  => $request->name
            ]);
            $message=  "UPDATE_SUCCESS";
        } else {
            $message=  "ERR_NOT_FOUND";
        }
        return json_encode([$message]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //DELETE_SUCCESS
        $message ="";
        $lineSettingBusiness  = LineSettingBusiness::find($id);

        if ($lineSettingBusiness) {
            if ($lineSettingBusiness->active) {
                $message=  "ERR_DATA_IS_USED";
            } else {
                $message=  "DELETE_SUCCESS";
            }
        } else {
            $message=  "ERR_NOT_FOUND";
        }
        return json_encode([$message]);
    }

    
}
