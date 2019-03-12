<?php

namespace YellowProject\Segment;

use Illuminate\Database\Eloquent\Model;

class SegmentFolder extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dim_segment_folder';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'desc',
    ];
}
