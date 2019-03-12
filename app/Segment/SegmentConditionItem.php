<?php

namespace YellowProject\Segment;

use Illuminate\Database\Eloquent\Model;
use YellowProject\Segment\SegmentCondition;

class SegmentConditionItem extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'fact_segment_condition';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'segment_condition_id',
        'title',
        'condition1',
        'condition2',
        'condition3',
        'condition4',
        'condition5',
        'condition6',
        'condition7',
        'condition8',
        'condition9',
        'condition10',
        'condition11',
        'condition12',
        'condition13',
        'condition14',
        'condition15',
        'condition16',
        'condition17',
        'condition18',
        'condition19',
        'condition20',
        'value1',
        'value2',
        'value3',
        'value4',
        'value5',
        'value6',
        'value7',
        'value8',
        'value9',
        'value10',
        'value11',
        'value12',
        'value13',
        'value14',
        'value15',
        'value16',
        'value17',
        'value18',
        'value19',
        'value20',
        'remark1',
        'remark2',
        'remark3',
        'remark4',
        'remark5',
        'remark6',
        'remark7',
        'remark8',
        'remark9',
        'remark10',
        'remark11',
        'remark12',
        'remark13',
        'remark14',
        'remark15',
        'remark16',
        'remark17',
        'remark18',
        'remark19',
        'remark20',
        'is_empty',
    ];

    public function segmentCondition()
    {
        return $this->belongsTo(SegmentCondition::class, 'segment_condition_id', 'id');
    }
}
