<?php

namespace YellowProject\ICNOW\Mini;

use Illuminate\Database\Eloquent\Model;

class MiniUser extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dim_icnow_mini_user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'line_user_id',
        'username',
        'password',
        'mini_code',
        'dt_code',
        'mini_name',
        'dt_name',
    ];
}
