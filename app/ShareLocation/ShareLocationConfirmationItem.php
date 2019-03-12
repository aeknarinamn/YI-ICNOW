<?php

namespace YellowProject\ShareLocation;

use Illuminate\Database\Eloquent\Model;
use YellowProject\ShareLocation\ShareLocationItem;

class ShareLocationConfirmationItem extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'fact_share_location_confirmation';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'share_location_confirmation_id',
        'share_location_item_id',
        'distance',
        'is_active',
    ];

    public function shareLocationItem()
    {
        return $this->belongsTo(ShareLocationItem::class, 'share_location_item_id', 'id');
    }
}
