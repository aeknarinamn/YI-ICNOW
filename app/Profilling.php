<?php

namespace YellowProject;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use YellowProject\ProfillingAction;
use YellowProject\Subscriber;
use YellowProject\SubscriberLine;
use YellowProject\ProfillingFolder;

class Profilling extends Model
{
    public $timestamps = true;
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dim_profilling';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'folder_id',
        'is_route_expire',
        'route_expire_date',
        'route_expire_url',
        'lead_form_name',
        'subscriber_id',
        'color_wallpaper',
        'page_color',
        'route',
        'redirect_route',
        'is_active',
        'max_page_width',
        'is_use_hellosoda',
        'route_hellosoda',
    ];

    public function ProfillingActions()
    {
        return $this->hasMany(ProfillingAction::class,'profilling_id','id');
    }

    public function subscriber()
    {
        return $this->belongsTo(Subscriber::class,'subscriber_id','id');
    }

    public function profillingFolder()
    {
        return $this->belongsTo(ProfillingFolder::class,'folder_id','id');
    }

    public static function checkSubscriberData($lineUserId,$profilling)
    {
        $isSubscribe = 0;
        $subscriberId = $profilling->subscriber_id;
        if($subscriberId != 0){
            $susbcriber = $profilling->subscriber;
            $subscriberLine = SubscriberLine::where('subscriber_id',$subscriberId)->where('line_user_id',$lineUserId)->first();
            if($subscriberLine){
                $subscriberItems = $subscriberLine->subscriberItems;
                if($subscriberItems){
                    $fields = $susbcriber->fields;
                    $listFieldReqs = $fields->where('is_required',1)->pluck('id')->toArray();
                    if(count($listFieldReqs) > 0){
                        $subscriberItems = $subscriberItems->whereIn('field_id',$listFieldReqs);
                        foreach ($subscriberItems as $key => $subscriberItem) {
                            if($subscriberItem->value != ""){
                                if(($key = array_search($subscriberItem->field_id, $listFieldReqs)) !== false) {
                                    unset($listFieldReqs[$key]);
                                }
                                // $isSubscribe = 0;
                            }
                        }
                        if(count($listFieldReqs) > 0){
                            $isSubscribe = 0;
                        }else{
                            $isSubscribe = 1;
                        }
                    }else{
                        $isSubscribe = 1;
                    }
                }
            }
        }

        return $isSubscribe;
    }

}
