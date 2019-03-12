<?php

namespace YellowProject;

use Illuminate\Database\Eloquent\Model;
use YellowProject\CarouselItem;
use YellowProject\CarouselFolder;

class Carousel extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dim_carousel';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'folder_id',
        'name',
		'desc',
		'action_1',
		'action_2',
        'action_3',
        'label_1',
        'label_2',
		'label_3',
        'conf',
        'alt_text',
        'is_autoreply',
        'start_date',
        'end_date',
    ];

    public function carouselItems()
    {
        return $this->hasMany(CarouselItem::class,'carousel_id','id');
    }

    public function folder()
    {
        return $this->belongsTo(CarouselFolder::class, 'folder_id', 'id');
    }
}