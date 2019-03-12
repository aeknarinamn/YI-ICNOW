<?php

namespace YellowProject\ShareLocation;

use Illuminate\Database\Eloquent\Model;

class ShareLocationFolder extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dim_share_location_folder';

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
