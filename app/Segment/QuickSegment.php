<?php

namespace YellowProject\Segment;

use Illuminate\Database\Eloquent\Model;
use YellowProject\Segment\QuickSegmentItem;

class QuickSegment extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dim_quick_segment';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'desc',
        'count_new_user',
        'count_duplicate_user',
    ];

    public function quickSegmentItem()
    {
        return $this->hasMany(QuickSegmentItem::class,'quick_segment_id','id');
    }

    public static function getDatas($id)
    {
        $datas = collect();
        $quickSegmentItem = QuickSegmentItem::where('quick_segment_id',$id)->get();
        if($quickSegmentItem->count() > 0){
            $datas->push($quickSegmentItem->pluck('line_user_id'));
        }

        return $datas->collapse()->unique();
    }

}
