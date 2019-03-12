<?php

namespace YellowProject\BotJoinGroupAndRoom\AutoreplyKeyword;

use Illuminate\Database\Eloquent\Model;
use YellowProject\BotJoinGroupAndRoom\AutoreplyKeyword\BotJoinAutoReplyKeyword as AutoReplyKeyword;

class BotJoinKeyword extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'fact_botjoin_keyword';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'keyword',
		'dim_botjoin_auto_reply_id',
    ];


    public function autoReplyKeyWord()
    {
        return $this->belongsTo(AutoReplyKeyword::class, 'dim_botjoin_auto_reply_id', 'id');
    }


    public static function checkDuplicate($keywords)
    {
        if (isset($keywords['keywords']) ) {
            foreach ($keywords['keywords'] as $keyword) {
                $item = BotJoinKeyword::where('keyword', $keyword['value'][0])->first();
                if (!is_null($item)) {
                    return true;
                } 
                $item = null;
            }
        } else {
            return false;
        }
    }
}
