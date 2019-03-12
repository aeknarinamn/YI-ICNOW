<?php

namespace YellowProject\GeneralFunction;

use Illuminate\Database\Eloquent\Model;

class CoreFunction extends Model
{
    public static function checkURL($text)
    {
    	$regex = "((https?|ftp)\:\/\/)?"; // SCHEME 
	    $regex .= "([a-z0-9+!*(),;?&=\$_.-]+(\:[a-z0-9+!*(),;?&=\$_.-]+)?@)?"; // User and Pass 
	    $regex .= "([a-z0-9-.]*)\.([a-z]{2,3})"; // Host or IP 
	    $regex .= "(\:[0-9]{2,5})?"; // Port 
	    $regex .= "(\/([a-z0-9+\$_-]\.?)+)*\/?"; // Path 
	    $regex .= "(\?[a-z+&\$_.-][a-z0-9;:@&%=+\/\$_.-]*)?"; // GET Query 
	    $regex .= "(#[a-z_.-][a-z0-9+\$_.-]*)?"; // Anchor 

       if(preg_match("/^$regex$/i", $text)) // `i` flag for case-insensitive
       { 
            return true; 
       }else{
       		return false;
       }
    }

    public static function genCode($length = 8)
    {
      $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
      $charactersLength = strlen($characters);
      $randomString = '';
      for ($i = 0; $i < $length; $i++) {
          $randomString .= $characters[rand(0, $charactersLength - 1)];
      }
      return $randomString;
    }
}
