<?php

namespace YellowProject\Http\Controllers\ICNOW\View;

use Illuminate\Http\Request;
use YellowProject\Http\Controllers\Controller;
use YellowProject\ICNOW\BannerCarousel\BannerCarousel;
use YellowProject\ICNOW\Product\Product;
use YellowProject\ICNOW\Section\SectionImages;
use YellowProject\ICNOW\OrderCustomer\CustomerShippingAddress;
use YellowProject\LineUserProfile;

class HomeController extends Controller
{
    public function homePage()
    {
        if(!array_key_exists('line-user-id', $_COOKIE)){
            abort(404);
        }
        $isAddress = 0;
        $lineUserId = $_COOKIE['line-user-id'];
        $customerShippingAddress = CustomerShippingAddress::where('line_user_id',$lineUserId)->first();
        if($customerShippingAddress){
            $isAddress = 1;
        }

    	$bannerCarousel = BannerCarousel::where('carousel_name','Home')->first();
    	$bannerCarouseImages = $bannerCarousel->bannerCarouseImages;
    	$productPartySets = \DB::table('dim_icnow_product as p')
    		->select(
    			'p.*',
    			'pi.img_url'
    		)
            ->leftjoin('dim_icnow_product_images as pi','pi.icnow_product_id','=','p.id')
            ->where('p.section_id',1)
            ->where('p.is_active',1)
            ->orderByDesc('p.section_id')->orderBy('p.sort_order')->orderByDesc('p.updated_at')
    		->groupBy('p.id')
    		->get();
        $productDiys = \DB::table('dim_icnow_product as p')
            ->select(
                'p.*',
                'pi.img_url'
            )
            ->leftjoin('dim_icnow_product_images as pi','pi.icnow_product_id','=','p.id')
            ->where('p.section_id',2)
            ->where('p.is_active',1)
            ->orderByDesc('p.section_id')->orderBy('p.sort_order')->orderByDesc('p.updated_at')
            ->groupBy('p.id')
            ->get();
        $productCustoms = \DB::table('dim_icnow_product as p')
            ->select(
                'p.*',
                'pi.img_url'
            )
            ->leftjoin('dim_icnow_product_images as pi','pi.icnow_product_id','=','p.id')
            ->where('p.section_id',3)
            ->where('p.is_active',1)
            ->orderByDesc('p.section_id')->orderBy('p.sort_order')->orderByDesc('p.updated_at')
            ->groupBy('p.id')
            ->get();
        $sectionImages = SectionImages::all();
    	return view('icnow.home.index')
            ->with('lineUserId',$lineUserId)
    		->with('bannerCarouseImages',$bannerCarouseImages)
    		->with('bannerCarousel',$bannerCarousel)
            ->with('sectionImages',$sectionImages)
            ->with('productPartySets',$productPartySets)
            ->with('productDiys',$productDiys)
            ->with('productCustoms',$productCustoms)
    		->with('isAddress',$isAddress);
    }
}
