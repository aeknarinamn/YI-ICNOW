<?php

namespace YellowProject;

use Illuminate\Database\Eloquent\Model;

class FieldItem extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'fact_fields';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'dim_fields_id',
        'value',
    ];

    // protected $dates = ['deleted_at'];
    
    public $timestamps = true;

    /**
     * Relationships
     */

}
