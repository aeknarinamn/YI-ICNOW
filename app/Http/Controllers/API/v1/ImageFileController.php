<?php

namespace YellowProject\Http\Controllers\API\v1;

use Illuminate\Http\Request;
use YellowProject\Http\Controllers\Controller;
use YellowProject\ImageFile;
use Carbon\Carbon;
use URL;

class ImageFileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $imageFiles = ImageFile::all();

        return response()->json([
            'datas' => $imageFiles,
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
        // dd($request->all());
        $dateNow = Carbon::now()->format('dmY_His');
        $type = null;
        $fileImage = $request->file_img;
        if(isset($request->type)){
            $type = $request->type;
            if($type == 'ecom_product'){
                ImageFile::checkFolderEcomProduct();
                $destinationPath = 'ecom_line/ecom_shopping_line';
            }else if($type == 'ecom_payment'){
                ImageFile::checkFolderEcomPayment();
                $destinationPath = 'ecom_line/ecom_payment_line';
            }else if($type == 'coupon'){
                ImageFile::checkFolderCoupon();
                $destinationPath = 'coupon_line/coupon_special_deal/original_image';
            }else{
                ImageFile::checkFolderDefaultPath();
                $destinationPath = 'file_uploads/img_upload';
            }
        }else{
            ImageFile::checkFolderDefaultPath();
            $destinationPath = 'file_uploads/img_upload'; // upload path
        }
        $extension = $fileImage->getClientOriginalExtension(); // getting image extension
        // $fileName = rand(111111,999999).'.'.$extension; // renameing image
        $fileName = $dateNow.'.'.$extension; // renameing image
        $fileImage->move($destinationPath, $fileName); // uploading file to given path

        ImageFile::create([
            'img_url' => URL::to('/')."/".$destinationPath."/".$fileName,
            'img_size' => $request->img_size,
            'type' => $type,
        ]);

        return response()->json([
            'msg_return' => 'บันทึกสำเร็จ',
            'code_return' => 1,
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $imageFile = ImageFile::find($id);
        $imageFile->delete();
        return response()->json([
            'msg_return' => 'ลบสำเร็จ',
            'code_return' => 1,
        ]);
    }

    public function uploadMultiple(Request $request)
    {
        // dd($request->all());
        ImageFile::checkFolderEcomProduct();
        $datas = collect();
        if($request->img_items){
            foreach ($request->img_items as $key => $img_item) {
                $dateNow = Carbon::now()->format('dmY_His');
                $fileImage = $img_item;
                $type = null;
                if(isset($request->type)){
                    $type = $request->type;
                    if($type == 'ecom_product'){
                        ImageFile::checkFolderEcomProduct();
                        $destinationPath = 'ecom_line/ecom_shopping_line';
                    }else if($type == 'ecom_payment'){
                        ImageFile::checkFolderEcomPayment();
                        $destinationPath = 'ecom_line/ecom_payment_line';
                    }else if($type == 'coupon'){
                        ImageFile::checkFolderCoupon();
                        $destinationPath = 'coupon_line/coupon_special_deal/original_image';
                    }else{
                        ImageFile::checkFolderDefaultPath();
                        $destinationPath = 'file_uploads/img_upload';
                    }
                }else{
                    ImageFile::checkFolderDefaultPath();
                    $destinationPath = 'file_uploads/img_upload'; // upload path
                }
                // $destinationPath = 'file_uploads/img_upload'; // upload path
                $extension = $fileImage->getClientOriginalExtension(); // getting image extension
                $fileName = $dateNow.'-'.$key.'.'.$extension; // renameing image
                $fileImage->move($destinationPath, $fileName); // uploading file to given path

                $imageFile = ImageFile::create([
                    'img_url' => URL::to('/')."/".$destinationPath."/".$fileName,
                    // 'img_size' => $img_item->img_size,
                    'img_size' => null,
                    'type' => $type,
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
