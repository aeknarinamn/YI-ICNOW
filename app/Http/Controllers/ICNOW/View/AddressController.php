<?php

namespace YellowProject\Http\Controllers\ICNOW\View;

use Illuminate\Http\Request;
use YellowProject\Http\Controllers\Controller;
use YellowProject\ICNOW\CoreFunction\CoreFunction;
use YellowProject\ICNOW\OrderCustomer\CustomerShippingAddress;
use YellowProject\ICNOW\APIConnection\MiniConnection;
use YellowProject\LineUserProfile;
use YellowProject\ICNOW\CacheData\CacheData;
use Carbon\Carbon;

class AddressController extends Controller
{
    public function addressPage()
    {
        if(!array_key_exists('line-user-id', $_COOKIE)){
            abort(404);
        }
        $address = "";
        $firstname = "";
        $lastName = "";
        $phoneNumber = "";
        
        $lineUserId = $_COOKIE['line-user-id'];
        $lineUserProfile = LineUserProfile::find($lineUserId);
    	$dateNow = Carbon::now()->addDays(1)->format('d/m/Y');
    	$customerShippingAddresses = CustomerShippingAddress::where('line_user_id',$lineUserId)->where('is_active',1)->get();
        $customerShippingAddress = CustomerShippingAddress::find($lineUserProfile->address_id);

        if(array_key_exists('address-data', $_COOKIE) && $customerShippingAddress){
            $address = $_COOKIE['address-data'];
        }
        if(array_key_exists('addr-first-name', $_COOKIE)){
            $firstname = $_COOKIE['addr-first-name'];
        }else{
            if($customerShippingAddress){
                $firstname = $customerShippingAddress->first_name;
            }
        }
        if(array_key_exists('addr-last-name', $_COOKIE)){
            $lastName = $_COOKIE['addr-last-name'];
        }else{
            if($customerShippingAddress){
                $lastName = $customerShippingAddress->last_name;
            }
        }
        if(array_key_exists('addr-phone-number', $_COOKIE)){
            $phoneNumber = $_COOKIE['addr-phone-number'];
        }else{
            if($customerShippingAddress && $customerShippingAddress->phone_number != ''){
                $phoneNumber = $customerShippingAddress->phone_number;
            }else{
                $phoneNumber = $lineUserProfile->phone_number;
            }
        }

    	// if($customerShippingAddresses->count() == 0){
    	// 	return view('icnow.address.empty-address');
    	// }

        setcookie('addr-first-name', '', time() - (86400 * 1), "/");
        setcookie('addr-last-name', '', time() - (86400 * 1), "/");
        setcookie('addr-phone-number', '', time() - (86400 * 1), "/");

    	return view('icnow.address.address')
            ->with('dateNow',$dateNow)
            ->with('address',$address)
            ->with('firstname',$firstname)
            ->with('lastName',$lastName)
    		->with('phoneNumber',$phoneNumber)
            ->with('customerShippingAddresses',$customerShippingAddresses)
            ->with('lineUserProfile',$lineUserProfile)
    		->with('customerShippingAddress',$customerShippingAddress);
    }

    public function addressEmptyPage()
    {
        if(!array_key_exists('line-user-id', $_COOKIE)){
            abort(404);
        }
    	return view('icnow.address.empty-address');
    }

    public function addressAddPage()
    {
        if(!array_key_exists('line-user-id', $_COOKIE)){
            abort(404);
        }
        $isAddress = 0;
        $lineUserId = $_COOKIE['line-user-id'];
        $customerShippingAddress = CustomerShippingAddress::where('line_user_id',$lineUserId)->first();
        $cacheData = CacheData::where('line_user_id',$lineUserId)->first();
        if($customerShippingAddress){
            $isAddress = 1;
        }

    	return view('icnow.address.add-address')
            ->with('cacheData',$cacheData)
            ->with('isAddress',$isAddress);
    }

    public function addressEditPage($id)
    {
        $customerShippingAddress = CustomerShippingAddress::find($id);
        return view('icnow.address.edit-address')
            ->with('customerShippingAddress',$customerShippingAddress);
    }

    public function addressDataUpdate(Request $request)
    {
        $customerShippingAddress = CustomerShippingAddress::find($request->address_id);
        if($customerShippingAddress){
            $customerShippingAddress->update($request->all());
        }
        return redirect('/profile-page');
    }

    public function addressData()
    {
        if(!array_key_exists('line-user-id', $_COOKIE)){
            abort(404);
        }
    	return view('icnow.address.confirm-data');
    }

    public function addressDataStore(Request $request)
    {
        if(!array_key_exists('line-user-id', $_COOKIE)){
            abort(404);
        }
    	$customerShippingAddress = CustomerShippingAddress::create($request->all());

    	return redirect('/address');
    }

