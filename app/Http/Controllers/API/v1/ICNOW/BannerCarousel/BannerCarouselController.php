<?php

namespace YellowProject\Http\Controllers\API\v1\ICNOW\BannerCarousel;

use Illuminate\Http\Request;
use YellowProject\Http\Controllers\Controller;
use YellowProject\ICNOW\BannerCarousel\BannerCarousel;
use YellowProject\ICNOW\BannerCarousel\BannerCarouseImages;

class BannerCarouselController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filters = $request->filter_items;
        $carouselName = $filters['carousel_name'];
        $status = $filters['status'];
        $bannerCarousels = BannerCarousel::orderByDesc('created_at');
        if($carouselName != ""){
            $bannerCarousels = $bannerCarousels->where('carousel_name',$carouselName);
        }
        if($status != ""){
            $bannerCarousels = $bannerCarousels->where('is_active',$status);
        }
        $bannerCarousels = $bannerCarousels->get();

        return response()->json([
            'datas' => $bannerCarousels,
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
        $datas = $request->datas;
        $bannerCarousel = BannerCarousel::create($datas);
        if(array_key_exists('image_items', $datas)){
            foreach ($datas['image_items'] as $key => $imageItem) {
                BannerCarouseImages::create([
                    'icnow_banner_carousel_id' => $bannerCarousel->id,
                    'image_url' => $imageItem['img_url']
                ]);
            }
        }

        return response()->json([
            'msg_return' => 'บันทึกสำเร็จ',
            'code_return' => 1,
            'id' => $bannerCarousel->id
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
        $datas = [];
        $bannerCarousel = BannerCarousel::find($id);
        $bannerCarouseImages = $bannerCarousel->bannerCarouseImages;
        $datas['id'] = $bannerCarousel->id;
        $datas['delay_time'] = $bannerCarousel->delay_time;
        $datas['is_active'] = $bannerCarousel->is_active;
        $datas['image_items'] = [];
        if($bannerCarouseImages->count() > 0){
            foreach ($bannerCarouseImages as $key => $bannerCarouseImage) {
                $datas['image_items'][$key]['img_url'] = $bannerCarouseImage->image_url;
            }
        }

        return response()->json([
            'datas' => $datas,
        ]);
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
        $datas = $request->datas;
        $bannerCarousel = BannerCarousel::find($id);
        $bannerCarousel->update($datas);
        BannerCarouseImages::where('icnow_banner_carousel_id',$bannerCarousel->id)->delete();
        if(array_key_exists('image_items', $datas)){
            foreach ($datas['image_items'] as $key => $imageItem) {
                BannerCarouseImages::create([
                    'icnow_banner_carousel_id' => $bannerCarousel->id,
                    'image_url' => $imageItem['img_url']
                ]);
            }
        }

        return response()->json([
            'msg_return' => 'บันทึกสำเร็จ',
            'code_return' => 1,
            'id' => $bannerCarousel->id
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $bannerCarousel = BannerCarousel::find($id);
        BannerCarouseImages::where('icnow_banner_carousel_id',$bannerCarousel->id)->delete();
        $bannerCarousel->delete();

        return response()->json([
            'msg_return' => 'ลบข้อมูลสำเร็จ',
            'code_return' => 1,
        ]);
    }
}
