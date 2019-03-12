<?php

namespace YellowProject\Segment;

use Illuminate\Database\Eloquent\Model;
use YellowProject\Segment\Segment;
use YellowProject\Segment\SegmentCondition;

class SegmentCondition extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dim_segment_condition';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'segment_id',
        'subscriber_match',
    ];

    public function segment()
    {
        return $this->belongsTo(Segment::class, 'segment_id', 'id');
    }

    public function segmentConditionItems()
    {
        return $this->hasMany(SegmentConditionItem::class,'segment_condition_id','id');
    }
}
