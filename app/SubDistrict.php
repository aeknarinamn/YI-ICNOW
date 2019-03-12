<?php

namespace YellowProject;

use YellowProject\District;
use Illuminate\Database\Eloquent\Model;

class SubDistrict extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'sub_districts';

    /**
     * A flag to indicate whether or not to use timestamp on the record
     *
     * @var array
     */
    protected $fillable = [
        'post_code',
    ];

    public $timestamps = false;

    //Event Listener
    protected static function boot() {
        parent::boot();

        static::creating(function($newSubDistrict) 
        {
            if($newSubDistrict['post_code'] == "")
            {
                $newSubDistrict['post_code'] = substr($newSubDistrict['id'], 0, 5) ;
            }
            // echo $newSubDistrict['post_code'] ;
        });

    }

    /**
     * Postcode is computed from the first 5 digit of Subdistrict's id
     */
    // public function getPostCodeAttribute() {
    //     return substr($this->id, 0, 5);
    // }

    /**
     * A SubDistrict has one District
     */
    public function district()
    {
        return $this->belongsTo(District::class);
    }
}
