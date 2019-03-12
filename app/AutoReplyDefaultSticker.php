<?php

namespace YellowProject;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AutoReplyDefaultSticker extends Model
{
    use SoftDeletes;
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'auto_reply_default_sticker';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
		'packageId',
		'stickerId',
        'display',//
    ];
}