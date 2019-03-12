<?php

namespace YellowProject\ICNOW\Log;

use Illuminate\Database\Eloquent\Model;

class LogProductView extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'fact_icnow_log_product_view';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'line_user_id',
        'product_id'
    ];
}
