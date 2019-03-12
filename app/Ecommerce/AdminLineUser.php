<?php

namespace YellowProject\Ecommerce;

use Illuminate\Database\Eloquent\Model;
use YellowProject\LineUserProfile;

class AdminLineUser extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dim_ecommerce_admin_lineuser';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'line_user_id',
    	'email',
        'is_user',
    ];

    public function lineUserProfile()
    {
        return $this->belongsTo(LineUserProfile::class,'line_user_id','id');
    }
}
