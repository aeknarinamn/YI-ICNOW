<?php

namespace YellowProject\Segment;

use Illuminate\Database\Eloquent\Model;
use YellowProject\Subscriber;
use YellowProject\Segment\Segment;

class SegmentSubscriber extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'fact_segment_subscriber';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'segment_id',
        'subscriber_id',
    ];

    public function folder()
    {
        return $this->belongsTo(Segment::class, 'segment_id', 'id');
    }

    public function subscriber()
    {
        return $this->belongsTo(Subscriber::class, 'subscriber_id', 'id');
    }

}
