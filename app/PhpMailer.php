<?php

namespace YellowProject;

use Illuminate\Database\Eloquent\Model;
use YellowProject\SettingPhpMailer;

class PhpMailer extends Model
{
    public static function sendMail($token,$user)
    {
    	$phpMailer = SettingPhpMailer::where('is_active',1)->first();
		require 'PHPMailerAutoload.php';
    	$mail = new \PHPMailer;
		$mail->isSMTP();
		$mail->CharSet = 'UTF-8';
		$mail->Host = $phpMailer->mail_host;
		$mail->SMTPAuth = true;
		$mail->Username = $phpMailer->mail_username;
		$mail->Password = $phpMailer->mail_password;
		$mail->SMTPSecure = $phpMailer->mail_encryption;
		$mail->Port = $phpMailer->mail_port;

		$mail->setFrom('admin@ufsshoponline.com', 'ufsshoponline');
		$mail->addAddress($user->email);

		$mail->isHTML(true);

		$mail->Subject = 'Reset your password';
		$mail->Body    = view('emails.password',['token' => $token, 'user' => $user]);

		if(!$mail->send()) {
			\Log::debug('Mailer Error: ' .$mail->ErrorInfo);
		} else {
		}
    }

    public static function ecomSendEmail($text,$email,$subject)
    {
    	$phpMailer = SettingPhpMailer::where('is_active',1)->first();
	    require 'PHPMailerAutoload.php';
	    $mail = new \PHPMailer;
	    $mail->isSMTP();
	    $mail->CharSet = 'UTF-8';
	    $mail->Host = $phpMailer->mail_host;
	    $mail->SMTPAuth = true;
	    $mail->Username = $phpMailer->mail_username;
	    $mail->Password = $phpMailer->mail_password;
	    $mail->SMTPSecure = $phpMailer->mail_encryption;
	    $mail->Port = $phpMailer->mail_port;

	    $mail->setFrom('admin@ufsshoponline.com', 'ufsshoponline');
	    $mail->addAddress($email);

	    $mail->isHTML(true);

	    $mail->Subject = $subject;
	    $mail->Body    = $text;

	    if(!$mail->send()) {
	    	\Log::debug('Mailer Error: ' . $mail->ErrorInfo);
	    } else {
	      	
	    }

	    return 1 ;
	    
    }
}
