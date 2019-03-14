<?php

namespace YellowProject\Http\Controllers\ICNOW\View;

use Illuminate\Http\Request;
use YellowProject\Http\Controllers\Controller;
use YellowProject\ICNOW\Product\Product;
use YellowProject\ICNOW\ShoppingCart\ShoppingCart;
use YellowProject\ICNOW\ShoppingCart\ShoppingCartItem;
use YellowProject\ICNOW\ShoppingCart\ShoppingCartItemDetailDiy;
use YellowProject\ICNOW\ShoppingCart\ShoppingCartItemDetailDiyItem;
use YellowProject\ICNOW\ShoppingCart\ShoppingCartItemDetailPartySet;
use YellowProject\ICNOW\ShoppingCart\ShoppingCartItemDetailPartySetItem;
use YellowProject\ICNOW\ShoppingCart\ShoppingCartItemDetailCustom;
use YellowProject\ICNOW\ShoppingCart\ShoppingCartItemDetailCustomItem;
use YellowProject\LineUserProfile;
use YellowProject\ICNOW\Log\LogSession;
use Carbon\Carbon;

class ShoppingCartController extends Controller
{
    public function shoppingCartPage()
    {
    	// $lineUserId = $request->line_user_id;
        if(!array_key_exists('line-user-id', $_COOKIE)){
            abort(404);
        }
        $lineUserId = $_COOKIE['line-user-id'];
    	$dateNow = Carbon::now()->format('Y-m-d H:i:s');
    	$shoppingCart = ShoppingCart::where('line_user_id',$lineUserId)->where('is_active',1)->first();
    	// if(!$shoppingCart){
    	// 	abort(404);
    	// }
        $logSession = LogSession::where('line_user_id',$lineUserId)->where('is_active',1)->first();
        if($logSession){
            $logSession->update([
                'is_add_to_cart' => 1
            ]);
        }
    	$datas = \DB::table('fact_icnow_shopping_cart as sc')
    		->select(
    			'sci.*',
    			'pi.img_url'
    		)
            ->leftjoin('fact_icnow_shopping_cart_item as sci','sci.shopping_cart_id','=','sc.id')
    		->leftjoin('dim_icnow_product_images as pi','pi.icnow_product_id','=','sci.product_id')
            ->where('is_active',1)
            ->where('sc.line_user_id',$lineUserId)
    		->groupBy('sci.id')
    		->get();
        $datas = $datas->filter(function ($value, $key) {
            return $value->id != null;
        });

    	return view('icnow.cart.index')
    		->with('dateNow',$dateNow)
    		->with('datas',$datas);
    }

    public function saveShoppingCartDiy(Request $request)
    {
    	$dateNow = Carbon::now()->format('Y-m-d H:i:s');
    	$lineUserId = $request->line_user_id;
    	$productId = $request->product_id;
    	$quantity = $request->quantity;
    	$isPromotion = 0;
    	$shoppingCart = ShoppingCart::where('line_user_id',$lineUserId)->where('is_active',1)->first();
    	$product = Product::find($productId);
    	if($product->special_start_date != "" && $product->special_end_date != ""){
    		if($product->special_start_date <= $dateNow && $product->special_end_date >= $dateNow){
    			$isPromotion = 1;
    		}
    	}
    	if(!$shoppingCart){
    		$shoppingCart = ShoppingCart::create([
    			'line_user_id' => $lineUserId,
    			'is_active' => 1,
    			'is_product_update' => 0
    		]);
    	}

    	// $shoppingCartItem = ShoppingCartItem::where('shopping_cart_id',$shoppingCart->id)->where('product_id',$product->id)->first();
    	// if($shoppingCartItem){
    	// 	$oldQuantity = $shoppingCartItem->quantity;
    	// 	$newQuantity = $oldQuantity+$quantity;
    	// 	$price = $product->price;
    	// 	if($isPromotion == 1){
    	// 		$price = $product->special_price;
    	// 	}
    	// 	$retailPrice = $newQuantity*$price;
    	// 	$shoppingCartItem->update([
    	// 		'shopping_cart_id' => $shoppingCart->id,
    	// 		'line_user_id' => $lineUserId,
    	// 		'product_id' => $product->id,
    	// 		'product_name' => $product->product_name,
    	// 		'section_id' => $product->section_id,
    	// 		'product_desc' => $product->product_desc,
    	// 		'sku' => $product->sku,
    	// 		'price' => $product->price,
    	// 		'special_price' => $product->special_price,
    	// 		'special_start_date' => $product->special_start_date,
    	// 		'special_end_date' => $product->special_end_date,
    	// 		'retial_price' => $retailPrice,
    	// 		'quantity' => $newQuantity,
    	// 	]);
    	// }else{
    		$newQuantity = $quantity;
    		$price = $product->price;
    		if($isPromotion == 1){
    			$price = $product->special_price;
    		}
    		$retailPrice = $newQuantity*$price;
            $beforePriceDiscount = $newQuantity*$product->price;
    		$shoppingCartItem = ShoppingCartItem::create([
    			'shopping_cart_id' => $shoppingCart->id,
    			'line_user_id' => $lineUserId,
    			'product_id' => $product->id,
    			'product_name' => $product->product_name,
    			'section_id' => $product->section_id,
    			'product_desc' => $product->product_desc,
    			'sku' => $product->sku,
    			'price' => $product->price,
                'before_price_discount' => $beforePriceDiscount,
    			'special_price' => $product->special_price,
    			'special_start_date' => $product->special_start_date,
    			'special_end_date' => $product->special_end_date,
    			'retial_price' => $retailPrice,
    			'quantity' => $newQuantity,
    		]);
    	// }

    	$shoppingCartItemDetailDiys = ShoppingCartItemDetailDiy::where('shopping_cart_item_id',$shoppingCartItem->id)->get();
    	if($shoppingCartItemDetailDiys->count() > 0){
    		foreach ($shoppingCartItemDetailDiys as $key => $shoppingCartItemDetailDiy) {
    			ShoppingCartItemDetailDiyItem::where('shopping_cart_item_detail_diy_id',$shoppingCartItemDetailDiy->id)->delete();
    			$shoppingCartItemDetailDiy->delete();
    		}
    	}
    	$shoppingCartItemDetailDiy = ShoppingCartItemDetailDiy::create([
    		'shopping_cart_item_id' => $shoppingCartItem->id,
            'person_in_party' =>  $request->person_in_party,
    		'other_option' =>  $request->other_option,
    		'product_focus' =>  null,
    		'comment' =>  $request->comment,
    	]);
    	if($request->product_focus){
    		foreach ($request->product_focus as $key => $productfocus) {
    			ShoppingCartItemDetailDiyItem::create([
    				'shopping_cart_item_detail_diy_id' => $shoppingCartItemDetailDiy->id,
    				'value' => $productfocus
    			]);
    		}
    	}

    	return response()->json([
            'msg_return' => 'SUCCESS',
            'code_return' => 1,
        ]);
    }

