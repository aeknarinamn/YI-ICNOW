<?php

namespace YellowProject;

use Illuminate\Database\Eloquent\Model;

class ProfillingActionParentCss extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'fact_profilling_action_parent_css';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'profilling_action_id',
        'key',
        'label',
        'type',
        'values',
    ];

}
