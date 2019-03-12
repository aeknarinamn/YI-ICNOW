<?php

namespace YellowProject;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReportTag extends Model
{
     use SoftDeletes;
    public $timestamps = false;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'report_tags';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       	'name',
		'description',
    ];

    /**
     * A flag to indicate whether or not to use timestamp on the record
     *
     * @var array
     */
}
