<?php

namespace YellowProject\Queuing;

use Illuminate\Database\Eloquent\Model;
use YellowProject\AutoReplyKeyword;
use YellowProject\Campaign;
use Carbon\Carbon;

class QueuingData extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'fact_queuing_data';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'auto_reply_keyword_id',
        'campaign_id',
        'type',
        'sent_time',
        'status',
    ];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class, 'campaign_id', 'id');
    }

    public function autoReplyKeyword()
    {
        return $this->belongsTo(AutoReplyKeyword::class,'auto_reply_keyword_id','id');
    }

    public static function checkRateLimt($type,$id)
    {
    	$dateNow = Carbon::now();
    	$startMinute = $dateNow->format('Y-m-d H:i');
    	$endMinute = $dateNow->addMinute(1)->format('Y-m-d H:i');
    	$queuingData = QueuingData::where('sent_time','>=',$startMinute)->where('sent_time','<=',$endMinute)->where('status','process')->get();
    	$limitMessagePerMinute = 1;
    	$dataArrays = [];
    	if($type == 'campaign'){
    		$dataArrays['campaign_id'] = $id;
    	}else if($type == 'auto_reply_keyword'){
    		$dataArrays['auto_reply_keyword_id'] = $id;
    	}else{

    	}

    	if($queuingData->count() >= $limitMessagePerMinute){
    		
    	}
    }
}
