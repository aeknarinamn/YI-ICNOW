<?php

namespace YellowProject;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use YellowProject\LocationItem;

class Location extends Model
{
   	use SoftDeletes;

    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dim_location';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
		'description'
    ];

    public function locationItems()
    {
        return $this->hasMany(LocationItem::class,'location_id','id');
    }
}
