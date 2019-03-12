<?php

namespace YellowProject;

use Auth;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Notifications\Notifiable as Notifiable;

use YellowProject\UserPermissionRole;
use YellowProject\UserAgent;
use YellowProject\RolePermission\RolePermission;

class User extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{
    use SoftDeletes, Authenticatable, Authorizable, CanResetPassword, Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'users';

    protected $fillable = [
        'name',
        'username', 
        'email', 
        'rule_id', 
        'is_active', 
        'password',
        'mid',
        'avatar',
        'is_dt',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 
        'remember_token',
    ];

    public $timestamps = true;

    // public function setPasswordAttribute($newPassword) 
    // {
    //     if($newPassword != ''){
    //         $this->attributes['password'] = bcrypt($newPassword);
    //     }
    // }

    public function userPermissionRoles()
    {
        return $this->hasMany(UserPermissionRole::class,'user_id','id')->select(array('id','user_id','menu_id','action','active'));
    }

    public function userAgent()
    {
        return $this->belongsTo(UserAgent::class, 'id', 'user_id');
    }

    public function rolePermission()
    {
        return $this->belongsTo(RolePermission::class, 'rule_id', 'id');
    }
}
