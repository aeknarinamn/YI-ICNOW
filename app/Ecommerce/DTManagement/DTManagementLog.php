<?php

namespace YellowProject\Ecommerce\DTManagement;

use Illuminate\Database\Eloquent\Model;
use YellowProject\User;

class DTManagementLog extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'fact_ecommerce_dt_management_log';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'user_id',
    	'activity',
    	'action',
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
}
