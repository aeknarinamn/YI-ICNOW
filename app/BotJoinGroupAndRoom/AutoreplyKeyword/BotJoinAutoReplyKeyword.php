<?php

namespace YellowProject\BotJoinGroupAndRoom\AutoreplyKeyword;

use Illuminate\Database\Eloquent\Model;
use YellowProject\BotJoinGroupAndRoom\AutoreplyKeyword\BotJoinAutoReplyKeywordItem as AutoReplyKeywordItem;
use YellowProject\BotJoinGroupAndRoom\AutoreplyKeyword\BotJoinKeyword as Keyword;
use Carbon\Carbon;
use Log;
use Response;

class BotJoinAutoReplyKeyword extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dim_botjoin_auto_reply';

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

    public function keywords()
    {
        return $this->hasMany(Keyword::class,'dim_botjoin_auto_reply_id','id');
    }


    public function autoReplyKeyWordItems()
    {
        return $this->hasMany(AutoReplyKeywordItem::class,'dim_botjoin_auto_reply_id','id');
    }

    public static function checkDuplicate($title, $iD = null)
    {
        if (is_null($iD)) {
            $item = BotJoinAutoReplyKeyword::where('title',$title)->first();
            if (is_null($item)) {
                return false;
            } else {
                return true;
            }
        } else {
            $item = BotJoinAutoReplyKeyword::where('title',$title)->where('id',"!=", $iD)->first();
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
