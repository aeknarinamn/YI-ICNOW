<?php

namespace YellowProject\Http\Controllers\ICNOW\View;

use Illuminate\Http\Request;
use YellowProject\Http\Controllers\Controller;
use YellowProject\LineUserProfile;
use YellowProject\ICNOW\OrderCustomer\CustomerShippingAddress;
use YellowProject\ICNOW\ShoppingCart\ShoppingCartItem;
use YellowProject\ICNOW\ShoppingCart\ShoppingCart;
use YellowProject\ICNOW\ShoppingCart\ShoppingCartItemDetailDiy;
use YellowProject\ICNOW\ShoppingCart\ShoppingCartItemDetailDiyItem;
use YellowProject\ICNOW\ShoppingCart\ShoppingCartItemDetailPartySet;
use YellowProject\ICNOW\ShoppingCart\ShoppingCartItemDetailPartySetItem;
use YellowProject\ICNOW\Product\Product;
use Carbon\Carbon;

class ProfileController extends Controller
{
    public function profilePage()
    {
    	if(!array_key_exists('line-user-id', $_COOKIE)){
            abort(404);
        }
        $lineUserId = $_COOKIE['line-user-id'];
    	$lineUserProfile = LineUserProfile::find($lineUserId);
        $customerShippingAddressAlls = CustomerShippingAddress::where('id',$lineUserProfile->address_id)->get();
    	$customerShippingAddress = CustomerShippingAddress::find($lineUserProfile->address_id);

        $datas = \DB::table('fact_icnow_customer_order as co')
            ->select(
                'sci.*',
                'pi.img_url'
            )
            ->leftjoin('fact_icnow_shopping_cart_item as sci','sci.shopping_cart_id','=','co.shopping_cart_id')
            ->leftjoin('dim_icnow_product_images as pi','pi.icnow_product_id','=','sci.product_id')
            ->where('co.line_user_id',$lineUserId)
            ->orderByDesc('sci.created_at')
            ->groupBy('sci.id')
            ->get();

    	return view('icnow.profile.index')
            ->with('datas',$datas)
    		->with('lineUserProfile',$lineUserProfile)
            ->with('customerShippingAddressAlls',$customerShippingAddressAlls)
    		->with('customerShippingAddress',$customerShippingAddress);
    }

    public function recentOrder($itemId)
    {
        $dateNow = Carbon::now()->format('Y-m-d H:i:s');
        $shoppingCartItemOld = ShoppingCartItem::find($itemId);
        $lineUserId = $shoppingCartItemOld->line_user_id;
        $productId = $shoppingCartItemOld->product_id;
        $quantity = $shoppingCartItemOld->quantity;
        $isPromotion = 0;
        $shoppingCart = ShoppingCart::where('line_user_id',$lineUserId)->where('is_active',1)->first();
        $product = Product::find($shoppingCartItemOld->product_id);
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

        if($product->section_id == 2){
            $shoppingCartItemDetailPartySets = ShoppingCartItemDetailPartySet::where('shopping_cart_item_id',$shoppingCartItem->id)->get();
            if($shoppingCartItemDetailPartySets->count() > 0){
                foreach ($shoppingCartItemDetailPartySets as $key => $shoppingCartItemDetailPartySet) {
                    ShoppingCartItemDetailPartySetItem::where('shopping_cart_item_party_set_id',$shoppingCartItemDetailPartySet->id)->delete();
                    $shoppingCartItemDetailPartySet->delete();
                }
            }
            $items = $shoppingCartItemOld->shoppingCartItemDetailPartySets;
            foreach ($items as $key => $item) {
                $itemChooses = $item->shoppingCartItemDetailPartySetItems;
                $item = $item->toArray();
                $item['shopping_cart_item_id'] = $shoppingCartItem->id;
                $shoppingCartItemDetailPartySet = ShoppingCartItemDetailPartySet::create($item);
                foreach ($itemChooses as $key => $itemChoose) {
                    $itemChoose = $itemChoose->toArray();
                    $itemChoose['shopping_cart_item_party_set_id'] = $shoppingCartItemDetailPartySet->id;
                    ShoppingCartItemDetailPartySetItem::create($itemChoose);
                }
            }
        }else{
            $shoppingCartItemDetailDiys = ShoppingCartItemDetailDiy::where('shopping_cart_item_id',$shoppingCartItem->id)->get();
            if($shoppingCartItemDetailDiys->count() > 0){
                foreach ($shoppingCartItemDetailDiys as $key => $shoppingCartItemDetailDiy) {
                    ShoppingCartItemDetailDiyItem::where('shopping_cart_item_detail_diy_id',$shoppingCartItemDetailDiy->id)->delete();
                    $shoppingCartItemDetailDiy->delete();
                }
            }
            $shoppingCartItemDetailDiyData = $shoppingCartItemOld->shoppingCartItemDetailDiy;
            $shoppingCartItemDetailDiy = ShoppingCartItemDetailDiy::create([
                'shopping_cart_item_id' => $shoppingCartItem->id,
                'person_in_party' =>  $shoppingCartItemDetailDiyData->person_in_party,
                'product_focus' =>  null,
                'comment' =>  $shoppingCartItemDetailDiyData->comment,
            ]);
            $shoppingCartItemDetailDiyItems = $shoppingCartItemDetailDiyData->shoppingCartItemDetailDiyItems;
            if($shoppingCartItemDetailDiyItems){
                foreach ($shoppingCartItemDetailDiyItems as $key => $productfocus) {
                    ShoppingCartItemDetailDiyItem::create([
                        'shopping_cart_item_detail_diy_id' => $shoppingCartItemDetailDiy->id,
                        'value' => $productfocus->value
                    ]);
                }
            }
        }

        return response()->json([
            'msg_return' => 'SUCCESS',
            'code_return' => 1,
        ]);
    }
}
