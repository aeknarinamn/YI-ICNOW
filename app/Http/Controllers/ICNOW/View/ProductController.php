<?php

namespace YellowProject\Http\Controllers\ICNOW\View;

use Illuminate\Http\Request;
use YellowProject\Http\Controllers\Controller;
use YellowProject\ICNOW\Product\Product;
use YellowProject\LineUserProfile;
use YellowProject\ICNOW\Log\LogProductView;
use YellowProject\ICNOW\Log\LogSession;
use Carbon\Carbon;

class ProductController extends Controller
{
	public function productDetail($id)
	{
		if(!array_key_exists('line-user-id', $_COOKIE)){
            abort(404);
        }

        $dateNow = Carbon::now()->format('Y-m-d H:i:s');
        $lineUserId = $_COOKIE['line-user-id'];
        $lineUserProfile = LineUserProfile::find($lineUserId);
        $logSession = LogSession::where('line_user_id',$lineUserProfile->id)->where('is_active',1)->first();
        if($logSession){
        	$logSession->update([
        		'is_product_view' => 1
        	]);
        }
		$product = Product::find($id);
		if($lineUserProfile && $product){
			LogProductView::create([
				'line_user_id' => $lineUserProfile->id,
				'product_id' => $product->id
			]);
		}

		if($product->section_id == 1){
			$productImages = $product->productImages;
			$productDiyPersons = $product->productDiyPersons;
			$productDiyProductFocuses = $product->productDiyProductFocuses;
			$productDiyOtherOptions = $product->productDiyOtherOptions;
			$mainImage = $productImages->first();
			return view('icnow.product.diy')
				->with('dateNow',$dateNow)
				->with('lineUserProfile',$lineUserProfile)
				->with('product',$product)
				->with('productDiyPersons',$productDiyPersons)
				->with('productDiyProductFocuses',$productDiyProductFocuses)
				->with('productDiyOtherOptions',$productDiyOtherOptions)
				->with('mainImage',$mainImage);
		}else if($product->section_id == 2){
			$productImages = $product->productImages;
			$productPartySets = $product->productPartySets;
			$mainImage = $productImages->first();
			return view('icnow.product.party-set')
				->with('dateNow',$dateNow)
				->with('lineUserProfile',$lineUserProfile)
				->with('product',$product)
				->with('productPartySets',$productPartySets)
				->with('partySetCount',$productPartySets->count())
				->with('mainImage',$mainImage);
		}else if($product->section_id == 3){
			$productImages = $product->productImages;
			$productPartySets = $product->productCustoms;
			$mainImage = $productImages->first();
			return view('icnow.product.custom')
				->with('dateNow',$dateNow)
				->with('lineUserProfile',$lineUserProfile)
				->with('product',$product)
				->with('productPartySets',$productPartySets)
				->with('partySetCount',$productPartySets->count())
				->with('mainImage',$mainImage);
		}
	}

    public function diyPage()
    {
    	return view('icnow.product.diy');
    }

    public function partySetPage()
    {
    	return view('icnow.product.party-set');
    }
}
