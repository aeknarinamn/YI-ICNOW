<?php

namespace YellowProject\ICNOW\Mini;

use Illuminate\Database\Eloquent\Model;

class Mini extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dim_icnow_mini';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'dt_code',
        'dt_name',
        'mini_code',
        'mini_name',
        'walls_code',
        'walls_name',
        'latitude',
        'longitude',
        'address',
        'customer_name',
        'customer_phonenumber',
        'is_active',
        'login_url',
    ];
}
