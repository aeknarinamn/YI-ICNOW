<?php

namespace YellowProject\Ecommerce\TemplateSentMessage;

use Illuminate\Database\Eloquent\Model;
use YellowProject\Ecommerce\TemplateSentMessage\TemplateSentMessageItem;
use Carbon\Carbon;
use Log;
use Response;

class TemplateSentMessageMain extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dim_ecommerce_template_sent_message';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'title',
        'active',
		'alt_text',
		'sent_date',
		'last_sent_date',
		'conf',
		'is_open_bot',
    ];

    public function autoReplyKeyWordItems()
    {
        return $this->hasMany(TemplateSentMessageItem::class,'dim_ecommerce_template_sent_message_id','id');
    }

    public static function checkDuplicate($title, $iD = null)
    {
        if (is_null($iD)) {
            $item = TemplateSentMessageMain::where('title',$title)->first();
            if (is_null($item)) {
                return false;
            } else {
                return true;
            }
        } else {
            $item = TemplateSentMessageMain::where('title',$title)->where('id',"!=", $iD)->first();
            if (is_null($item)) {
                return false;
            } else {
                return true;
            }
        }
        
    }

    public static function convertDate($timestamp)
    {
        $time = $timestamp;
        $strTime = substr($time, 0, -3);
        //line receive  UTC + 5   want to conver to UTC+7
        $strDate = Carbon::createFromTimestamp((int) $strTime);
        $date = Carbon::createFromFormat('Y-m-d H:i:s', $strDate);
        return $date->toDateTimeString();
    }

}
