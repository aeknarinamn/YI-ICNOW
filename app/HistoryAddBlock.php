<?php

namespace YellowProject;

use Illuminate\Database\Eloquent\Model;

class HistoryAddBlock extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'fact_history_add_block';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'line_user_id',
		'action',
    ];
}
