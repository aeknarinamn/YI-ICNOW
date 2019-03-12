<?php

namespace YellowProject\Ecommerce\Conversion;

use Illuminate\Database\Eloquent\Model;

class ConversionData extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dim_ecommerce_conversion_mapping_data';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'cpn_mobile',
    	'name',
        'sur_name',
		'bussiness_name',
		'bussiness_type',
		'post_code',
    ];
}
