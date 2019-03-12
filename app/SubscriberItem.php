<?php

namespace YellowProject;

use Illuminate\Database\Eloquent\Model;
use YellowProject\SubscriberLine;
use YellowProject\Field;

class SubscriberItem extends Model
{
    public $timestamps = true;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected   $table  =  'fact_subscribers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected   $fillable = [
        'subscriber_line_id',
        'field_id',
        'value',
    ];

    public function subscriberLine()
    {
        return $this->hasMany(SubscriberLine::class, 'subscriber_line_id', 'id');
    }

    public function field()
    {
        return $this->belongsTo(Field::class, 'field_id', 'id');
    }
}
