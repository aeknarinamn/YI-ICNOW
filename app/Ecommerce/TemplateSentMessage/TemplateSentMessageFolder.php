<?php

namespace YellowProject\Ecommerce\TemplateSentMessage;

use Illuminate\Database\Eloquent\Model;

class TemplateSentMessageFolder extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dim_ecommerce_template_sent_message_folder';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'desc',//playload
    ];
    
}
