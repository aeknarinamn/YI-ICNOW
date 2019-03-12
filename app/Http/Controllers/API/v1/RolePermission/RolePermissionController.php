<?php

namespace YellowProject\Http\Controllers\API\v1\RolePermission;

use Illuminate\Http\Request;
use YellowProject\Http\Controllers\Controller;
use YellowProject\RolePermission\RolePermission;
use YellowProject\RolePermission\RolePermissionItem;

class RolePermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $datas = RolePermission::all();
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
        $rolePermissionSearch = RolePermission::where('name',$request->name)->first();
        if($rolePermissionSearch){
          return response()->json([
              'msg_return' => 'ชื่อซ้ำกัน',
              'code_return' => 2,
          ]);
        }
        $rolePermission = RolePermission::create($request->all());
        if($request->menu_access_items){
            $menuAccessItems = $request->menu_access_items;
            foreach ($menuAccessItems as $key => $menuAccessItem) {
                $menuAccessItem['permission_role_id'] = $rolePermission->id;
                RolePermissionItem::create($menuAccessItem);
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
        $datas = [];
        $rolePermission = RolePermission::find($id);
        $rolePsermissionItems = $rolePermission->permissionRoleItems;
        $datas['id'] = $rolePermission->id;
        $datas['name'] = $rolePermission->name;
        foreach ($rolePsermissionItems as $key => $rolePsermissionItem) {
            $datas['menu_access_items'][$key]['id'] = $rolePsermissionItem->id;
            $datas['menu_access_items'][$key]['menu_id'] = $rolePsermissionItem->menu_id;
            $datas['menu_access_items'][$key]['role_id'] = $rolePsermissionItem->permission_role_id;
            $datas['menu_access_items'][$key]['access_id'] = $rolePsermissionItem->access_id;
            $datas['menu_access_items'][$key]['is_active'] = $rolePsermissionItem->is_active;
        }


        return response()->json([
            'datas' => $datas,
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
        $rolePermission = RolePermission::find($id);
        $rolePermissionSearch = RolePermission::where('name',$request->name)->first();
        if($rolePermissionSearch && $request->name != $rolePermission->name){
          return response()->json([
              'msg_return' => 'ชื่อซ้ำกัน',
              'code_return' => 2,
          ]);
        }

        $rolePsermissionItems = $rolePermission->permissionRoleItems;
        if($rolePsermissionItems){
            foreach ($rolePsermissionItems as $key => $rolePsermissionItem) {
                $rolePsermissionItem->delete();
            }
        }

        if($request->menu_access_items){
            $menuAccessItems = $request->menu_access_items;
            foreach ($menuAccessItems as $key => $menuAccessItem) {
                $menuAccessItem['permission_role_id'] = $rolePermission->id;
                RolePermissionItem::create($menuAccessItem);
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
        $rolePermission = RolePermission::find($id);
        $rolePsermissionItems = $rolePermission->permissionRoleItems;
        if($rolePsermissionItems){
            foreach ($rolePsermissionItems as $key => $rolePsermissionItem) {
                $rolePsermissionItem->delete();
            }
        }
        $rolePermission->delete();

        return response()->json([
            'msg_return' => 'ลบข้อมูลสำเร็จ',
            'code_return' => 1,
        ]);
    }
}
