<?php

namespace YellowProject\Http\Controllers\API\v1\MessageFile;

use Illuminate\Http\Request;
use YellowProject\Http\Controllers\Controller;
use YellowProject\MessageFile\MessageFile;
use Carbon\Carbon;
use URL;

class MessageFileController extends Controller
{
    public function uploadMultiple(Request $request)
    {
    	MessageFile::checkFolderMessageFile();
        $datas = collect();
        if($request->img_items){
            foreach ($request->img_items as $key => $img_item) {
                $dateNow = Carbon::now()->format('dmY_His');
                $fileImage = $img_item;
                $type = null;
                $destinationPath = 'message-file'; // upload path
                $extension = $fileImage->getClientOriginalExtension(); // getting image extension
                $fileName = $dateNow.'-'.$key.'.'.$extension; // renameing image
                $fileImage->move($destinationPath, $fileName); // uploading file to given path

                $imageFile = MessageFile::create([
                    'img_url' => URL::to('/')."/".$destinationPath."/".$fileName,
                    'img_size' => null,
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
