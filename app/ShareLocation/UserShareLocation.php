<?php

namespace YellowProject\ShareLocation;

use Illuminate\Database\Eloquent\Model;
use YellowProject\LineUserProfile;

class UserShareLocation extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'fact_user_share_location';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'line_user_id',
        'latitude',
		'longtitude',
		'address',
    ];

    public function lineUserProfile()
    {
        return $this->belongsTo(LineUserProfile::class, 'line_user_id', 'id');
    }
}
