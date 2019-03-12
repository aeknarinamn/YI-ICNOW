<?php

namespace YellowProject;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use YellowProject\Campaign;
use YellowProject\CampaignTrackingURL;
use YellowProject\LineEmoticon;
use YellowProject\RichMessageImageSize;
use YellowProject\Field;
use YellowProject\RichmessageV2\Richmessage;
use Carbon\Carbon;
use YellowProject\URL;

class CampaignItem extends Model
{
    use SoftDeletes;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'fact_campaigns';
    protected $appends = ['show_message'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'campaign_id',
        'seq_no',
        'message_type_id',
        'campaign_message_id',
        'campaign_sticker_id',
        'campaign_richmessage_id',
    ];

    /**
     * A flag to indicate whether or not to use timestamp on the record
     *
     * @var array
     */
    public $timestamps = true;

    public function getShowMessageAttribute()
    {
        $message = null;

        switch ( $this->messageType->type ) {
            case 'text':
                $message  = [
                    'playload' => $this->message->message,
                    'display' => $this->message->display,
                ];
                break;
            case 'sticker':
                $message  = [
                    'package_id' => $this->sticker->packageId,
                    'stricker_id' => $this->sticker->stickerId,
                    'display' => $this->sticker->display,
                ];
                break;
            case 'imagemap':
                $message  = [
                    'auto_reply_richmessage_id' => $this->richmessage->id,
                    'img_url' => $this->richmessage->rich_message_url,
                ];
                break;
            case 'template_message':
                $message  = [
                    'template_message_id' => $this->template_message_id,
                ];

                break;
        }

        return $message;
    }

    public function campaigns()
    {
        return $this->belongsto(Campaign::class,'campaign_id','id');
    }

    public function messageType()
    {
        return $this->belongsTo(LineMessageType::class, 'message_type_id', 'id');
    }

    public function message()
    {
        return $this->belongsTo(CampaignMessage::class, 'campaign_message_id', 'id');
    }

    public function sticker()
    {
        return $this->belongsTo(CampaignSticker::class, 'campaign_sticker_id', 'id');
    }

    public function richmessage()
    {
        return $this->belongsTo(Richmessage::class, 'campaign_richmessage_id', 'id');
    }

    public function setMessageTypeIdAttribute($value)
    {

        $lineMessageType = LineMessageType::where('type', $value)->first();
        if(is_null($lineMessageType)) return false;

        $this->attributes['message_type_id'] = $lineMessageType->id;
    }

}
