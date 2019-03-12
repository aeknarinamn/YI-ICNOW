<?php

namespace YellowProject\Country;

use Illuminate\Database\Eloquent\Model;
use YellowProject\Country\SubDistrict;

class District extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'districts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
		'province_id',
		'latitude',
		'longitude',
    ];

    public function subDistricts()
    {
        return $this->hasMany(SubDistrict::class,'district_id','id');
    }

}
