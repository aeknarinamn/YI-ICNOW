<?php

namespace YellowProject\ShareLocation;

use Illuminate\Database\Eloquent\Model;
use YellowProject\ShareLocation\ShareLocation;
use YellowProject\ShareLocation\ShareLocationConfirmation;
use YellowProject\ShareLocation\ShareLocationConfirmationItem;
use YellowProject\ShareLocation\ShareLocationItem;

class ShareLocationItem extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'fact_share_location';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'share_location_id',
        'name',
		'description',
		'image_url',
		'link_url_1',
		'link_url_2',
		'link_url_3',
		'tel_1',
		'tel_2',
		'tel_3',
		'keyword_text_1',
		'keyword_text_2',
		'keyword_text_3',
		'latitude',
		'longtitude',
		// 'keyword',
        'auto_reply_keyword_id',
        'autoreply_folder_name',
        'autoreply_title',
        'auto_reply_message',
        'auto_reply_keyword',
    ];

    public function shareLocation()
    {
        return $this->belongsTo(ShareLocation::class, 'share_location_id', 'id');
    }
}
