<?php

namespace YellowProject\Http\Controllers\API\v1;

use Illuminate\Http\Request;
use YellowProject\Http\Controllers\Controller;
use YellowProject\Location;
use YellowProject\LocationItem;
use YellowProject\LocationKeyword;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $locations = Location::all();
        return response()->json([
            'datas' => $locations,
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
        //Create Empty Locationdata Arrays
        $locationDatas = [];
        // Set Location Data
        $locationDatas['name'] = $request['name'];
        $locationDatas['description'] = $request['description'];
        // Save Location
        $location = Location::create($locationDatas);
        if($request->locationItemDatas){
            foreach ($request->locationItemDatas as $locationItemData) {
                $locationItemData['location_id'] = $location->id;
                $locationItem = LocationItem::create($locationItemData);
                if (array_key_exists('keyword', $locationItemData)) {
                    $locationKeywords = explode(',', $locationItemData['keyword']);
                    foreach ($locationKeywords as $locationKeywordData) {
                        $locationKeywordDatas['location_id'] = $locationItem->id;
                        $locationKeywordDatas['keyword'] = $locationKeywordData;
                        LocationKeyword::create($locationKeywordDatas);
                    }
                }
            }
        }

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
        $location = Location::find($id);
        $locationItems = $location->locationItems;
        return response()->json([
            'dataLocations' => $location,
            'dataLocationItems' => $locationItems,
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
        //Create Empty Locationdata Arrays
        $locationDatas = [];
        // Set Location Data
        $locationDatas['name'] = $request['name'];
        $locationDatas['description'] = $request['description'];
        $location = Location::find($id);
        $location->update($locationDatas);
        if($request->locationItemDatas){
            foreach ($request->locationItemDatas as $locationItemData) {
                $locationItem = LocationItem::where('location_name',$locationItemData['location_name'])->first();
                $locationKeywordArrays = [];
                $locationKeywordArrays = LocationKeyword::where('location_id',$locationItem->id)->pluck('keyword')->toArray();
                $locationItemData['location_id'] = $location->id;
                if($locationItem){
                    $locationItem->update($locationItemData);
                    if (array_key_exists('keyword', $locationItemData)) {
                        $locationKeywords = explode(',', $locationItemData['keyword']);
                        foreach ($locationKeywords as $locationKeywordData) {
                            if (in_array($locationKeywordData, $locationKeywordArrays)) {
                                if(($key = array_search($locationKeywordData, $locationKeywordArrays)) !== false) {
                                    unset($locationKeywordArrays[$key]);
                                }
                                // dd($locationKeywordArrays);
                            }else{
                                // dd($locationKeywordArrays);
                                // dd(in_array($locationKeywordData, $locationKeywordArrays));
                                $locationKeywordDatas['location_id'] = $locationItem->id;
                                $locationKeywordDatas['keyword'] = $locationKeywordData;
                                LocationKeyword::create($locationKeywordDatas);
                            }
                            
                        }
                    }
                    LocationKeyword::where('location_id',$locationItem->id)->whereIn('keyword',$locationKeywordArrays)->delete();
                }else{
                    LocationItem::create($locationItemData);
                    if (array_key_exists('keyword', $locationItemData)) {
                        $locationKeywords = explode(',', $locationItemData['keyword']);
                        foreach ($locationKeywords as $locationKeywordData) {
                            $locationKeywordDatas['location_id'] = $locationItem->id;
                            $locationKeywordDatas['keyword'] = $locationKeywordData;
                            LocationKeyword::create($locationKeywordDatas);
                        }
                    }
                }
            }
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
        $location = Location::find($id);
        $locationItems = \YellowProject\LocationItem::where('location_id',$location->id)->get();
        foreach ($locationItems as $locationItem) {
            \YellowProject\LocationKeyword::where('location_id',$locationItem->id)->forceDelete();
        }
        \YellowProject\LocationItem::where('location_id',$location->id)->forceDelete();
        $location->forceDelete();
        return response()->json([
            'msg_return' => 'ลบสำเร็จ',
            'code_return' => 1,
        ]);
    }
}
