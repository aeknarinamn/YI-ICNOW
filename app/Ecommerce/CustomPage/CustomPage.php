<?php

namespace YellowProject\Ecommerce\CustomPage;

use Illuminate\Database\Eloquent\Model;
use YellowProject\TrackingBc;

class CustomPage extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dim_ecommerce_custom_page';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'name',
    	'content_body',
        'img_header',
        'url',
        'is_active',
        'tracking_bc_id'
    ];

    public function trackingBc()
    {
        return $this->belongsto(TrackingBc::class,'tracking_bc_id','id');
    }
}
