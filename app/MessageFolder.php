<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use YellowProject\Campaign;

class MessageFolder extends Model
{
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dim_message_folders';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
    ];


    /**
     * A flag to indicate whether or not to use timestamp on the record
     *
     * @var array
     */
    public $timestamps = false;

    //Relation
    public function campaign()
    {
        return $this->hasMany(Campaign::class);
    }

}
