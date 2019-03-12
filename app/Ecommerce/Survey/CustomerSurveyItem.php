<?php

namespace YellowProject\Ecommerce\Survey;

use Illuminate\Database\Eloquent\Model;

class CustomerSurveyItem extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'fact_ecommerce_customer_survey';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'ecommerce_customer_survey_id',
    	'label',
    	'value',
        'seq',
    ];

}
