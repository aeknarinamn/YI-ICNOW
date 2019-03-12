<?php

namespace YellowProject\GoogleAnalytic;

use Illuminate\Database\Eloquent\Model;

class GoogleAnalytic extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dim_google_analytic';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'google_analytic_data',
    ];
}
