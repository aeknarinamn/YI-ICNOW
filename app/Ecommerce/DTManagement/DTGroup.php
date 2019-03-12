<?php

namespace YellowProject\Ecommerce\DTManagement;

use Illuminate\Database\Eloquent\Model;

class DTGroup extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dim_ecommerce_dt_group_management';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'name',
    	'desc',
    ];
}
