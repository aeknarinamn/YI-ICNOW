<?php

namespace YellowProject\Http\Controllers\API\v1;

use Illuminate\Http\Request;
use YellowProject\Http\Controllers\Controller;
use YellowProject\User;
use YellowProject\UserPermissionRole;
use YellowProject\RolePermission\RolePermissionItem;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use YellowProject\ICNOW\AdminUser\AdminUser;
// use Illuminate\Foundation\Auth\ResetsPasswords;

class UserController extends Controller
{
    // use ResetsPasswords;
    use SendsPasswordResetEmails;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $datas = [];
        $users = User::all();
        foreach ($users as $key => $user) {
            $user->rolePermission;
        }
        return response()->json([
            'datas' => $users,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($token);

        $user = User::create($request->all());
        
        $this->sendResetLinkEmail($request);
        
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
        $user = User::find($id);
        return response()->json([
            'datas' => $user,
            'dataUserRole' => $user->userPermissionRoles,
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
        $user = User::find($id);
        $user->update($request->all());

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
        $user = User::find($id);
        $adminUser = AdminUser::where('email',$user->email)->first();
        if($adminUser){
            $adminUser->forceDelete();
        }
        
        $user->forceDelete();
        return response()->json([
            'msg_return' => 'ลบสำเร็จ',
            'code_return' => 1,
        ]);
    }

    // public function storeUserDt(Request $request)
    // {
    //     $request['is_active'] = 1;
    //     $request['rule_id'] = 1000;
    //     $user = User::create($request->all());
 
    //     DTManagementRegisterData::create([
    //         'line_user_id' => $request->line_user_id,
    //         'user_id' => $user->id,
    //         'dt_code' => $request->dt_code,
    //     ]);
 
    //     $this->sendResetLinkEmail($request);
 
    //     return redirect('/login');
    // }
}
