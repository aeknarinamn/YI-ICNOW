<?php

namespace YellowProject\ConfirmationSetting;

use Illuminate\Database\Eloquent\Model;

class ConfirmationSettingCarousel extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dim_setting_confirmation_carousel';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'alt_text',
        'confimation_yes',
		'confimation_no',
		'max_display',
		'is_confimation',
    ];
}
