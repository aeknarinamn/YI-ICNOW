<?php

namespace YellowProject\RateLimit;

use Illuminate\Database\Eloquent\Model;

class RateLimitSetting extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dim_setting_rate_limit';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'max_rate_limit',
		'rate_limit_count',
    ];
}
