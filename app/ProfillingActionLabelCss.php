<?php

namespace YellowProject;

use Illuminate\Database\Eloquent\Model;

class ProfillingActionLabelCss extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'fact_profilling_action_label_css';

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
