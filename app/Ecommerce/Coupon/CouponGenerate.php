<?php

namespace YellowProject\Ecommerce\Coupon;

use Illuminate\Database\Eloquent\Model;
use YellowProject\Ecommerce\Coupon\Coupon;
use Carbon\Carbon;
use URL;

class CouponGenerate extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'fact_ecommerce_coupon_generate';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ecommerce_coupon_id',
		'coupon_section_img_url',
		'coupon_fail_img_url',
		'coupon_reedeem_img_url',
		'start_date',
		'end_date',
		'flag_status',
    ];

    public function coupon()
    {
        return $this->belongsto(Coupon::class,'ecommerce_coupon_id','id');
    }

    // public function userCheckCoupons()
    // {
    //     return $this->hasMany(UserCheckCoupon::class,'coupon_id','id');
    // }

    public static function sentPayload($payload)
    {
        $string='';
        $subscriberID = '';
        $keyword = '';
        $newPayloads = $payload;

        $newPayloads = str_replace(trim('&nbsp;'), ' ', trim($newPayloads));
        $newPayloads = str_replace(trim(' '), ' ', trim($newPayloads));
        $newPayloads = preg_replace('#(www\.|https?:\/\/){1}[a-zA-Z0-9]{2,}\.[a-zA-Z0-9]{2,}(\S*)#i', ' $0', $newPayloads);
        $keywords = preg_split("/\s+/", $newPayloads);
        foreach ($keywords as $key => $messageText) {
            $string .= " ".$messageText;
        }
        $keyword = $string;
        // dd($keyword);
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
            // dd($lineEmoticon->sent_unicode);
            if(!is_null($lineEmoticon)){
                $keyword = str_replace('&nbsp;', ' ', trim($keyword));
                $keyword = str_replace('@##'.trim('{['.$value.']}@###'), ' '.$lineEmoticon->sent_unicode, trim($keyword));
            }
        }
        $keyword = preg_replace_callback("~\(([^\)]*)\)~", function($s) {
            return str_replace(" ", "%S", "($s[1])");
        }, $keyword);
        $payloads = explode(" ", $keyword);

        foreach ($payloads as $key => $value) {
            if($payloads[$key] != ""){
                // preg_match('#\<(.*?)\>#', $payloads[$key], $match);
                $payloads[$key] = str_replace("%S", " ", $payloads[$key]);
                preg_match('#\(\[.*?\]\)#', $payloads[$key], $match);
                if(count($match) > 0){
                    $keyword = str_replace('([', '', $match[0]);
                    $keyword = str_replace('])', '', $keyword);
                    $match[0] = trim($keyword);
                    if($match[0] == 'prefixCouponCode'){
                        $payloads[$key] = 'MK';
                        // $payloads[$key] = $ecomOrderList->order_id;
                    }
                    if($match[0] == 'couponCode'){
                        // $payloads[$key] = $ecomOrderList->order_id;
                    }
                }
            }
        }
        // dd($payloads);
        $keyword = implode(" ", $payloads);

        $keyword = preg_replace("/<span[^>]+\>/i", "", $keyword);
        // $keyword = str_replace('\n', PHP_EOL, $keyword);
        $keyword = str_replace(' ##newline## ', PHP_EOL, $keyword);
        return $keyword;
    }

    public static function genImage($path,$couponSection,$datas,$coupon)
    {
        $dateNow = Carbon::now()->format('dmY_His');
        $img = \Image::make($path); 
        $defaultFront = 'static/fonts/DB Helvethaica X v3.2.8c4629e.ttf';
        $directory = 'ecommerce/coupon_special_deal/'.$coupon->id.'/'.$couponSection->section_name;
        
        if (!\File::isDirectory($directory)){
            $result = \File::makeDirectory($directory, 0775, true);
        }

        $fileName = $dateNow.'.png';

        $img->save(public_path($directory.'/'.$fileName));

        if(array_key_exists('items', $datas) && count($datas['items']) > 0){
            foreach ($datas['items'] as $key => $item) {
                $img = \Image::make(URL::to('/').'/'.$directory.'/'.$fileName);
                $img->resize(520, 520);
                $sectionItem = $item['section_item'];
                $type = $sectionItem->type;
                $width = $sectionItem->width;
                $height = $sectionItem->height;
                $setting = $item['setting']->first();
                $css = $item['css'];

                if($type == 'image'){
                    $x = $sectionItem->x;
                    $y = $sectionItem->y;
                    $imageLink =  $setting->value;
                    $imageSection = \Image::make($imageLink); 
                    $imageSection->resize($width, $height);
                    $img->insert($imageSection, null, $x, $y);
                }else if($type == 'ImageAvartar'){
                    
                }else{
                    $x = $sectionItem->x-10;
                    $y = $sectionItem->y+35;
                    $cssColor = $css->where('key','color')->first();
                    $cssSize = $css->where('key','font-size')->first();
                    $fontSize = ($cssSize != '')? (int)$cssSize->value : null;
                    $fontColor = ($cssColor != '')? $cssColor->value : null;
                    $text = self::sentPayload($setting->value);

                    $img->text($text, $x, $y, function($font) use($fontSize,$fontColor,$defaultFront) {  
                        $font->file(public_path($defaultFront));
                        $font->size($fontSize);
                        $font->color($fontColor);
                    });
                }
                $img->resize(1040, 1040);
                $img->save(public_path($directory.'/'.$fileName));
            }
        }
        

        return URL::to('/').'/'.$directory.'/'.$fileName;
    }

    public static function storeCoupon($coupon)
    {
        $datasStore = [];
        $datasStore['start_date'] = $coupon->start_date;
        $datasStore['end_date'] = $coupon->end_date;
        $datasStore['ecommerce_coupon_id'] = $coupon->id;
        $couponSections = CouponSection::where('ecommerce_coupon_id',$coupon->id)->get();
        foreach ($couponSections as $sectionIndex => $couponSection) {
          $datas = [];
          $couponSectionItems = $couponSection->couponItems;
          if($couponSection->img_id != ""){
            $image = $couponSection->imageFile;
            foreach ($couponSectionItems as $key => $couponSectionItem) {
              $datas['items'][$key]['section_item'] = $couponSectionItem;
              $datas['items'][$key]['css'] = $couponSectionItem->couponItemCss;
              $datas['items'][$key]['setting'] = $couponSectionItem->couponItemSettings;
            }
            $imgUrl = CouponGenerate::genImage($image->img_url,$couponSection,$datas,$coupon);
            if($couponSection->section_name == 'coupon_section'){
              $datasStore['coupon_section_img_url'] = $imgUrl;
            }else if($couponSection->section_name == 'coupon_failed'){
              $datasStore['coupon_fail_img_url'] = $imgUrl;
            }else{
              $datasStore['coupon_reedeem_img_url'] = $imgUrl;
            }
          }
        }
        $couponGenerate = CouponGenerate::where('ecommerce_coupon_id',$coupon->id)->first();
        if($couponGenerate){
          $couponGenerate->update($datasStore);
        }else{
          CouponGenerate::create($datasStore);
        }
    }
}
