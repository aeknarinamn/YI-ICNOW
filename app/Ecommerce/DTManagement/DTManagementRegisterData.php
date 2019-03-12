<?php

namespace YellowProject\Ecommerce\DTManagement;

use Illuminate\Database\Eloquent\Model;
use YellowProject\LineUserProfile;
use YellowProject\User;

class DTManagementRegisterData extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dim_ecommerce_dt_management_register_data';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'line_user_id',
    	'user_id',
    	'dt_code',
    ];

    public function lineUserProfile()
    {
        return $this->belongsTo(LineUserProfile::class,'line_user_id','id');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
}
