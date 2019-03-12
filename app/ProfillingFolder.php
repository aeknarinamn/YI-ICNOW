<?php

namespace YellowProject;

use Illuminate\Database\Eloquent\Model;

class ProfillingFolder extends Model
{
    public $timestamps = true;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dim_profilling_folder';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       	'name',
		'desc',
    ];

    /**
     * A flag to indicate whether or not to use timestamp on the record
     *
     * @var array
     */
}
