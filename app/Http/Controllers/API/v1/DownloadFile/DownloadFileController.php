<?php

namespace YellowProject\Http\Controllers\API\v1\DownloadFile;

use Illuminate\Http\Request;
use YellowProject\Http\Controllers\Controller;
use YellowProject\DownloadFile\DownloadFile;

class DownloadFileController extends Controller
{
    public function getFileSubscriber()
    {
    	$downloadFileSusbcribers = DownloadFile::where('file_type','subscriber')->get();

    	return response()->json([
            'datas' => $downloadFileSusbcribers,
        ]);
    }
}
