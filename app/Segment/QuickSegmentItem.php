<?php

namespace YellowProject\Segment;

use Illuminate\Database\Eloquent\Model;

class QuickSegmentItem extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'fact_quick_segment';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'quick_segment_id',
        'line_user_id',
    ];
}
