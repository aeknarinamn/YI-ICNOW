<?php

namespace YellowProject\RichmessageV2;

use Illuminate\Database\Eloquent\Model;
use YellowProject\RichmessageV2\Richmessage;
use YellowProject\RichmessageV2\RichmessageArea;
use YellowProject\RichmessageV2\RichmessageAreaAction;
use YellowProject\RichmessageV2\RichmessageAreaBounds;

class CoreRichmessage extends Model
{
    public static function saveImageRichmesage($newPhoto)
    {
        $generateCode = self::generateRandomString();
        $photoBase64 = $newPhoto;
        $directory = 'images/richmessage';
        $path_save_image = 'images/richmessage/'.$generateCode;
        $haveDirCampainFile = false;
        if (\File::isDirectory($directory)){
            if (\File::isDirectory($path_save_image)){
                $haveDirCampainFile = true;
            } else  {
                $result = \File::makeDirectory($path_save_image, 0777, true);
                if($result){
                    $haveDirCampainFile = true;
                }
            }
        } else {
            //create  Directory
            $resultCreate = \File::makeDirectory(public_path($directory), 0777, true);
            if($resultCreate) {
                $result = \File::makeDirectory($path_save_image, 0777, true);
                 $haveDirCampainFile = true;
            }
        }
        if($haveDirCampainFile) {
            // \Image::make($photoBase64->getRealPath())->resize(240, null)->save($path_save_image.'/240');
            $img240  =\Image::make($photoBase64);
            $img300 = \Image::make($photoBase64);
            $img460 = \Image::make($photoBase64);
            $img700 = \Image::make($photoBase64);
            $img1040 = \Image::make($photoBase64);

            $img240->resize(240, null, function ($constraint){
                $constraint->aspectRatio();
            });
            $img240->save(public_path($path_save_image.'/240'));

            $img300->resize(300, null, function ($constraint){
                $constraint->aspectRatio();
            });
            $img300->save(public_path($path_save_image.'/300'));

            $img460->resize(460, null, function ($constraint){
                $constraint->aspectRatio();
            });
            $img460->save(public_path($path_save_image.'/460'));

            $img700->resize(700, null, function ($constraint){
                $constraint->aspectRatio();
            });
            $img700->save(public_path($path_save_image.'/700'));

            $img1040->resize(1040, null, function ($constraint){
                $constraint->aspectRatio();
            });
            $img1040->save(public_path($path_save_image.'/1040'));
        }

        return $generateCode;
    }

    public static function generateRandomString($length = 10) 
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
