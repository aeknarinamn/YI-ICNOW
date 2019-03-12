<?php

namespace YellowProject;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use YellowProject\RichMessage;
use YellowProject\Photo\CampaignPhoto;
use YellowProject\Video\CampaignVideo;

class AutoReplyKeywordItem extends Model
{
    use SoftDeletes;
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'fact_auto_reply_keywords';
    protected $appends = ['show_message'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'dim_auto_reply_keyword_id',
		'message_type_id',
		'seq_no',
        'auto_reply_message_id',
		'auto_reply_sticker_id',
        'auto_reply_richmessage_id',
        // 'auto_reply_photo_id',
        // 'auto_reply_video_id',
        'original_content_url',
        'preview_image_url',
        'template_message_id',
    ];

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
                    'auto_reply_richmessage_id' => $this->auto_reply_richmessage_id,
                ];

                break;
            case 'image':
                $message  = [
                    'original_content_url' => $this->original_content_url,
                    'preview_image_url' => $this->preview_image_url,
                ];

                break;
            case 'video':
                $message  = [
                    'original_content_url' => $this->original_content_url,
                    'preview_image_url' => $this->preview_image_url,
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
    
    public function autoReplyKeyWord()
    {
        return $this->belongsTo(AutoReplyKeyword::class, 'dim_auto_reply_keyword_id', 'id');
    }

    public function message()
    {
        return $this->belongsTo(AutoReplyKeywordMessage::class, 'auto_reply_message_id', 'id');
    }

    public function sticker()
    {
        return $this->belongsTo(AutoReplyKeywordSticker::class, 'auto_reply_sticker_id', 'id');
    }

    public function messageType()
    {
        return $this->belongsTo(LineMessageType::class, 'message_type_id', 'id');
    }

    public function imageRichmessage()
    {
        return $this->belongsTo(RichMessage::class, 'auto_reply_richmessage_id', 'id');
    }

    // public function photo()
    // {
    //     return $this->belongsTo(CampaignPhoto::class, 'auto_reply_photo_id', 'id');
    // }

    // public function video()
    // {
    //     return $this->belongsTo(CampaignVideo::class, 'auto_reply_video_id', 'id');
    // }

    public function setMessageTypeIdAttribute($value)
    {
        $lineMessageType = LineMessageType::where('type', $value)->first();
        if(is_null($lineMessageType)) return false;

        $this->attributes['message_type_id'] = $lineMessageType->id;
    }

}
