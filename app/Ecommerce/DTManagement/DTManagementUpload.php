<?php

namespace YellowProject\Ecommerce\DTManagement;

use Illuminate\Database\Eloquent\Model;
use YellowProject\Ecommerce\DTManagement\DTManagementUploadItem;

class DTManagementUpload extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dim_ecommerce_dt_management_upload';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'count_record',
    ];

    public function uploadItems()
    {
        return $this->hasMany(DTManagementUploadItem::class,'ecommerce_dt_management_upload_id','id');
    }
}
