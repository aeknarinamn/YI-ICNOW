<?php

namespace YellowProject\RolePermission;

use Illuminate\Database\Eloquent\Model;

class RolePermissionItem extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'fact_permission_role';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'permission_role_id',
        'menu_id',
        'role_id',
        'access_id',
        'is_active',
    ];

}
