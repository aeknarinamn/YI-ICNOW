<?php

namespace YellowProject;

use Illuminate\Database\Eloquent\Model;
use YellowProject\SubDistrict;
use YellowProject\District;
use YellowProject\Province;

class District extends Model
{
    /**
     * A flag to indicate whether or not to use timestamp on the record
     *
     * @var array
     */
    public $timestamps = false;

    /**
     * A District has many Sub Districts
     */
    public function sub_districts()
    {
        return $this->hasMany(SubDistrict::class);
    }

    /**
     * A District has one Province
     */
    public function province()
    {
        return $this->belongsTo(Province::class);
    }
}
