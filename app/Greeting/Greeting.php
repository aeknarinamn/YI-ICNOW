<?php

namespace YellowProject\Greeting;

use Illuminate\Database\Eloquent\Model;
use YellowProject\Greeting\GreetingItem;

class Greeting extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dim_greeting';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
		'active',
    ];


    //Event Listener
    protected static function boot() 
    {
        parent::boot();

       static::deleting(function($greeting) {
            $greeting->greetingItems()->delete();
        });
    }





    public static function checkDuplicate($channelId)
    {
        $item = Greeting::where('title',$channelId)->first();
        if (is_null($item)) {
            return false;
        } else {
            return true;
        }
    }


    public function greetingItems()
    {
        return $this->hasMany(GreetingItem::class,'greeting_id','id');
    }
}
