<?php

namespace YellowProject\Ecommerce\DTManagement;

use Illuminate\Database\Eloquent\Model;
use YellowProject\Ecommerce\DTManagement\DTManagement;

class DTManagementPostCode extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'fact_ecommerce_dt_management_post_code';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'ecommerce_dt_management_id',
    	'post_code',
    ];

    public function dtManagement()
    {
        return $this->belongsTo(DTManagement::class,'ecommerce_dt_management_id','id');
    }
}
