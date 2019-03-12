<?php

namespace YellowProject;

use Illuminate\Database\Eloquent\Model;
use YellowProject\SubscriberLine;
use YellowProject\Field;
use YellowProject\SubscriberFolder;

class Subscriber extends Model
{
    public $timestamps = true;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected   $table  =  'dim_subscriber';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected   $fillable = [
        'folder_id',
        'category_id',
        'name',
        'desc',
        'is_master'
    ];

    public function subscriberLines()
    {
        return $this->hasMany(SubscriberLine::class, 'subscriber_id', 'id');
    }

    public function fields()
    {
        return $this->hasMany(Field::class, 'subscriber_id', 'id');
    }

    public function folder()
    {
        return $this->belongsto(SubscriberFolder::class,'folder_id','id');
    }
}
