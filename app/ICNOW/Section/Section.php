<?php

namespace YellowProject\ICNOW\Section;

use Illuminate\Database\Eloquent\Model;
use YellowProject\ICNOW\Section\SectionImages;

class Section extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dim_icnow_section';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'section_name',
        'section_desc',
    ];

    public function sectionImages()
    {
        return $this->hasMany(SectionImages::class,'icnow_section_id','id');
    }

    public static function genData()
    {
        \YellowProject\ICNOW\Section\Section::truncate();
        Section::create([
            'section_name' => 'DIY',
            'section_desc' => 'DIY',
        ]);

        Section::create([
            'section_name' => 'Party Set',
            'section_desc' => 'Party Set',
        ]);
    }
}
