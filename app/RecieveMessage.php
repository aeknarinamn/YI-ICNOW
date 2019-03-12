<?php

namespace YellowProject;

use Illuminate\Database\Eloquent\Model;
use YellowProject\LineBizConCustomer;
use YellowProject\LineUserProfile;
use Carbon\Carbon;

class RecieveMessage extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'fact_recieve_message';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'keyword',
        'mid',
        'line_biz_con_id',
        'bot_conf',
        'bot_reply',
    ];

    /**
     * A flag to indicate whether or not to use timestamp on the record
     *
     * @var array
     */
    public $timestamps = true;

    // public static function getCountKeywordAttribute()
    // {
    //     // dd($this);
    //     // $keyword = $this->keyword;
    //     // $recieveMessage = RecieveMessage::where('keyword',$keyword)->get();
    //     // return $recieveMessage->count();
    // }

    public static function convertDate($date)
    {
        $splitDates = explode('/', $date);
        $dateNewFormat = $splitDates['2']."-".$splitDates['1']."-".$splitDates['0'];
        return $dateNewFormat;
    }

    public function user()
    {
        return $this->belongsto(LineBizConCustomer::class,'line_biz_con_id','id');
    }

    public function lineUserProfile()
    {
        return $this->belongsto(LineUserProfile::class,'mid','mid');
    }
    
}
