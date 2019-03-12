<?php

namespace YellowProject;

use Illuminate\Database\Eloquent\Model;

class UserPermissionRole extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'fact_user_permission_role';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'menu_id',
        'action',
        'active',
    ];
}
