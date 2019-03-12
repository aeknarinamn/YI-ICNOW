<?php

namespace YellowProject;

use Illuminate\Database\Eloquent\Model;
use YellowProject\LocationItem;

class LocationKeyword extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'fact_location_keywords';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'location_id',
        'keyword',
    ];

    public function locationItem()
    {
        return $this->belongsTo(LocationItem::class, 'location_id', 'id');
    }
}
