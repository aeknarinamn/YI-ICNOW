<?php

namespace YellowProject;

use Illuminate\Database\Eloquent\Model;
use YellowProject\ListMenu;

class ListModuleMenu extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dim_list_module_menu';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'module_name',
    ];

    public function listMenus()
    {
        return $this->hasMany(ListMenu::class,'module_id','id');
    }
}
