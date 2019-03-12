<?php

namespace YellowProject;

use Illuminate\Database\Eloquent\Model;
use YellowProject\CarouselItem;

class CarouselConfirmationItem extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'fact_carousel_confirmation';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'carousel_confirmation_id',
        'carousel_item_id',
        'is_active',
    ];

    public function carouselItem()
    {
        return $this->belongsTo(CarouselItem::class, 'carousel_item_id', 'id');
    }
}
