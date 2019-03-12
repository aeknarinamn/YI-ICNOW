<?php

namespace YellowProject;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use YellowProject\TrackingRecieveBc;
use YellowProject\HistoryAddBlock;
use YellowProject\Campaign\CampaignSendMessage;

class LineUserProfile extends Model
{
   	use SoftDeletes;
    protected $guard = "dim_line_user_table";
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dim_line_user_table';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'customer_id',
        'mid',
		'avatar',
        'name',
        'email',
        'phone_number',
        'user_type',
        'flag_status',
        'flag_comment',
        'is_follow',
        'is_auto_kick',
		'address_id',
    ];

    public function historyAddBlocks()
    {
        return $this->hasMany(HistoryAddBlock::class, 'line_user_id', 'id');
    }
    
    public function campaignSendMessages()
    {
        return $this->hasMany(CampaignSendMessage::class, 'mid', 'mid');
    }

    public function trackingRecieveBcs()
    {
        return $this->hasMany(TrackingRecieveBc::class, 'line_user_id', 'id');
    }

    public static function genCustomerNumber()
    {
        $count = LineUserProfile::all()->count();
        $pad = str_pad($count+1, 8, '0', STR_PAD_LEFT);
        $docId = "C"."_".$pad;

        return $docId;
    }
}
