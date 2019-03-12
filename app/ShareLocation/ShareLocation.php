<?php

namespace YellowProject\ShareLocation;

use Illuminate\Database\Eloquent\Model;
use YellowProject\ShareLocation\ShareLocationItem;
use YellowProject\ShareLocation\ShareLocationFolder;

class ShareLocation extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dim_share_location';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'folder_id',
        'name',
		'desc',
		'action_1',
		'action_2',
        'action_3',
        'label_1',
        'label_2',
		'label_3',
        'conf',
        'alt_text',
        'is_autoreply',
        'start_date',
        'end_date',
    ];

    public function shareLocationItems()
    {
        return $this->hasMany(ShareLocationItem::class,'share_location_id','id');
    }

    public function folder()
    {
        return $this->belongsTo(ShareLocationFolder::class, 'folder_id', 'id');
    }
}
