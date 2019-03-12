<?php

namespace YellowProject;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use YellowProject\ShareLocation\ShareLocationItem;
use YellowProject\CarouselItem;
use Carbon\Carbon;
use Log;
use Response;

class AutoReplyKeyword extends Model
{
    use SoftDeletes;
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dim_auto_reply_keywords';
    //protected $appends = ['show_keyword'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
		'active',
        'sent_date',
		'last_sent_date',
		'folder_id',
		'report_tag_id',
        'is_postback'
    ];


    public function folder()
    {
        return $this->belongsTo(KeywordFolder::class, 'folder_id', 'id');
    }

    public function report_tag()
    {
        return $this->belongsTo(ReportTag::class, 'report_tag_id', 'id');
    }


    public function keywords()
    {
        return $this->hasMany(Keyword::class,'dim_auto_reply_keywords_id','id');
    }


    public function autoReplyKeyWordItems()
    {
        return $this->hasMany(AutoReplyKeywordItem::class,'dim_auto_reply_keyword_id','id');
    }

    public function shareLocationItems()
    {
        return $this->hasMany(ShareLocationItem::class,'auto_reply_keyword_id','id');
    }

    public function carouselItems()
    {
        return $this->hasMany(CarouselItem::class,'auto_reply_keyword_id','id');
    }


    public static function checkDuplicate($title, $iD = null)
    {
        if (is_null($iD)) {
            $item = AutoReplyKeyword::where('title',$title)->first();
            if (is_null($item)) {
                return false;
            } else {
                return true;
            }
        } else {
            $item = AutoReplyKeyword::where('title',$title)->where('id',"!=", $iD)->first();
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



    /*
               $now = Carbon::now()->setTimezone('+7');
                        $dateNow = $now->toDateTimeString();
                        $autoReplyKeyWord = $keywordTag->autoReplyKeyWord;
                        $start_date = $autoReplyKeyWord->sent_date;
                        $last_sent_date = $autoReplyKeyWord->last_sent_date;
                        $status = $autoReplyKeyWord->status;
                        $chkStart = $start_date <= $dateNow;
                        $chkLast = $dateNow <= $last_sent_date;

    */

}
