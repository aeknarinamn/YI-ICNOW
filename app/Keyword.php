<?php

namespace YellowProject;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Keyword extends Model
{
    use SoftDeletes;
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'keywords';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'keyword',
		'active',
        'dim_auto_reply_keywords_id',
    ];


    public function autoReplyKeyWord()
    {
        return $this->belongsTo(AutoReplyKeyword::class, 'dim_auto_reply_keywords_id', 'id');
    }


    public static function checkDuplicate($keywords)
    {
        if (isset($keywords['keywords']) ) {
            foreach ($keywords['keywords'] as $keyword) {
                $item = Keyword::where('keyword', $keyword['value'][0])->first();
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