    public function addressAddStore(Request $request)
    {
        if(!array_key_exists('line-user-id', $_COOKIE)){
            abort(404);
        }
        $lineUserId = $_COOKIE['line-user-id'];
        setcookie('address-data', $request->address, time() + (86400 * 1), "/");
        // setcookie('address-lat', $request->lat, time() + (86400 * 1), "/");
        // setcookie('address-long', $request->long, time() + (86400 * 1), "/");

        
        // dd($request->all());
        $lineUserProfile = LineUserProfile::find($lineUserId);
    	$loadDatas = [];
    	$datas = [];
    	$mainDatas = [];
    	$mainDatas['street_number'] = "";
    	$mainDatas['route'] = "";
    	$mainDatas['province'] = "";
    	$mainDatas['district'] = "";
    	$mainDatas['sub_district'] = "";
    	$mainDatas['post_code'] = "";
        $mainDatas['lattitude'] = $request->lat;
        $mainDatas['longtitude'] = $request->long;
        $mainDatas['phone_number'] = $lineUserProfile->phone_number;
        $mainDatas['is_active'] = 1;
    	$lat = $request->lat;
    	$long = $request->long;
        // $responseMini = MiniConnection::connectMini($lat,$long);
        // if($responseMini != null){
        //     $mainDatas['is_active'] = 1;
        // }
    	$response = CoreFunction::getLocationFromGoogle($lat,$long);
    	$response = json_decode($response);
    	if($response->status == 'OK'){
    		$results = $response->results;
    		foreach ($results as $key => $result) {
    			if(count($datas) == 0){
    				$datas['items'] = $result->address_components;
    				$datas['items_count'] = count($result->address_components);
    			}else{
    				if($datas['items_count'] < count($result->address_components)){
    					$datas['items'] = $result->address_components;
    					$datas['items_count'] = count($result->address_components);
    				}
    			}
    		}
    		foreach ($datas['items'] as $key => $items) {
    			$type = implode(' ', $items->types);
    			$loadDatas[$type] = $items->long_name;
    		}
    		if(array_key_exists('street_number', $loadDatas)){
    			$mainDatas['street_number'] = $loadDatas['street_number'];
    		}
    		if(array_key_exists('route', $loadDatas)){
    			if($loadDatas['route'] != "Unnamed Road"){
    				$mainDatas['route'] = $loadDatas['route'];
    			}
    		}
    		if(array_key_exists('political sublocality sublocality_level_2', $loadDatas)){
    			$mainDatas['sub_district'] = $loadDatas['political sublocality sublocality_level_2'];
    		}
    		if(array_key_exists('locality political', $loadDatas)){
    			$mainDatas['sub_district'] = $loadDatas['locality political'];
    		}
    		if(array_key_exists('political sublocality sublocality_level_1', $loadDatas)){
    			$mainDatas['district'] = $loadDatas['political sublocality sublocality_level_1'];
    		}
    		if(array_key_exists('administrative_area_level_2 political', $loadDatas)){
    			$mainDatas['district'] = $loadDatas['administrative_area_level_2 political'];
    		}
    		if(array_key_exists('administrative_area_level_1 political', $loadDatas)){
    			$mainDatas['province'] = $loadDatas['administrative_area_level_1 political'];
    		}
    		if(array_key_exists('postal_code', $loadDatas)){
    			$mainDatas['post_code'] = $loadDatas['postal_code'];
    		}
    	}

        // if($mainDatas['is_active'] == 0){
            $CustomerShippingAddress = CustomerShippingAddress::create([
                'line_user_id' => $lineUserId,
                'first_name' => null,
                'last_name' => null,
                'address' => $mainDatas['street_number']." ".$mainDatas['route'],
                'sub_district' => $mainDatas['sub_district'],
                'district' => $mainDatas['district'],
                'province' => $mainDatas['province'],
                'post_code' => $mainDatas['post_code'],
                'phone_number' => $mainDatas['phone_number'],
                'lattitude' => $mainDatas['lattitude'],
                'longtitude' => $mainDatas['longtitude'],
                'is_active' => 0,
            ]);

            $lineUserProfile->update([
                'address_id' => $CustomerShippingAddress->id
            ]);



        //     return view('icnow.out-service.index');
        // }else{
            // return view('icnow.address.confirm-data')
            //     ->with('lineUserProfile',$lineUserProfile)
            //     ->with('mainDatas',$mainDatas);
        // }
        return redirect('/address');
    }

    public function addressRemove($id)
    {
        $customerShippingAddress = CustomerShippingAddress::find($id);
        if($customerShippingAddress){
            $customerShippingAddress->delete();
        }

        return response()->json([
            'msg_return' => 'SUCCESS',
            'code_return' => 1,
        ]);
    }

    public function storeAddressDataToCookie(Request $request)
    {
        setcookie('addr-first-name', $request->first_name, time() + (86400 * 1), "/");
        setcookie('addr-last-name', $request->last_name, time() + (86400 * 1), "/");
        setcookie('addr-phone-number', $request->phone_number, time() + (86400 * 1), "/");

        return response()->json([
            'msg_return' => 'SUCCESS',
            'code_return' => 1,
        ]);
    }

    public function checkAreaService(Request $request)
    {
        $isArea = 0;
        $lat = $request->lat;
        $long = $request->long;
        $response = MiniConnection::connectMini($lat,$long);
        if($response != null){
            $isArea = 1;
        }

        return response()->json([
            'is_area' => $isArea
        ]);
    }
}
