<?php

namespace YellowProject;

use Illuminate\Database\Eloquent\Model;
use YellowProject\FieldItem;
use YellowProject\ProfillingAction;
use YellowProject\FieldFolder;
use YellowProject\Subscriber;

class Field extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dim_fields';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'folder_id',
        'name',
        'type',
        'description',
        'is_personalize',
        'primary_key',
        'is_segment',
        'subscriber_id',
        'field_name',
        'personalize_default',
        'api_url',
        'is_required',
        'is_readonly',
        'is_api',
        'is_encrypt',
        'is_master_subscriber',
        'is_master_subscriber_update',
        'mapping_master_field',
    ];

    public function fieldItems()
    {
        return $this->hasMany(FieldItem::class,'dim_fields_id','id');
    }

    public function profillingActions()
    {
        return $this->hasMany(ProfillingAction::class,'field_id','id');
    }

    public function folder()
    {
        return $this->belongsto(FieldFolder::class,'folder_id','id');
    }

    public function subscriber()
    {
        return $this->belongsto(Subscriber::class,'subscriber_id','id');
    }

    // public function setIsPersonalizeAttribute($personalize)
    // {
    //     if ($personalize == "on")
    //     {
    //         $personalize = 1;
    //     }

    //     $this->attributes['is_personalize'] = $personalize ;
    // }

    // public function setIsSegmentAttribute($isSegment)
    // {
    //     if ($isSegment == "on")
    //     {
    //         $isSegment = 1;
    //     }

    //     $this->attributes['is_segment'] = $isSegment ;
    // }

    // public function setPrimaryKeyAttribute($isPrimaryKey)
    // {
    //     if ($isPrimaryKey == "on")
    //     {
    //         $isPrimaryKey = 1;
    //     }

    //     $this->attributes['primary_key'] = $isPrimaryKey ;
    // }

    // public function setIsRequiredAttribute($isRequired)
    // {
    //     if ($isRequired == "on")
    //     {
    //         $isRequired = 1;
    //     }

    //     $this->attributes['is_required'] = $isRequired ;
    // }

    // public function setIsReadonlyAttribute($isReadonly)
    // {
    //     if ($isReadonly == "on")
    //     {
    //         $isReadonly = 1;
    //     }

    //     $this->attributes['is_readonly'] = $isReadonly ;
    // }

    // public function setIsApiAttribute($isApi)
    // {
    //     if ($isApi == "on")
    //     {
    //         $isApi = 1;
    //     }

    //     $this->attributes['is_api'] = $isApi ;
    // }

}
