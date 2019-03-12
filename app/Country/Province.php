<?php

namespace YellowProject\Country;

use Illuminate\Database\Eloquent\Model;
use YellowProject\Country\District;

class Province extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'provinces';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
		'name_eng',
		'latitude',
		'longitude',
    ];

    public function districts()
    {
        return $this->hasMany(District::class,'province_id','id');
    }

}
