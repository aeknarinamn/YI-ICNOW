<?php

namespace YellowProject\RolePermission;

use Illuminate\Database\Eloquent\Model;
use YellowProject\RolePermission\RolePermissionItem;

class RolePermission extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dim_permission_role';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'name',
    ];

    public function permissionRoleItems()
    {
        return $this->hasMany(RolePermissionItem::class,'permission_role_id','id');
    }

}
