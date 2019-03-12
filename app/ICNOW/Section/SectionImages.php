<?php

namespace YellowProject\ICNOW\Section;

use Illuminate\Database\Eloquent\Model;

class SectionImages extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dim_icnow_section_images';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'icnow_section_id',
        'img_url',
    ];
}
