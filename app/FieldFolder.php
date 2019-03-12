<?php

namespace YellowProject;

use Illuminate\Database\Eloquent\Model;

class FieldFolder extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dim_field_folder';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
		'desc',
    ];
}