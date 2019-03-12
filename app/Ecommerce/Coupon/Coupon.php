<?php

namespace YellowProject\Ecommerce\Coupon;

use Illuminate\Database\Eloquent\Model;
use YellowProject\Ecommerce\Coupon\CouponSection;
use YellowProject\Ecommerce\Coupon\CouponGenerate;
use YellowProject\Ecommerce\Coupon\CouponProduct;
use YellowProject\Ecommerce\Coupon\CouponProductCategory;
use YellowProject\Ecommerce\Coupon\CouponDiscount;
use YellowProject\Ecommerce\Coupon\ImageFile;
use YellowProject\LineUserProfile;
use YellowProject\TrackingBc;
use Carbon\Carbon;
use URL;

class Coupon extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dim_ecommerce_coupon';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'code',
        'prefix_name',
        'is_running_number',
        'start_date',
        'end_date',
        'user_per_coupon',
        'user_per_customer',
        'sort_order',
        'disply_on_main_page',
        'coupon_image',
        'desc',
        'tracking_bc_id',
        'minimum_purchase',
        'apply_to_customer_wallet',
        'img_id',
    ];

    public function couponSection()
    {
        return $this->hasMany(CouponSection::class,'ecommerce_coupon_id','id');
    }

    public function couponGenerate()
    {
        return $this->hasMany(CouponGenerate::class,'ecommerce_coupon_id','id');
    }

    public function Products()
    {
        return $this->hasMany(CouponProduct::class,'ecommerce_coupon_id','id');
    }

    public function Categories()
    {
        return $this->hasMany(CouponProductCategory::class,'ecommerce_coupon_id','id');
    }

    public function Discounts()
    {
        return $this->hasMany(CouponDiscount::class,'ecommerce_coupon_id','id');
    }

    // public function couponUsers()
    // {
    //     return $this->hasMany(CouponUser::class,'coupon_id','id');
    // }

    // public function couponUserReedeems()
    // {
    //     return $this->hasMany(CouponReedeem::class,'coupon_id','id');
    // }

    // public function userCheckCoupons()
    // {
    //     return $this->hasMany(UserCheckCoupon::class,'coupon_id','id');
    // }

    // public function couponReedeemCode()
    // {
    //     return $this->hasOne(CouponReedeemCode::class,'coupon_id','id');
    // }

    public function trackingBc()
    {
        return $this->belongsto(TrackingBc::class,'tracking_bc_id','id');
    }

    public function image()
    {
        return $this->belongsto(ImageFile::class,'img_id','id');
    }

    public static function genCouponFirstRegister()
    {
        $genImageCoupon = self::genImageCoupon();
        $couponFirstRegister1 = Coupon::where('code','DISCOUNT-500')->first();
        if(!$couponFirstRegister1){
            $code = 'DISCOUNT-500';
            $name = 'DISCOUNT-500';
            $discount = 500;
            self::genCoupon($code,$name,$genImageCoupon,$discount);
        }
        $couponFirstRegister2 = Coupon::where('code','DISCOUNT-100-1')->first();
        if(!$couponFirstRegister2){
            $code = 'DISCOUNT-100-1';
            $name = 'DISCOUNT-100';
            $discount = 100;
            self::genCoupon($code,$name,$genImageCoupon,$discount);
        }
        $couponFirstRegister3 = Coupon::where('code','DISCOUNT-100-2')->first();
        if(!$couponFirstRegister3){
            $code = 'DISCOUNT-100-2';
            $name = 'DISCOUNT-100';
            $discount = 100;
            self::genCoupon($code,$name,$genImageCoupon,$discount);
        }
        $couponFirstRegister4 = Coupon::where('code','DISCOUNT-100-3')->first();
        if(!$couponFirstRegister4){
            $code = 'DISCOUNT-100-3';
            $name = 'DISCOUNT-100';
            $discount = 100;
            self::genCoupon($code,$name,$genImageCoupon,$discount);
        }
    }

    public static function genCoupon($code,$name,$genImageCoupon,$discount)
    {
        $coupon = Coupon::create([
            'name' => $name,
            'code' => $code,
            'prefix_name' => null,
            'is_running_number' => 1,
            'start_date' => '2018-01-01 00:00:00',
            'end_date' => '2025-01-01 00:00:00',
            'user_per_coupon' => 999999,
            'user_per_customer' => 999999,
            'sort_order' => 0,
            'disply_on_main_page' => 1,
            'coupon_image' => $genImageCoupon->id,
            'desc' => 'คูปองส่วนลดสำหรับการซื้อครั้งแรก',
            'tracking_bc_id' => null,
            'minimum_purchase' => 2500,
            'apply_to_customer_wallet' => 0,
            'img_id' => $genImageCoupon->id,
        ]);

        CouponDiscount::create([
            'ecommerce_coupon_id' => $coupon->id,
            'type' => 'bath',
            'discount' => $discount,
            'total_amount' => $discount
        ]);
    }

    public static function genImageCoupon()
    {
        $baseUrl = \URL::to('/');
        $imageFile = ImageFile::create([
            'img_url' => $baseUrl."/ecommerce/temp-coupon/coupon-500.png",
            'img_size' => null,
            'type' => "coupon-first-register"
        ]);

        return $imageFile;
    }

    // public static function sentPayload($payload,$couponReedeemCode,$lineUserProfile,$isRunningNumber)
    // {
    //     $string='';
    //     $subscriberID = '';
    //     $keyword = '';
    //     $newPayloads = $payload;

    //     $newPayloads = str_replace(trim('&nbsp;'), ' ', trim($newPayloads));
    //     $newPayloads = str_replace(trim(' '), ' ', trim($newPayloads));
    //     $newPayloads = preg_replace('#(www\.|https?:\/\/){1}[a-zA-Z0-9]{2,}\.[a-zA-Z0-9]{2,}(\S*)#i', ' $0', $newPayloads);
    //     $keywords = preg_split("/\s+/", $newPayloads);
    //     foreach ($keywords as $key => $messageText) {
    //         $string .= " ".$messageText;
    //     }
    //     $keyword = $string;
    //     // dd($keyword);
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
    //         // dd($lineEmoticon->sent_unicode);
    //         if(!is_null($lineEmoticon)){
    //             $keyword = str_replace('&nbsp;', ' ', trim($keyword));
    //             $keyword = str_replace('@##'.trim('{['.$value.']}@###'), ' '.$lineEmoticon->sent_unicode, trim($keyword));
    //         }
    //     }
    //     $keyword = preg_replace_callback("~\(([^\)]*)\)~", function($s) {
    //         return str_replace(" ", "%S", "($s[1])");
    //     }, $keyword);
    //     $payloads = explode(" ", $keyword);

    //     foreach ($payloads as $key => $value) {
    //         if($payloads[$key] != ""){
    //             // preg_match('#\<(.*?)\>#', $payloads[$key], $match);
    //             $payloads[$key] = str_replace("%S", " ", $payloads[$key]);
    //             preg_match('#\(\[.*?\]\)#', $payloads[$key], $match);
    //             if(count($match) > 0){
    //                 $keyword = str_replace('([', '', $match[0]);
    //                 $keyword = str_replace('])', '', $keyword);
    //                 $match[0] = trim($keyword);
    //                 // if($match[0] == 'prefixCouponCode'){
    //                 //     $payloads[$key] = $couponReedeemCode->prefix_code;
    //                 //     // $payloads[$key] = $ecomOrderList->order_id;
    //                 // }
    //                 if($match[0] == 'code'){
    //                     if($isRunningNumber == 1){
    //                         $payloads[$key] = $couponReedeemCode->prefix_code.$couponReedeemCode->running_code;
    //                     }else{
    //                         $payloads[$key] = $couponReedeemCode->prefix_code;
    //                     }
    //                     // $payloads[$key] = $ecomOrderList->order_id;
    //                 }
    //                 if($match[0] == 'displayName'){
    //                     $payloads[$key] = $lineUserProfile->name;
    //                     // $payloads[$key] = $ecomOrderList->order_id;
    //                 }
    //             }
    //         }
    //     }
    //     // dd($payloads);
    //     $keyword = implode(" ", $payloads);

    //     $keyword = preg_replace("/<span[^>]+\>/i", "", $keyword);
    //     // $keyword = str_replace('\n', PHP_EOL, $keyword);
    //     $keyword = str_replace(' ##newline## ', PHP_EOL, $keyword);
    //     return $keyword;
    // }

    // public static function genImage($path,$couponSection,$datas,$lineUserProfileId,$coupon,$couponReedeemCode)
    // {
    //     $dataRenders = [];
    //     $dateNow = Carbon::now()->format('dmY_His');
    //     $img = \Image::make($path); 
    //     $lineUserProfile = LineUserProfile::find($lineUserProfileId);
    //     $defaultFront = 'static/fonts/DB Helvethaica X v3.2.8c4629e.ttf';
    //     $directory3 = 'ecommerce/coupon_mycoupon/'.$lineUserProfileId.'/'.$coupon->id.'/'.$couponSection->section_name;
    //     if (!\File::isDirectory($directory3)){
    //         $result = \File::makeDirectory($directory3, 0775, true);
    //     }

    //     $fileName = $dateNow.'.png';

    //     $img->save(public_path($directory3.'/'.$fileName));

    //     if(array_key_exists('items', $datas) && count($datas['items']) > 0){
    //         foreach ($datas['items'] as $key => $item) {
    //             $img = \Image::make(URL::to('/').'/'.$directory3.'/'.$fileName);
    //             $img->resize(520, 520);
    //             $sectionItem = $item['section_item'];
    //             $title = $sectionItem->title;
    //             $type = $sectionItem->type;
    //             $width = $sectionItem->width;
    //             $height = $sectionItem->height;
    //             $setting = $item['setting']->first();
    //             $css = $item['css'];

    //             if($type == 'image'){
    //                 $x = $sectionItem->x;
    //                 $y = $sectionItem->y;
    //                 $imageLink =  $setting->value;
    //                 if($title == 'Image'){
    //                     $imageSection = \Image::make($imageLink);
    //                 }else{
    //                     $imageSection = \Image::make($lineUserProfile->avatar);
    //                 }
    //                 $imageSection->resize($width, $height);
    //                 $img->insert($imageSection, null, $x, $y);
    //             }else if($type == 'ImageAvartar'){
    //                 $x = $sectionItem->x;
    //                 $y = $sectionItem->y;
    //                 $imageLink =  $setting->value;
    //                 $imageSection = \Image::make($lineUserProfile->avatar); 
    //                 $imageSection->resize($width, $height);
    //                 $img->insert($imageSection, null, $x, $y);
    //             }else{
    //                 $x = $sectionItem->x-10;
    //                 $y = $sectionItem->y+35;
    //                 $cssColor = $css->where('key','color')->first();
    //                 $cssSize = $css->where('key','font-size')->first();
    //                 $fontSize = ($cssSize != '')? (int)$cssSize->value : null;
    //                 $fontColor = ($cssColor != '')? $cssColor->value : null;
    //                 $text = self::sentPayload($setting->value,$couponReedeemCode,$lineUserProfile,$coupon->is_running_number);

    //                 $img->text($text, $x, $y, function($font) use($fontSize,$fontColor,$defaultFront) {  
    //                     $font->file(public_path($defaultFront));
    //                     $font->size($fontSize);
    //                     $font->color($fontColor);
    //                 });
    //             }

    //             $img->resize(1040, 1040);
    //             $img->save(public_path($directory3.'/'.$fileName));
    //         }
    //     }

    //     return URL::to('/').'/'.$directory3.'/'.$fileName;
    // }

    // public static function storeCoupon($coupon,$lineUserProfileId)
    // {
    //     // $coupons = Coupon::all();
    //     $couponReedeemCode = self::genCouponCodeReedeem($coupon,$lineUserProfileId);
    //     $datasStore = [];
    //     $datasStore['start_date'] = $coupon->start_date;
    //     $datasStore['end_date'] = $coupon->end_date;
    //     $datasStore['coupon_id'] = $coupon->id;
    //     $datasStore['line_user_id'] = $lineUserProfileId;
    //     $couponSections = $coupon->couponSection;
    //     foreach ($couponSections as $sectionIndex => $couponSection) {
    //       $datas = [];
    //       $couponSectionItems = $couponSection->couponItems;
    //       if($couponSection->img_id != ""){
    //         $image = $couponSection->imageFile;
    //         foreach ($couponSectionItems as $key => $couponSectionItem) {
    //           $datas['items'][$key]['section_item'] = $couponSectionItem;
    //           $datas['items'][$key]['css'] = $couponSectionItem->couponItemCss;
    //           $datas['items'][$key]['setting'] = $couponSectionSettings = $couponSectionItem->couponItemSettings;
    //         }
    //         $imgUrl = Coupon::genImage($image->img_url,$couponSection,$datas,$lineUserProfileId,$coupon,$couponReedeemCode);
    //         if($couponSection->section_name == 'coupon_section'){
    //           $datasStore['coupon_section_img_url'] = $imgUrl;
    //         }else if($couponSection->section_name == 'coupon_failed'){
    //           $datasStore['coupon_fail_img_url'] = $imgUrl;
    //         }else{
    //           $datasStore['coupon_reedeem_img_url'] = $imgUrl;
    //         }
    //       }
    //     }
    //     $couponUser = CouponUser::where('line_user_id',$lineUserProfileId)->where('coupon_id',$coupon->id)->first();
    //     if($couponUser){
    //       // $couponUser->update($datasStore);
    //     }else{
    //         $couponUser = CouponUser::create($datasStore);
    //     }
    // }

    // public static function genCouponCodeReedeem($coupon,$lineUserProfileId)
    // {
    //     if($coupon->is_running_number == 1){
    //         $code = self::genCouponRandom($coupon);
    //     }else{
    //         $code = null;
    //     }

    //     if($coupon->is_famliy != null){
    //         $prefixName = ($coupon->prefix_api != "")? $coupon->prefix_api : 'CP';
    //     }else{
    //         $prefixName = ($coupon->prefix_name != "")? $coupon->prefix_name : 'MK';
    //     }
    //     // $runningNumber = $countCouponReedeemCode+1;
    //     $couponReedeemCode = CouponReedeemCode::create([
    //         'coupon_id' => $coupon->id,
    //         'line_user_id' => $lineUserProfileId,
    //         'prefix_code' => $prefixName,
    //         'running_code' => $code,
    //     ]);

    //     return $couponReedeemCode;
    // }

    // public static function genCouponRandom($coupon)
    // {
    //     $code = "";
    //     $checkCode = 0;
    //     while ( $checkCode == 0) {
    //         $randomCode = rand(11111,99999);
    //         $couponReedeemCodeCheck = CouponReedeemCode::where('coupon_id',$coupon->id)->where('running_code',$randomCode)->first();
    //         if($couponReedeemCodeCheck == ""){
    //             $code = $randomCode;
    //             $checkCode = 1;
    //         }
    //     }

    //     return str_pad($code, 5, '0', STR_PAD_LEFT);
    // }
}
