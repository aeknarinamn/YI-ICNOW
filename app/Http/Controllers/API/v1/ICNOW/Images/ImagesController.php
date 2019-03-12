<?php

namespace YellowProject\Http\Controllers\API\v1\ICNOW\Images;

use Illuminate\Http\Request;
use YellowProject\Http\Controllers\Controller;
use YellowProject\ICNOW\Images\Images;
use Carbon\Carbon;
use URL;

class ImagesController extends Controller
{
    public function uploadMultiple(Request $request)
    {
        $destinationPath = 'file_uploads/icnow/images'; // upload path
        Images::checkFolder($destinationPath);
        $datas = collect();
        if($request->img_items){
            foreach ($request->img_items as $key => $img_item) {
                $dateNow = Carbon::now()->format('dmY_His');
                $fileImage = $img_item;
                $type = null;
                // ImageFile::checkFolderDefaultPath();
                $extension = $fileImage->getClientOriginalExtension(); // getting image extension
                $fileName = $dateNow.'-'.$key.'.'.$extension; // renameing image
                $fileImage->move($destinationPath, $fileName); // uploading file to given path

                $imageFile = Images::create([
                    'img_url' => URL::to('/')."/".$destinationPath."/".$fileName,
                    'type' => null,
                ]);

                $datas->put($key,$imageFile);
            }
        }

        return response()->json([
            'msg_return' => 'บันทึกสำเร็จ',
            'code_return' => 1,
            'datas' => $datas,
        ]);
    }
}
