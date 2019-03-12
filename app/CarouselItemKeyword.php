<?php

namespace YellowProject;

use Illuminate\Database\Eloquent\Model;
use YellowProject\CarouselItem;

class CarouselItemKeyword extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'fact_carousel_keyword';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'carousel_id',
        'keyword',
    ];

    public function carouselItem()
    {
        return $this->belongsTo(CarouselItem::class, 'carousel_id', 'id');
    }
}
