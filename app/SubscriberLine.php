<?php

namespace YellowProject;

use Illuminate\Database\Eloquent\Model;
use YellowProject\Subscriber;
use YellowProject\SubscriberItem;
use YellowProject\LineUserProfile;

class SubscriberLine extends Model
{
    public $timestamps = true;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected   $table  =  'dim_subscriber_line';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected   $fillable = [
        'subscriber_id',
        'line_user_id',
        'updated_at',
    ];

    public function lineUserProfile()
    {
        return $this->belongsTo(LineUserProfile::class, 'line_user_id', 'id');
    }

    public function subscriber()
    {
        return $this->belongsTo(Subscriber::class, 'subscriber_id', 'id');
    }

    public function subscriberItems()
    {
        return $this->hasMany(SubscriberItem::class, 'subscriber_line_id', 'id');
    }
}