    public function saveShoppingCartPartySet(Request $request)
    {
    	$dateNow = Carbon::now()->format('Y-m-d H:i:s');
    	$lineUserId = $request->line_user_id;
    	$productId = $request->product_id;
    	$quantity = $request->quantity;
    	$isPromotion = 0;
    	$shoppingCart = ShoppingCart::where('line_user_id',$lineUserId)->where('is_active',1)->first();
    	$product = Product::find($productId);
    	if($product->special_start_date != "" && $product->special_end_date != ""){
    		if($product->special_start_date <= $dateNow && $product->special_end_date >= $dateNow){
    			$isPromotion = 1;
    		}
    	}
    	if(!$shoppingCart){
    		$shoppingCart = ShoppingCart::create([
    			'line_user_id' => $lineUserId,
    			'is_active' => 1,
    			'is_product_update' => 0
    		]);
    	}
    	$newQuantity = $quantity;
		$price = $product->price;
		if($isPromotion == 1){
			$price = $product->special_price;
		}
		$retailPrice = $newQuantity*$price;
        $beforePriceDiscount = $newQuantity*$product->price;
		$shoppingCartItem = ShoppingCartItem::create([
			'shopping_cart_id' => $shoppingCart->id,
			'line_user_id' => $lineUserId,
			'product_id' => $product->id,
			'product_name' => $product->product_name,
			'section_id' => $product->section_id,
			'product_desc' => $product->product_desc,
			'sku' => $product->sku,
            'price' => $product->price,
			'before_price_discount' => $beforePriceDiscount,
			'special_price' => $product->special_price,
			'special_start_date' => $product->special_start_date,
			'special_end_date' => $product->special_end_date,
			'retial_price' => $retailPrice,
			'quantity' => $newQuantity,
		]);
		$shoppingCartItemDetailPartySets = ShoppingCartItemDetailPartySet::where('shopping_cart_item_id',$shoppingCartItem->id)->get();
    	if($shoppingCartItemDetailPartySets->count() > 0){
    		foreach ($shoppingCartItemDetailPartySets as $key => $shoppingCartItemDetailPartySet) {
    			ShoppingCartItemDetailPartySetItem::where('shopping_cart_item_party_set_id',$shoppingCartItemDetailPartySet->id)->delete();
    			$shoppingCartItemDetailPartySet->delete();
    		}
    	}
    	$items = $request->items;
    	foreach ($items as $key => $item) {
    		$item['shopping_cart_item_id'] = $shoppingCartItem->id;
    		$shoppingCartItemDetailPartySet = ShoppingCartItemDetailPartySet::create($item);
    		$itemChooses = $item['group_items'];
    		foreach ($itemChooses as $key => $itemChoose) {
    			$itemChoose['shopping_cart_item_party_set_id'] = $shoppingCartItemDetailPartySet->id;
    			ShoppingCartItemDetailPartySetItem::create($itemChoose);
    		}
    	}


    	return response()->json([
            'msg_return' => 'SUCCESS',
            'code_return' => 1,
        ]);
    }

