<?php

namespace YellowProject\Segment;

use Illuminate\Database\Eloquent\Model;

class SegmentQuery extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'fact_segment_query';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'segment_id',
        'line_user_id',
    ];
}
