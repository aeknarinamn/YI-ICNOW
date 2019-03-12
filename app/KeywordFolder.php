<?php

namespace YellowProject;

use Illuminate\Database\Eloquent\Model;

class KeywordFolder extends Model
{
    public $timestamps = false;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dim_keyword_folder';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'folder_name',
		'description',
    ];
}
