<?php

namespace YellowProject\Subscriber;

use Illuminate\Database\Eloquent\Model;

class SubscriberCategory extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dim_subscriber_category';

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
