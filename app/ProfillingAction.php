<?php

namespace YellowProject;

use Illuminate\Database\Eloquent\Model;
use YellowProject\Profilling;
use YellowProject\ProfillingActionCss;
use YellowProject\ProfillingActionParentCss;
use YellowProject\ProfillingActionParentSetting;
use YellowProject\ProfillingActionLabelCss;
use YellowProject\Field;

class ProfillingAction extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dim_profilling_action';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'profilling_id',
        'title',
        'type',
        'field_id',
        'seq_no',
    ];

    protected $primaryKey = 'pri_id';

    public function css()
    {
        return $this->hasMany(ProfillingActionCss::class,'profilling_action_id','pri_id');
    }

    public function labelCss()
    {
        return $this->hasMany(ProfillingActionLabelCss::class,'profilling_action_id','pri_id');
    }

    public function parentCss()
    {
        return $this->hasMany(ProfillingActionParentCss::class,'profilling_action_id','pri_id');
    }

    public function settings()
    {
        return $this->hasMany(ProfillingActionSetting::class,'profilling_action_id','pri_id');
    }

    public function field()
    {
        return $this->belongsTo(Field::class,'field_id','id');
    }

    public function profilling()
    {
        return $this->belongsTo(Profilling::class,'profilling_id','id');
    }

}
