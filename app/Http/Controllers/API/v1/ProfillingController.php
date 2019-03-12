<?php

namespace YellowProject\Http\Controllers\API\v1;

use Illuminate\Http\Request;
use YellowProject\Http\Controllers\Controller;
use YellowProject\Profilling;
use YellowProject\ProfillingAction;
use YellowProject\ProfillingActionCss;
use YellowProject\ProfillingActionParentCss;
use YellowProject\ProfillingActionSetting;
use YellowProject\ProfillingActionLabelCss;

class ProfillingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $profillings = Profilling::orderBy('updated_at','desc')->get();
        foreach ($profillings as $key => $profilling) {
          $subscriber = $profilling->subscriber;
          $profillingFolder = $profilling->profillingFolder;
        }
        return response()->json([
            'datas' => $profillings,
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
        $ProfillingSearchRoute = Profilling::where('route',$request->route)->first();
        $ProfillingSearchName = Profilling::where('lead_form_name',$request->lead_form_name)->first();
        if($ProfillingSearchRoute){
          return response()->json([
              'msg_return' => 'ข้อมูล route ซ้ำ',
              'code_return' => 2,
          ]);
        }
        if($ProfillingSearchName){
          return response()->json([
              'msg_return' => 'ข้อมูล Lead Form Name ซ้ำ',
              'code_return' => 2,
          ]);
        }
        $Profilling = Profilling::create($request->all());
        // $Profilling = Profilling::find(1);
        if (array_key_exists('items', $request->all())) {
          foreach ($request['items'] as $items) {
            $items['profilling_id'] = $Profilling->id;
            $ProfillingAction = ProfillingAction::create($items);
            if (array_key_exists('css', $items)) {
              foreach ($items['css'] as $css) {
                $css['profilling_action_id'] = $ProfillingAction->pri_id;
                ProfillingActionCss::create($css);
              }
            }
            if (array_key_exists('label_css', $items)) {
              foreach ($items['label_css'] as $labelCss) {
                $labelCss['profilling_action_id'] = $ProfillingAction->pri_id;
                ProfillingActionLabelCss::create($labelCss);
              }
            }
            if (array_key_exists('parent_css', $items)) {
              foreach ($items['parent_css'] as $parentCss) {
                $parentCss['profilling_action_id'] = $ProfillingAction->pri_id;
                ProfillingActionParentCss::create($parentCss);
              }
            }
            if (array_key_exists('setting', $items)) {
              foreach ($items['setting'] as $setting) {
                $setting['profilling_action_id'] = $ProfillingAction->pri_id;
                ProfillingActionSetting::create($setting);
              }
            }
          }
        }
        return response()->json([
            'msg_return' => 'บันทึกสำเร็จ',
            'code_return' => 1,
            'id' => $Profilling->id
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
        $response = 0;
        $dataArrays = [];
        $Profilling = Profilling::find($id);
        $Profilling->subscriber;
        $Profilling->profillingFolder;
        if($Profilling){
          $response = 1;
          $dataArrays[] = $Profilling->toArray();
          $ProfillingActions = $Profilling->ProfillingActions;
          foreach ($ProfillingActions as $key => $ProfillingAction) {
            $ProfillingAction->field;
            $dataArrays[0]['items'][$key] = $ProfillingAction->toArray();
            $ProfillingActionsCss = $ProfillingAction->css;
            $dataArrays[0]['items'][$key]['css'] = ($ProfillingActionsCss->count() > 0)? $ProfillingActionsCss->toArray() : array();

            $ProfillingActionsLabelCss = $ProfillingAction->Labelcss;
            $dataArrays[0]['items'][$key]['label_css'] = ($ProfillingActionsLabelCss->count() > 0)? $ProfillingActionsLabelCss->toArray() : array();
            
            $ProfillingActionsParentCss = $ProfillingAction->parentCss;
            $dataArrays[0]['items'][$key]['parent_css'] = ($ProfillingActionsParentCss->count() > 0)? $ProfillingActionsParentCss->toArray() : array();
            
            $ProfillingActionsSetting = $ProfillingAction->settings;
            $dataArrays[0]['items'][$key]['setting'] = ($ProfillingActionsSetting->count() > 0)? $ProfillingActionsSetting->toArray() : array();
          }
        }
        return response()->json([
            'datas' => $dataArrays,
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
        // dd($request->all());
        $Profilling = Profilling::find($id);

        $ProfillingSearchRoute = Profilling::where('route',$request->route)->first();
        $ProfillingSearchName = Profilling::where('lead_form_name',$request->lead_form_name)->first();
        if($ProfillingSearchRoute && $request->route != $Profilling->route){
          return response()->json([
              'msg_return' => 'ข้อมูล route ซ้ำ',
              'code_return' => 2,
          ]);
        }
        if($ProfillingSearchName && $request->lead_form_name != $Profilling->lead_form_name){
          return response()->json([
              'msg_return' => 'ข้อมูล Lead Form Name ซ้ำ',
              'code_return' => 2,
          ]);
        }

        $ProfillingActionIDs = $Profilling->ProfillingActions->pluck('pri_id')->toArray();
        $Profilling->update($request->all());
        if (array_key_exists('items', $request->all())) {
          foreach ($request['items'] as $items) {
            if($items['pri_id'] != 0){
              if(($key = array_search($items['pri_id'], $ProfillingActionIDs)) !== false) {
                  unset($ProfillingActionIDs[$key]);
              }
              // $ProfillingAction = ProfillingAction::find($items['pri_id']);
              $ProfillingAction = ProfillingAction::where('pri_id',$items['pri_id'])->first();
              $ProfillingAction->update($items);
              $ProfillingActionCssIDs = ($ProfillingAction->css)? $ProfillingAction->css->pluck('id')->toArray() : array();
              $ProfillingActionLabelCssIDs = ($ProfillingAction->labelCss)? $ProfillingAction->labelCss->pluck('id')->toArray() : array();
              $ProfillingActionParentCssIDs = ($ProfillingAction->parentCss)? $ProfillingAction->parentCss->pluck('id')->toArray() : array();
              $ProfillingActionSettingIDs = ($ProfillingAction->settings)? $ProfillingAction->settings->pluck('id')->toArray() : array();
              if (array_key_exists('css', $items)) {
                foreach ($items['css'] as $css) {
                  if($css['id'] != 0){
                    if(($key = array_search($css['id'], $ProfillingActionCssIDs)) !== false) {
                        unset($ProfillingActionCssIDs[$key]);
                    }
                    $ProfillingActionCss = ProfillingActionCss::find($css['id']);
                    $ProfillingActionCss->update($css);
                  }else{
                    $css['profilling_action_id'] = $ProfillingAction->pri_id;
                    ProfillingActionCss::create($css);
                  }
                }
              }
              if (array_key_exists('label_css', $items)) {
                foreach ($items['label_css'] as $labelCss) {
                  if($labelCss['id'] != 0){
                    if(($key = array_search($labelCss['id'], $ProfillingActionLabelCssIDs)) !== false) {
                        unset($ProfillingActionLabelCssIDs[$key]);
                    }
                    $ProfillingActionLabelCss = ProfillingActionLabelCss::find($labelCss['id']);
                    $ProfillingActionLabelCss->update($labelCss);
                  }else{
                    $labelCss['profilling_action_id'] = $ProfillingAction->pri_id;
                    ProfillingActionLabelCss::create($labelCss);
                  }
                }
              }
              if (array_key_exists('parent_css', $items)) {
                foreach ($items['parent_css'] as $parentCss) {
                  if($parentCss['id'] != 0){
                    if(($key = array_search($parentCss['id'], $ProfillingActionParentCssIDs)) !== false) {
                        unset($ProfillingActionParentCssIDs[$key]);
                    }
                    $ProfillingActionParentCss = ProfillingActionParentCss::find($parentCss['id']);
                    $ProfillingActionParentCss->update($parentCss);
                  }else{
                    $parentCss['profilling_action_id'] = $ProfillingAction->pri_id;
                    ProfillingActionParentCss::create($parentCss);
                  }
                }
              }
              if (array_key_exists('setting', $items)) {
                foreach ($items['setting'] as $setting) {
                  if($setting['id'] != 0){
                    $ProfillingActionSetting = ProfillingActionSetting::find($setting['id']);
                    $ProfillingActionSetting->update($setting);
                    if(($key = array_search($setting['id'], $ProfillingActionSettingIDs)) !== false) {
                        unset($ProfillingActionSettingIDs[$key]);
                    }
                  } else {
                    $setting['profilling_action_id'] = $ProfillingAction->pri_id;
                    ProfillingActionSetting::create($setting);
                  }
                }
              }
              ProfillingActionCss::whereIn('id',$ProfillingActionCssIDs)->delete();
              ProfillingActionLabelCss::whereIn('id',$ProfillingActionLabelCssIDs)->delete();
              ProfillingActionParentCss::whereIn('id',$ProfillingActionParentCssIDs)->delete();
              // dd($ProfillingActionSettingIDs);
              ProfillingActionSetting::whereIn('id',$ProfillingActionSettingIDs)->delete();
            } else {
              $items['profilling_id'] = $Profilling->id;
              $ProfillingAction = ProfillingAction::create($items);
              if (array_key_exists('css', $items)) {
                foreach ($items['css'] as $css) {
                  $css['profilling_action_id'] = $ProfillingAction->pri_id;
                  ProfillingActionCss::create($css);
                }
              }
              if (array_key_exists('label_css', $items)) {
                foreach ($items['label_css'] as $labelCss) {
                  $labelCss['profilling_action_id'] = $ProfillingAction->pri_id;
                  ProfillingActionLabelCss::create($labelCss);
                }
              }
              if (array_key_exists('parent_css', $items)) {
                foreach ($items['parent_css'] as $parentCss) {
                  $parentCss['profilling_action_id'] = $ProfillingAction->pri_id;
                  ProfillingActionParentCss::create($parentCss);
                }
              }
              if (array_key_exists('setting', $items)) {
                foreach ($items['setting'] as $setting) {
                  $setting['profilling_action_id'] = $ProfillingAction->pri_id;
                  ProfillingActionSetting::create($setting);
                }
              }
            }
          }
          ProfillingAction::whereIn('pri_id',$ProfillingActionIDs)->delete();
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
        $Profilling = Profilling::find($id);
        if($Profilling->ProfillingActions){
          foreach ($Profilling->ProfillingActions as $key => $profillingAction) {
            if($profillingAction->css){
              foreach ($profillingAction->css as $key => $css) {
                $css->delete();
              }
            }
            if($profillingAction->parentCss){
              foreach ($profillingAction->parentCss as $key => $parentCss) {
                $parentCss->delete();
              }
            }
            if($profillingAction->settings){
              foreach ($profillingAction->settings as $key => $settings) {
                $settings->delete();
              }
            }
            $profillingAction->delete();
          }
        }
      $Profilling->forceDelete();
      return response()->json([
          'msg_return' => 'ลบข้อมูลสำเร็จ',
          'code_return' => 1,
      ]);
    }

    public function achieve($id)
    {
      $Profilling = Profilling::find($id);
      $Profilling->delete();

      return response()->json([
          'msg_return' => 'ลบข้อมูลสำเร็จ',
          'code_return' => 1,
      ]);
    }

    public function unAchieve($id)
    {
        $profilling = Profilling::onlyTrashed()->where('id',$id)->first();
        $profilling->restore();

        return response()->json([
            'msg_return' => 'ทำการ un-achieve เรียบร้อย',
            'code_return' => 1,
        ]);
    }

    public function getDataAchieve()
    {
      $profillings = Profilling::onlyTrashed()->get();
      foreach ($profillings as $key => $profilling) {
        $subscriber = $profilling->subscriber;
        $profillingFolder = $profilling->profillingFolder;
      }
      return response()->json([
          'datas' => $profillings,
      ]);
    }
}
