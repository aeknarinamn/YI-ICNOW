<?php

namespace YellowProject\Country;

use Illuminate\Database\Eloquent\Model;

class SubDistrict extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'sub_districts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'post_code',
		'district_id',
		'latitude',
		'longitude',
    ];

}
