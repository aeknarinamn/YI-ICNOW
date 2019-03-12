<?php

namespace YellowProject;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AutoReplyDefault extends Model
{
    use SoftDeletes;
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dim_auto_reply_default';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'active',
		'alt_text',
    ];


    //Event Listener
    protected static function boot() 
    {
        parent::boot();

       static::deleting(function($autoReplyDefault) {
            $autoReplyDefault->autoReplyDefaultItems()->delete();
        });
    }





    public static function checkDuplicate($channelId)
    {
        $item = AutoReplyDefault::where('title',$channelId)->first();
        if (is_null($item)) {
            return false;
        } else {
            return true;
        }
    }


    public function autoReplyDefaultItems()
    {
        return $this->hasMany(AutoReplyDefaultItem::class,'dim_auto_reply_default_id','id');
    }
}
