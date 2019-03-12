<?php

namespace YellowProject;

use Illuminate\Database\Eloquent\Model;
use YellowProject\LocationItem;

class CoroselConfirmationItem extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'fact_corosel_confirmation';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'corosel_confirmation_id',
        'location_item_id',
        'is_active',
    ];

    public function locationItem()
    {
        return $this->belongsTo(LocationItem::class, 'location_item_id', 'id');
    }
}
