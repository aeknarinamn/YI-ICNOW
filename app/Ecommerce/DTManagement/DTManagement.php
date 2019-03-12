<?php

namespace YellowProject\Ecommerce\DTManagement;

use Illuminate\Database\Eloquent\Model;
use YellowProject\Ecommerce\DTManagement\DTGroup;
use YellowProject\Ecommerce\DTManagement\DTManagementPostCode;

class DTManagement extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dim_ecommerce_dt_management';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'name',
        'address',
    	'tel',
    	'province',
    	'ecommerce_dt_management_group_id',
    	'status',
    	'dt_code_login',
    	'dt_url_login',
        'seq',
    	'line_at_link',
    ];

    public function dtGroup()
    {
        return $this->belongsTo(DTGroup::class,'ecommerce_dt_management_group_id','id');
    }

    public function postCodes()
    {
        return $this->hasMany(DTManagementPostCode::class,'ecommerce_dt_management_id','id');
    }
}
