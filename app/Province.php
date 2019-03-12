<?php

namespace YellowProject;

use Illuminate\Database\Eloquent\Model;
use YellowProject\District;

class Province extends Model
{
    /**
     * A flag to indicate whether or not to use timestamp on the record
     *
     * @var array
     */
    public $timestamps = false;

    /**
     * A SubDistrict has many Districts
     */
    public function districts()
    {
        return $this->hasMany(District::class);
    }
}