    public function saveShoppingCartCustom(Request $request)
    {
        $dateNow = Carbon::now()->format('Y-m-d H:i:s');
        $lineUserId = $request->line_user_id;
        $productId = $request->product_id;
        $isPromotion = 0;
        $shoppingCart = ShoppingCart::where('line_user_id',$lineUserId)->where('is_active',1)->first();
        $product = Product::find($productId);
        
        if(!$shoppingCart){
            $shoppingCart = ShoppingCart::create([
                'line_user_id' => $lineUserId,
                'is_active' => 1,
                'is_product_update' => 0
            ]);
        }
        $price = $request->total_price;
        $shoppingCartItem = ShoppingCartItem::create([
            'shopping_cart_id' => $shoppingCart->id,
            'line_user_id' => $lineUserId,
            'product_id' => $product->id,
            'product_name' => $product->product_name,
            'section_id' => $product->section_id,
            'product_desc' => $product->product_desc,
            'sku' => $product->sku,
            'price' => $price,
            'before_price_discount' => $price,
            'special_price' => $price,
            'special_start_date' => null,
            'special_end_date' => null,
            'retial_price' => $price,
            'quantity' => 1,
        ]);
        $shoppingCartItemDetailCustoms = ShoppingCartItemDetailCustom::where('shopping_cart_item_id',$shoppingCartItem->id)->get();
        if($shoppingCartItemDetailCustoms->count() > 0){
            foreach ($shoppingCartItemDetailCustoms as $key => $shoppingCartItemDetailCustom) {
                ShoppingCartItemDetailCustomItem::where('shopping_cart_item_custom_id',$shoppingCartItemDetailCustom->id)->delete();
                $shoppingCartItemDetailCustom->delete();
            }
        }
        $items = $request->items;
        foreach ($items as $key => $item) {
            if($item['choose_item'] > 0){
                $item['shopping_cart_item_id'] = $shoppingCartItem->id;
                $shoppingCartItemDetailCustom = ShoppingCartItemDetailCustom::create($item);
                $itemChooses = $item['group_items'];
                foreach ($itemChooses as $key => $itemChoose) {
                    if($itemChoose['item_value'] > 0){
                        $itemChoose['shopping_cart_item_custom_id'] = $shoppingCartItemDetailCustom->id;
                        ShoppingCartItemDetailCustomItem::create($itemChoose);
                    }
                }
            }
        }


        return response()->json([
            'msg_return' => 'SUCCESS',
            'code_return' => 1,
        ]);
    }

    public function shoppingCartRemove($cartItemId)
    {
    	$shoppingCartItem = ShoppingCartItem::find($cartItemId);
    	$shoppingCartItemDetailDiys = ShoppingCartItemDetailDiy::where('shopping_cart_item_id',$shoppingCartItem->id)->get();
    	if($shoppingCartItemDetailDiys->count() > 0){
    		foreach ($shoppingCartItemDetailDiys as $key => $shoppingCartItemDetailDiy) {
    			ShoppingCartItemDetailDiyItem::where('shopping_cart_item_detail_diy_id',$shoppingCartItemDetailDiy->id)->delete();
    			$shoppingCartItemDetailDiy->delete();
    		}
    	}
    	$shoppingCartItemDetailPartySets = ShoppingCartItemDetailPartySet::where('shopping_cart_item_id',$shoppingCartItem->id)->get();
    	if($shoppingCartItemDetailPartySets->count() > 0){
    		foreach ($shoppingCartItemDetailPartySets as $key => $shoppingCartItemDetailPartySet) {
    			ShoppingCartItemDetailPartySetItem::where('shopping_cart_item_party_set_id',$shoppingCartItemDetailPartySet->id)->delete();
    			$shoppingCartItemDetailPartySet->delete();
    		}
    	}
        $shoppingCartItemDetailCustoms = ShoppingCartItemDetailCustom::where('shopping_cart_item_id',$shoppingCartItem->id)->get();
        if($shoppingCartItemDetailCustoms->count() > 0){
            foreach ($shoppingCartItemDetailCustoms as $key => $shoppingCartItemDetailCustom) {
                ShoppingCartItemDetailCustomItem::where('shopping_cart_item_custom_id',$shoppingCartItemDetailCustom->id)->delete();
                $shoppingCartItemDetailCustom->delete();
            }
        }
    	$shoppingCartItem->delete();

    	return response()->json([
            'msg_return' => 'SUCCESS',
            'code_return' => 1,
        ]);
    }
}
