<?php

namespace YellowProject\ICNOW\CacheData;

use Illuminate\Database\Eloquent\Model;

class CacheData extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'fact_icnow_cache_data';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'line_user_id',
        'data_id',
        'value'
    ];
}
