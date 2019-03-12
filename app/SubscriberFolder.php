<?php

namespace YellowProject;

use Illuminate\Database\Eloquent\Model;

class SubscriberFolder extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dim_subscriber_folder';

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
