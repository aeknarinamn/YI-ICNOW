<?php

namespace YellowProject\Ecommerce\RecieveKeyword;

use Illuminate\Database\Eloquent\Model;

class RecieveKeyword extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'fact_ecommerce_recieve_keyword_search';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'ecommerce_customer_id',
    	'keyword',
    ];
}
