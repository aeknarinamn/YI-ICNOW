<?php

namespace YellowProject;

use Illuminate\Database\Eloquent\Model;

class ListMenu extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dim_list_menu';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'module_id',
        'menu_type',
        'menu_name',
    ];
}
