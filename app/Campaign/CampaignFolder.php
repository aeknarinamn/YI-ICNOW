<?php

namespace YellowProject\Campaign;

use Illuminate\Database\Eloquent\Model;

class CampaignFolder extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dim_campaign_folder';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'folder_name',
		'description',
    ];
}
