<?php

namespace YellowProject\Ecommerce\DTManagement;

use Illuminate\Database\Eloquent\Model;

class DTManagementUploadItem extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'fact_ecommerce_dt_management_upload_item';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'ecommerce_dt_management_upload_id',
    	'name',
    	'address',
    	'province',
    	'post_code',
    	'ecommerce_dt_management_group_id',
    	'status',
        'dt_code_login',
        'dt_url_login',
        'remark'
    ];
}