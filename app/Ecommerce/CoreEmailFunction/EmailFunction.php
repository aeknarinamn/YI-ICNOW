<?php

namespace YellowProject\Ecommerce\CoreEmailFunction;

use Illuminate\Database\Eloquent\Model;
use YellowProject\SettingPhpMailer;
use YellowProject\Ecommerce\DTManagement\DTManagementRegisterData;
use YellowProject\Ecommerce\DTManagement\DTManagement;
use YellowProject\Ecommerce\AdminLineUser;

class EmailFunction extends Model
{
    public static function emailToCustomer($customer,$order)
    {
    	$orderProducts = $order->orderProducts;
    	$text = "เรียน คุณ ".$customer->first_name." ".$customer->last_name."<br><br>";
	    $text .= "ขณะนี้เราได้รับข้อมูลรายการสั่งซื้อจากท่านแล้ว ขอขอบคุณสำหรับการสั่งซื้อสินค้าและบริการจาก Unilever Thailand <br><br>";
	    $text .= "เลขที่ใบสั่งซื้อของท่านคือ ".$order->order_id." กรุณาอ้างอิงเลขที่นี้ในการติดต่อกับเราเพื่อสอบถามข้อมูลเพิ่มเติมเกี่ยวกับการสั่งซื้อครั้งนี้ค่ะ <br><br>";
	    $text .= "รายละเอียดการชำระเงิน <br>";
	    $text .= "ชื่อบัญชี  บริษัท เค.โฟร์. เทรดดิ้ง จำกัด <br>";
	    $text .= "บมจ.ธนาคารกสิกรไทย (KASIKORNBANK) สาขาเซ็นทรัลพลาซ่าชลบุรี เลขที่บัญชี 036-1-61765-7 <br>";
	    $text .= "บมจ.ธนาคารทหารไทย (TMB) สาขาบ้านสวน ชลบุรี เลขที่บัญชี 492-1-06205-7 <br><br>";
	    $text .= "รายละเอียดรายการสินค้ามีดังนี้ <br>";
	    foreach ($orderProducts as $key => $orderProduct) {
	    	$text .= $orderProduct->product->name." จำนวน ".$orderProduct->quanlity." ชิ้น เป็นเงิน ".$orderProduct->total." บาท <br>";
	    }
	    $text .= "<br>ราคาสินค้ารวม ภาษีมูลค่าเพิ่ม ".$order->grand_total."<br>";
	    $text .= "ส่วนลด ".$order->coupon_code_discount_amount."<br>";
	    $text .= "ราคาเงินสุทธิ ".$order->total_due."<br><br><br><br><br>";
	    $email = $customer->email;
	    self::sendEmail($text,$email);
    }

    public static function emailToDT($customer,$order)
    {
    	$orderProducts = $order->orderProducts;
    	$text = "คุณ ".$customer->first_name." ".$customer->last_name."<br><br>";
	    $text .= "ได้ทำการสั่งสินค้ามีข้อมูลดังต่อไปนี้ <br><br>";
	    $text .= "เลขที่ใบสั่งซื้อ ".$order->order_id." <br><br>";
	    $text .= "รายละเอียดรายการสินค้ามีดังนี้ <br>";
	    foreach ($orderProducts as $key => $orderProduct) {
	    	$text .= $orderProduct->product->name." จำนวน ".$orderProduct->quanlity." ชิ้น เป็นเงิน ".$orderProduct->total." บาท <br>";
	    }
	    $text .= "<br>ราคาสินค้ารวม ภาษีมูลค่าเพิ่ม ".$order->grand_total."<br>";
	    $text .= "ส่วนลด ".$order->coupon_code_discount_amount."<br>";
	    $text .= "ราคาเงินสุทธิ ".$order->total_due."<br><br><br><br><br>";
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
    	$orderProducts = $order->orderProducts;
    	$text = "คุณ ".$customer->first_name." ".$customer->last_name."<br><br>";
	    $text .= "ได้ทำการสั่งสินค้ามีข้อมูลดังต่อไปนี้ <br><br>";
	    $text .= "เลขที่ใบสั่งซื้อ ".$order->order_id." <br><br>";
	    $text .= "รายละเอียดรายการสินค้ามีดังนี้ <br>";
	    foreach ($orderProducts as $key => $orderProduct) {
	    	$text .= $orderProduct->product->name." จำนวน ".$orderProduct->quanlity." ชิ้น เป็นเงิน ".$orderProduct->total." บาท <br>";
	    }
	    $text .= "<br>ราคาสินค้ารวม ภาษีมูลค่าเพิ่ม ".$order->grand_total."<br>";
	    $text .= "ส่วนลด ".$order->coupon_code_discount_amount."<br>";
	    $text .= "ราคาเงินสุทธิ ".$order->total_due."<br><br><br><br><br>";
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

    public static function sendEmail($text,$email)
    {
    	$phpMailer = SettingPhpMailer::where('is_active',1)->first();
	    require 'PHPMailerAutoload.php';
	    $mail = new \PHPMailer;
	    //$mail->SMTPDebug = 3;                               // Enable verbose debug output
	    $mail->isSMTP();
	    $mail->CharSet = 'UTF-8';
	    $mail->Host = $phpMailer->mail_host;  // Specify main and backup SMTP servers
	    $mail->SMTPAuth = true;                               // Enable SMTP authentication
	    $mail->Username = $phpMailer->mail_username;                 // SMTP username
	    $mail->Password = $phpMailer->mail_password;                           // SMTP password
	    $mail->SMTPSecure = $phpMailer->mail_encryption;                            // Enable TLS encryption, `ssl` also accepted
	    $mail->Port = $phpMailer->mail_port;                                    // TCP port to connect to

	    $mail->setFrom('admin@unilever.com', 'UNILEVER SHOPPING LINE');
	    //$mail->setFrom('shoppingsupport@scgexperience.com', 'SCG SHOPPING LINE'); // SCG Email
        //$mail->setFrom('developer@yellow-idea.com', 'Yellow Idea');
	    $mail->addAddress($email);     // Add a recipient

	    $mail->isHTML(true);                                  // Set email format to HTML

	    $mail->Subject = 'UNILEVER SHOPPING LINE';
	    $mail->Body    = $text;

	    if(!$mail->send()) {
	    	\Log::debug('Mailer Error: ' . $mail->ErrorInfo);
	        // echo 'Message could not be sent.';
	        // echo 'Mailer Error: ' . $mail->ErrorInfo;
	        // dd('Mailer Error: ' . $mail->ErrorInfo);
	    } else {
	      	// dd('Message has been sent');
	        // echo 'Message has been sent';
	    }

	    return 1 ;
	    // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
    }
}
