<?php

namespace YellowProject\RateLimit;

use Illuminate\Database\Eloquent\Model;

class RateLimitQueue extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'fact_rate_limit_queue';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'recieve_data',
		'recieve_header',
    ];
}
