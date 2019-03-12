<?php

namespace YellowProject\Ecommerce\CoreEmailFunction;

use Illuminate\Database\Eloquent\Model;
use YellowProject\Ecommerce\CoreEmailFunction\EmailFunction;
use YellowProject\Ecommerce\DTManagement\DTManagementRegisterData;
use YellowProject\Ecommerce\DTManagement\DTManagement;
use YellowProject\Ecommerce\AdminLineUser;

class OrderConfirmEmailNotification extends Model
{
    public static function emailToCustomer($customer,$order)
    {
    	$text = "Order Confirmation To Customer";
	    $email = $customer->email;
	    EmailFunction::sendEmail($text,$email);
    }

    public static function emailToDT($customer,$order)
    {
    	$text = "Order Confirmation To DT";
    	$customer = $order->customer;
        $dtID = $customer->dt_id;
        $DTManagement = DTManagement::find($dtID);
        $DTManagementRegisterDatas = DTManagementRegisterData::where('dt_code',$DTManagement->dt_code_login)->get();
        foreach ($DTManagementRegisterDatas as $key => $DTManagementRegisterData) {
            $user = $DTManagementRegisterData->user;
            $email = $user->email;
	    	self::sendEmail($text,$email);
        }
    }

    public static function emailToAdmin($customer,$order)
    {
    	$text = "Order Confirmation To Admin";
    	$adminLineUsers = AdminLineUser::where('is_user',1)->get();
        foreach ($adminLineUsers as $key => $adminLineUser) {
            $email = $adminLineUser->email;
	    	self::sendEmail($text,$email);
        }
    }

    public static function addWhiteSpace()
    {
    	$space = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
    	return $space;
    }
}
