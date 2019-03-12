<?php

namespace YellowProject;

use Illuminate\Database\Eloquent\Model;

class PushOrReply extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'fact_push_or_reply';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'mid',
		'action',
		'type',
    ];
}
