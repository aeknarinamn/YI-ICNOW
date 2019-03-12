<?php

namespace YellowProject\Ecommerce\Task;

use Illuminate\Database\Eloquent\Model;

class SetTask extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dim_ecommerce_set_task';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'type',
    	'main_id',
        'is_active',
    ];
}
