<?php

namespace YellowProject\PageList;

use Illuminate\Database\Eloquent\Model;

class PageListLabel extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'fact_page_list_label';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'page_list_id',
        'label_name_th',
        'label_name_en',
        'remark',
    ];
}
