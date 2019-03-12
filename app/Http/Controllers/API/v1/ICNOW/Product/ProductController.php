<?php

namespace YellowProject\Http\Controllers\API\v1\ICNOW\Product;

use Illuminate\Http\Request;
use YellowProject\Http\Controllers\Controller;
use YellowProject\ICNOW\Product\Product;
use YellowProject\ICNOW\Product\ProductDiyPerson;
use YellowProject\ICNOW\Product\ProductDiyProductFocus;
use YellowProject\ICNOW\Product\ProductDiyOtherOption;
use YellowProject\ICNOW\Product\ProductImages;
use YellowProject\ICNOW\Product\ProductPartySet;
use YellowProject\ICNOW\Product\ProductPartySetItem;
use YellowProject\ICNOW\Product\ProductCustom;
use YellowProject\ICNOW\Product\ProductCustomItem;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filters = $request->filter_items;
        $productName = $filters['product_name'];
        $sectionName = $filters['section_name'];
        $sectionId = "";
        if($sectionName != ""){
            if($sectionName == 'DIY'){
                $sectionId = 2;
            }else{
                $sectionId = 1;
            }
        }
        $sku = $filters['sku'];
        $specialStartDate = $filters['special_start_date'];
        $specialEndDate = $filters['special_end_date'];
        $status = $filters['status'];
        $datas = \DB::table('dim_icnow_product as p')
            ->select(
                'p.id',
                'p.product_name',
                's.section_name',
                'p.sku',
                'p.price as retail_price',
                'p.special_price',
                'p.special_start_date as special_price_start_date',
                'p.special_end_date as special_price_end_date',
                'p.is_active',
                'p.sort_order'
            )
            ->leftjoin('dim_icnow_section as s','p.section_id','=','s.id');
        if($productName != ""){
            $datas = $datas->where('p.product_name','like','%'.$productName.'%');
        }
        if($sectionName != ""){
            $datas = $datas->where('p.section_id','like','%'.$sectionId.'%');
        }
        if($sku != ""){
            $datas = $datas->where('p.sku','like','%'.$sku.'%');
        }
        if($specialStartDate != "" && $specialEndDate != ""){
            $datas = $datas->whereDate('p.special_start_date','<=',$specialStartDate)->whereDate('p.special_end_date','>=',$specialEndDate);
        }
        if($status != ""){
            $datas = $datas->where('p.is_active',$status);
        }

        $datas = $datas->orderBy('p.sort_order')->orderByDesc('p.updated_at')->get();

        return response()->json([
            'datas' => $datas,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $datas = $request->datas;
        $product = Product::create($datas);
        if(array_key_exists('order_informations', $datas)){
            $orderInformations = $datas['order_informations'];
            if(array_key_exists('group_items', $orderInformations)){
                $groupItems = $orderInformations['group_items'];
                foreach ($groupItems as $key => $groupItem) {
                    $groupItem['icnow_product_id'] = $product->id;
                    $productPartySet = ProductPartySet::create($groupItem);
                    if(array_key_exists('items', $groupItem)){
                        $items = $groupItem['items'];
                        foreach ($items as $key => $item) {
                            $item['icnow_product_party_set_id'] = $productPartySet->id;
                            ProductPartySetItem::create($item);
                        }
                    }
                }
            }
            if(array_key_exists('person_in_party', $orderInformations)){
                $personInparties = $orderInformations['person_in_party'];
                foreach ($personInparties as $key => $personInparty) {
                    $personInparty['icnow_product_id'] = $product->id;
                    ProductDiyPerson::create($personInparty);
                }
            }
            if(array_key_exists('product_focuses', $orderInformations)){
                $productFocuses = $orderInformations['product_focuses'];
                foreach ($productFocuses as $key => $productFocus) {
                    $productFocus['icnow_product_id'] = $product->id;
                    if($productFocus['value'] == 'แท่ง'){
                        $productFocus['value'] = \URL::to('/').'/icnow/resources/images/p-3.png';
                    }else if($productFocus['value'] == 'โคน'){
                        $productFocus['value'] = \URL::to('/').'/icnow/resources/images/p-1.png';
                    }else if($productFocus['value'] == 'ถ้วย'){
                        $productFocus['value'] = \URL::to('/').'/icnow/resources/images/p-2.png';
                    }
                    ProductDiyProductFocus::create($productFocus);
                }
            }
            if(array_key_exists('other_option', $orderInformations)){
                $otherOptions = $orderInformations['other_option'];
                foreach ($otherOptions as $key => $otherOption) {
                    $otherOption['icnow_product_id'] = $product->id;
                    ProductDiyOtherOption::create($otherOption);
                }
            }
        }
        if(array_key_exists('image_items', $datas)){
            $imageItems = $datas['image_items'];
            foreach ($imageItems as $key => $imageItem) {
                $imageItem['icnow_product_id'] = $product->id;
                ProductImages::create($imageItem);
            }
        }

        return response()->json([
            'msg_return' => 'บันทึกสำเร็จ',
            'code_return' => 1,
            'id' => $product->id
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $datas = [];
        $product = Product::find($id);
        $datas['product_name'] = $product->product_name;
        $datas['section_id'] = $product->section_id;
        $datas['product_desc'] = $product->product_desc;
        $datas['sku'] = $product->sku;
        $datas['price'] = $product->price;
        $datas['special_price'] = $product->special_price;
        $datas['special_start_date'] = $product->special_start_date;
        $datas['special_end_date'] = $product->special_end_date;
        $datas['is_active'] = $product->is_active;
        $datas['sort_order'] = $product->sort_order;
        $datas['order_informations'] = [];
        if($product->section_id == 1){
            $datas['order_informations']['person_in_party'] = [];
            $datas['order_informations']['product_focuses'] = [];
            $datas['order_informations']['other_option'] = [];
            $productDiyPersons = $product->productDiyPersons;
            $productDiyProductFocuses = $product->productDiyProductFocuses;
            $productDiyOtherOptions = $product->productDiyOtherOptions;
            if($productDiyPersons){
                foreach ($productDiyPersons as $key => $productDiyPerson) {
                    $datas['order_informations']['person_in_party'][$key]['value'] = $productDiyPerson->value;
                }
            }
            if($productDiyProductFocuses){
                foreach ($productDiyProductFocuses as $key => $productDiyProductFocus) {
                    $datas['order_informations']['product_focuses'][$key]['value'] = $productDiyProductFocus->value;
                    $datas['order_informations']['product_focuses'][$key]['img_url'] = $productDiyProductFocus->img_url;
                }
            }
            if($productDiyOtherOptions){
                foreach ($productDiyOtherOptions as $key => $productDiyOtherOption) {
                    $datas['order_informations']['other_option'][$key]['value'] = $productDiyOtherOption->value;
                }
            }
        }else if($product->section_id == 2){
            $productPartySets = $product->productPartySets;
            $datas['order_informations']['group_items'] = [];
            if($productPartySets){
                foreach ($productPartySets as $keyMain => $productPartySet) {
                    $productPartySetItems = $productPartySet->productPartySetItems;
                    $datas['order_informations']['group_items'][$keyMain]['group_name'] = $productPartySet->group_name;
                    $datas['order_informations']['group_items'][$keyMain]['volumn'] = $productPartySet->volumn;
                    $datas['order_informations']['group_items'][$keyMain]['unit'] = $productPartySet->unit;
                    $datas['order_informations']['group_items'][$keyMain]['items'] = [];
                    if($productPartySetItems){
                        foreach ($productPartySetItems as $key => $productPartySetItem) {
                            $datas['order_informations']['group_items'][$keyMain]['items'][$key]['value'] = $productPartySetItem->value;
                            $datas['order_informations']['group_items'][$keyMain]['items'][$key]['img_url'] = $productPartySetItem->img_url;
                            $datas['order_informations']['group_items'][$keyMain]['items'][$key]['default_unit'] = $productPartySetItem->default_unit;
                        }
                    }
                }
            }
        }
        $datas['image_items'] = [];
        $productImages = $product->productImages;
        if($productImages){
            foreach ($productImages as $key => $productImage) {
                $datas['image_items'][$key]['img_url'] = $productImage->img_url;
            }
        }

        return response()->json([
            'datas' => $datas,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $datas = $request->datas;
        $product = Product::find($id);
        $product->update($datas);

        ProductDiyPerson::where('icnow_product_id',$product->id)->delete();
        ProductDiyProductFocus::where('icnow_product_id',$product->id)->delete();
        ProductDiyOtherOption::where('icnow_product_id',$product->id)->delete();
        ProductImages::where('icnow_product_id',$product->id)->delete();
        $productPartySets = ProductPartySet::where('icnow_product_id',$product->id)->get();
        if($productPartySets->count() > 0){
            foreach ($productPartySets as $key => $productPartySet) {
                ProductPartySetItem::where('icnow_product_party_set_id',$productPartySet->id)->delete();
                $productPartySet->delete();
            }
        }

        if(array_key_exists('order_informations', $datas)){
            $orderInformations = $datas['order_informations'];
            if(array_key_exists('group_items', $orderInformations)){
                $groupItems = $orderInformations['group_items'];
                foreach ($groupItems as $key => $groupItem) {
                    $groupItem['icnow_product_id'] = $product->id;
                    $productPartySet = ProductPartySet::create($groupItem);
                    if(array_key_exists('items', $groupItem)){
                        $items = $groupItem['items'];
                        foreach ($items as $key => $item) {
                            $item['icnow_product_party_set_id'] = $productPartySet->id;
                            ProductPartySetItem::create($item);
                        }
                    }
                }
            }
            if(array_key_exists('person_in_party', $orderInformations)){
                $personInparties = $orderInformations['person_in_party'];
                foreach ($personInparties as $key => $personInparty) {
                    $personInparty['icnow_product_id'] = $product->id;
                    ProductDiyPerson::create($personInparty);
                }
            }
            if(array_key_exists('product_focuses', $orderInformations)){
                $productFocuses = $orderInformations['product_focuses'];
                foreach ($productFocuses as $key => $productFocus) {
                    $productFocus['icnow_product_id'] = $product->id;
                    if($productFocus['value'] == 'แท่ง'){
                        $productFocus['value'] = \URL::to('').'/icnow/resources/images/p-3.png';
                    }else if($productFocus['value'] == 'โคน'){
                        $productFocus['value'] = \URL::to('/').'/icnow/resources/images/p-1.png';
                    }else if($productFocus['value'] == 'ถ้วย'){
                        $productFocus['value'] = \URL::to('/').'/icnow/resources/images/p-2.png';
                    }
                    ProductDiyProductFocus::create($productFocus);
                }
            }
            if(array_key_exists('other_option', $orderInformations)){
                $otherOptions = $orderInformations['other_option'];
                foreach ($otherOptions as $key => $otherOption) {
                    $otherOption['icnow_product_id'] = $product->id;
                    ProductDiyOtherOption::create($otherOption);
                }
            }
        }
        if(array_key_exists('image_items', $datas)){
            $imageItems = $datas['image_items'];
            foreach ($imageItems as $key => $imageItem) {
                $imageItem['icnow_product_id'] = $product->id;
                ProductImages::create($imageItem);
            }
        }


        return response()->json([
            'msg_return' => 'บันทึกสำเร็จ',
            'code_return' => 1,
            'id' => $product->id
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::find($id);
        ProductDiyPerson::where('icnow_product_id',$product->id)->delete();
        ProductDiyProductFocus::where('icnow_product_id',$product->id)->delete();
        ProductDiyOtherOption::where('icnow_product_id',$product->id)->delete();
        ProductImages::where('icnow_product_id',$product->id)->delete();
        $productPartySets = ProductPartySet::where('icnow_product_id',$product->id)->get();
        if($productPartySets->count() > 0){
            foreach ($productPartySets as $key => $productPartySet) {
                ProductPartySetItem::where('icnow_product_party_set_id',$productPartySet->id)->delete();
                $productPartySet->delete();
            }
        }
        $productCustoms = ProductCustom::where('icnow_product_id',$product->id)->get();
        if($productCustoms->count() > 0){
            foreach ($productCustoms as $key => $productCustom) {
                ProductCustomItem::where('icnow_product_custom_set_id',$productCustom->id)->delete();
                $productCustom->delete();
            }
        }
        $product->delete();

        return response()->json([
            'msg_return' => 'ลบข้อมูลสำเร็จ',
            'code_return' => 1,
        ]);
    }

    public function storeProductCustom(Request $request)
    {
        $datas = $request->datas;
        $product = Product::create($datas);
        if(array_key_exists('order_informations', $datas)){
            $orderInformations = $datas['order_informations'];
            if(array_key_exists('group_items', $orderInformations)){
                $groupItems = $orderInformations['group_items'];
                foreach ($groupItems as $key => $groupItem) {
                    $groupItem['icnow_product_id'] = $product->id;
                    $productPartySet = ProductPartySet::create($groupItem);
                    if(array_key_exists('items', $groupItem)){
                        $items = $groupItem['items'];
                        foreach ($items as $key => $item) {
                            $item['icnow_product_party_set_id'] = $productPartySet->id;
                            ProductPartySetItem::create($item);
                        }
                    }
                }
            }
            if(array_key_exists('person_in_party', $orderInformations)){
                $personInparties = $orderInformations['person_in_party'];
                foreach ($personInparties as $key => $personInparty) {
                    $personInparty['icnow_product_id'] = $product->id;
                    ProductDiyPerson::create($personInparty);
                }
            }
            if(array_key_exists('product_focuses', $orderInformations)){
                $productFocuses = $orderInformations['product_focuses'];
                foreach ($productFocuses as $key => $productFocus) {
                    $productFocus['icnow_product_id'] = $product->id;
                    if($productFocus['value'] == 'แท่ง'){
                        $productFocus['value'] = \URL::to('/').'/icnow/resources/images/p-3.png';
                    }else if($productFocus['value'] == 'โคน'){
                        $productFocus['value'] = \URL::to('/').'/icnow/resources/images/p-1.png';
                    }else if($productFocus['value'] == 'ถ้วย'){
                        $productFocus['value'] = \URL::to('/').'/icnow/resources/images/p-2.png';
                    }
                    ProductDiyProductFocus::create($productFocus);
                }
            }
            if(array_key_exists('other_option', $orderInformations)){
                $otherOptions = $orderInformations['other_option'];
                foreach ($otherOptions as $key => $otherOption) {
                    $otherOption['icnow_product_id'] = $product->id;
                    ProductDiyOtherOption::create($otherOption);
                }
            }
            if(array_key_exists('custom', $orderInformations)){
                $customs = $orderInformations['custom'];
                foreach ($customs as $key => $custom) {
                    $custom['icnow_product_id'] = $product->id;
                    $productCustom = ProductCustom::create($custom);
                    if(array_key_exists('items', $custom)){
                        $customItems = $custom['items'];
                        foreach ($customItems as $key => $customItem) {
                            $customItem['icnow_product_custom_set_id'] = $productCustom->id;
                            ProductCustomItem::create($customItem);
                        }
                    }
                }
            }

        }
        if(array_key_exists('image_items', $datas)){
            $imageItems = $datas['image_items'];
            foreach ($imageItems as $key => $imageItem) {
                $imageItem['icnow_product_id'] = $product->id;
                ProductImages::create($imageItem);
            }
        }

        return response()->json([
            'msg_return' => 'บันทึกสำเร็จ',
            'code_return' => 1,
            'id' => $product->id
        ]);
    }

    public function updateProductCustom(Request $request,$id)
    {
        $datas = $request->datas;
        $product = Product::find($id);
        $product->update($datas);

        ProductDiyPerson::where('icnow_product_id',$product->id)->delete();
        ProductDiyProductFocus::where('icnow_product_id',$product->id)->delete();
        ProductDiyOtherOption::where('icnow_product_id',$product->id)->delete();
        ProductImages::where('icnow_product_id',$product->id)->delete();
        $productPartySets = ProductPartySet::where('icnow_product_id',$product->id)->get();
        if($productPartySets->count() > 0){
            foreach ($productPartySets as $key => $productPartySet) {
                ProductPartySetItem::where('icnow_product_party_set_id',$productPartySet->id)->delete();
                $productPartySet->delete();
            }
        }
        $productCustoms = ProductCustom::where('icnow_product_id',$product->id)->get();
        if($productCustoms->count() > 0){
            foreach ($productCustoms as $key => $productCustom) {
                ProductCustomItem::where('icnow_product_custom_set_id',$productCustom->id)->delete();
                $productCustom->delete();
            }
        }

        if(array_key_exists('order_informations', $datas)){
            $orderInformations = $datas['order_informations'];
            if(array_key_exists('group_items', $orderInformations)){
                $groupItems = $orderInformations['group_items'];
                foreach ($groupItems as $key => $groupItem) {
                    $groupItem['icnow_product_id'] = $product->id;
                    $productPartySet = ProductPartySet::create($groupItem);
                    if(array_key_exists('items', $groupItem)){
                        $items = $groupItem['items'];
                        foreach ($items as $key => $item) {
                            $item['icnow_product_party_set_id'] = $productPartySet->id;
                            ProductPartySetItem::create($item);
                        }
                    }
                }
            }
            if(array_key_exists('person_in_party', $orderInformations)){
                $personInparties = $orderInformations['person_in_party'];
                foreach ($personInparties as $key => $personInparty) {
                    $personInparty['icnow_product_id'] = $product->id;
                    ProductDiyPerson::create($personInparty);
                }
            }
            if(array_key_exists('product_focuses', $orderInformations)){
                $productFocuses = $orderInformations['product_focuses'];
                foreach ($productFocuses as $key => $productFocus) {
                    $productFocus['icnow_product_id'] = $product->id;
                    if($productFocus['value'] == 'แท่ง'){
                        $productFocus['value'] = \URL::to('').'/icnow/resources/images/p-3.png';
                    }else if($productFocus['value'] == 'โคน'){
                        $productFocus['value'] = \URL::to('/').'/icnow/resources/images/p-1.png';
                    }else if($productFocus['value'] == 'ถ้วย'){
                        $productFocus['value'] = \URL::to('/').'/icnow/resources/images/p-2.png';
                    }
                    ProductDiyProductFocus::create($productFocus);
                }
            }
            if(array_key_exists('other_option', $orderInformations)){
                $otherOptions = $orderInformations['other_option'];
                foreach ($otherOptions as $key => $otherOption) {
                    $otherOption['icnow_product_id'] = $product->id;
                    ProductDiyOtherOption::create($otherOption);
                }
            }
            if(array_key_exists('custom', $orderInformations)){
                $customs = $orderInformations['custom'];
                foreach ($customs as $key => $custom) {
                    $custom['icnow_product_id'] = $product->id;
                    $productCustom = ProductCustom::create($custom);
                    if(array_key_exists('items', $custom)){
                        $customItems = $custom['items'];
                        foreach ($customItems as $key => $customItem) {
                            $customItem['icnow_product_custom_set_id'] = $productCustom->id;
                            ProductCustomItem::create($customItem);
                        }
                    }
                }
            }
        }
        if(array_key_exists('image_items', $datas)){
            $imageItems = $datas['image_items'];
            foreach ($imageItems as $key => $imageItem) {
                $imageItem['icnow_product_id'] = $product->id;
                ProductImages::create($imageItem);
            }
        }


        return response()->json([
            'msg_return' => 'บันทึกสำเร็จ',
            'code_return' => 1,
            'id' => $product->id
        ]);
    }

    public function showProductCustom(Request $request,$id)
    {
        $datas = [];
        $product = Product::find($id);
        $datas['product_name'] = $product->product_name;
        $datas['section_id'] = $product->section_id;
        $datas['product_desc'] = $product->product_desc;
        $datas['sku'] = $product->sku;
        $datas['price'] = $product->price;
        $datas['special_price'] = $product->special_price;
        $datas['special_start_date'] = $product->special_start_date;
        $datas['special_end_date'] = $product->special_end_date;
        $datas['is_active'] = $product->is_active;
        $datas['sort_order'] = $product->sort_order;
        $datas['order_informations'] = [];
        $datas['order_informations']['person_in_party'] = [];
        $datas['order_informations']['product_focuses'] = [];
        $datas['order_informations']['other_option'] = [];
        $datas['order_informations']['custom'] = [];
        $productDiyPersons = $product->productDiyPersons;
        $productDiyProductFocuses = $product->productDiyProductFocuses;
        $productDiyOtherOptions = $product->productDiyOtherOptions;
        $productCustoms = $product->productCustoms;
        if($productCustoms){
            foreach ($productCustoms as $keyMain => $productCustom) {
                $productCustomItems = $productCustom->productCustomItems;
                $datas['order_informations']['custom'][$keyMain]['group_name'] = $productCustom->group_name;
                $datas['order_informations']['custom'][$keyMain]['volumn'] = $productCustom->volumn;
                $datas['order_informations']['custom'][$keyMain]['unit'] = $productCustom->unit;
                $datas['order_informations']['custom'][$keyMain]['items'] = [];
                if($productCustomItems){
                    foreach ($productCustomItems as $key => $productCustomItem) {
                        $datas['order_informations']['custom'][$keyMain]['items'][$key]['value'] = $productCustomItem->value;
                        $datas['order_informations']['custom'][$keyMain]['items'][$key]['img_url'] = $productCustomItem->img_url;
                        $datas['order_informations']['custom'][$keyMain]['items'][$key]['default_unit'] = $productCustomItem->default_unit;
                        $datas['order_informations']['custom'][$keyMain]['items'][$key]['price'] = $productCustomItem->price;
                    }
                }
            }
        }
        if($productDiyPersons){
            foreach ($productDiyPersons as $key => $productDiyPerson) {
                $datas['order_informations']['person_in_party'][$key]['value'] = $productDiyPerson->value;
            }
        }
        if($productDiyProductFocuses){
            foreach ($productDiyProductFocuses as $key => $productDiyProductFocus) {
                $datas['order_informations']['product_focuses'][$key]['value'] = $productDiyProductFocus->value;
                $datas['order_informations']['product_focuses'][$key]['img_url'] = $productDiyProductFocus->img_url;
            }
        }
        if($productDiyOtherOptions){
            foreach ($productDiyOtherOptions as $key => $productDiyOtherOption) {
                $datas['order_informations']['other_option'][$key]['value'] = $productDiyOtherOption->value;
            }
        }
        $productPartySets = $product->productPartySets;
        $datas['order_informations']['group_items'] = [];
        if($productPartySets){
            foreach ($productPartySets as $keyMain => $productPartySet) {
                $productPartySetItems = $productPartySet->productPartySetItems;
                $datas['order_informations']['group_items'][$keyMain]['group_name'] = $productPartySet->group_name;
                $datas['order_informations']['group_items'][$keyMain]['volumn'] = $productPartySet->volumn;
                $datas['order_informations']['group_items'][$keyMain]['unit'] = $productPartySet->unit;
                $datas['order_informations']['group_items'][$keyMain]['items'] = [];
                if($productPartySetItems){
                    foreach ($productPartySetItems as $key => $productPartySetItem) {
                        $datas['order_informations']['group_items'][$keyMain]['items'][$key]['value'] = $productPartySetItem->value;
                        $datas['order_informations']['group_items'][$keyMain]['items'][$key]['img_url'] = $productPartySetItem->img_url;
                        $datas['order_informations']['group_items'][$keyMain]['items'][$key]['default_unit'] = $productPartySetItem->default_unit;
                    }
                }
            }
        }
        $datas['image_items'] = [];
        $productImages = $product->productImages;
        if($productImages){
            foreach ($productImages as $key => $productImage) {
                $datas['image_items'][$key]['img_url'] = $productImage->img_url;
            }
        }

        return response()->json([
            'datas' => $datas,
        ]);
    }
}
