<?php

namespace YellowProject;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use YellowProject\LineUserProfile;
use YellowProject\Field;
use YellowProject\SubscriberItem;
use YellowProject\SubscriberLine;

class CampaignMessage extends Model
{
    use SoftDeletes;
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'campaign_message';
    protected $appends = ['message_sent'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'message',//playload
        'display'
    ];

    public static function getMessageSentAttribute()
    {
        //return self::encodeMessageEmo($this->message);
    }

    // public static function encodeMessageEmo($string)
    // {
    //     $keyword = $string;
    //     $valueForQuery = collect();
    //     $regStrings = preg_split("/[@##][@###]+/",$string);
    //     foreach ($regStrings as $regString) {
    //       if(trim($regString) !=''){
    //             $first = substr($regString, 0, 2);
    //             if($first == '{[') {
    //                 $last = substr($regString,-2);
    //                 if($last == ']}'){
    //                     $data = substr($regString,2,strlen($regString)-4);
    //                     $valueForQuery->push($data);
    //                 }
    //             }
    //         }
    //     }

    //     foreach($valueForQuery as $value){
    //         $data = str_replace(".png", "", $value);
    //         $lineEmoticon = LineEmoticon::where('file_name',$data)->first();
    //         $keyword = str_replace(trim('&nbsp;'), ' ', trim($keyword));
    //         if (!is_null($lineEmoticon)) {
    //             $keyword = str_replace('@##'.trim('{['.$value.']}@###'), $lineEmoticon->sent_unicode, trim($keyword));
    //         }
    //     }
    //     return $keyword;
    // }

    public static function encodeMessageEmo($string,$mid)
    {
        // \Log::debug('LineUserID => '.$lineUserprofile->id);
        $lineUserprofile = LineUserProfile::where('mid',$mid)->first();
        $keyword = $string;
        $valueForQuery = collect();
        $regStrings = preg_split("/[@##][@###]+/",$string);
        foreach ($regStrings as $regString) {
          if(trim($regString) !=''){
                $first = substr($regString, 0, 2);
                if($first == '{[') {
                    $last = substr($regString,-2);
                    if($last == ']}'){
                        $data = substr($regString,2,strlen($regString)-4);
                        $valueForQuery->push($data);
                    }
                }
            }
        }

        foreach($valueForQuery as $value){
            $data = str_replace(".png", "", $value);
            $lineEmoticon = LineEmoticon::where('file_name',$data)->first();
            $keyword = str_replace(trim('&nbsp;'), ' ', trim($keyword));
            if (!is_null($lineEmoticon)) {
                $keyword = str_replace('@##'.trim('{['.$value.']}@###'), $lineEmoticon->sent_unicode, trim($keyword));
            }
        }

        $keyword = str_replace(trim('&nbsp;'), ' ', trim($keyword));
        $keyword = preg_replace_callback("~\(([^\)]*)\)~", function($s) {
            return str_replace(" ", "%S", "($s[1])");
        }, $keyword);
        $payloads = explode(" ", $keyword);
        foreach ($payloads as $key => $value) {
            if($payloads[$key] != ""){
                // preg_match('#\[(.*?)\]#', $payloads[$key], $match);
                $payloads[$key] = str_replace("%S", " ", $payloads[$key]);
                preg_match('#\(\[.*?\]\)#', $payloads[$key], $match);
                if(count($match) > 0) {
                    $keyword = str_replace('([', '', $match[0]);
                    $keyword = str_replace('])', '', $keyword);
                    $match[0] = trim($keyword);
                    if($match[0] == 'displayName'){
                        $payloads[$key] = $lineUserprofile->name;
                        // $payloads[$key] = $ecomOrderList->order_id;
                    }else{
                        $field = Field::where('name',$match[0])->first();
                        if($field){
                            $subscriberLine = SubscriberLine::where('subscriber_id',$field->subscriber_id)->where('line_user_id',$lineUserprofile->id)->first();
                            if($subscriberLine){
                                $subscriberItem = SubscriberItem::where('subscriber_line_id',$subscriberLine->id)->where('field_id',$field->id)->first();
                                if($subscriberItem){
                                    $payloads[$key] = $subscriberItem->value;
                                }else{
                                    $payloads[$key] = $field->personalize_default;
                                }
                            }else{
                                $payloads[$key] = $field->personalize_default;
                            }
                        }
                    }
                }
            }
        }
        $keyword = implode(" ", $payloads);
        $keyword = str_replace('##newline##', PHP_EOL, $keyword);
        return trim($keyword);
    }
}
