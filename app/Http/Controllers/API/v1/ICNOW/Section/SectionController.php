<?php

namespace YellowProject\Http\Controllers\API\v1\ICNOW\Section;

use Illuminate\Http\Request;
use YellowProject\Http\Controllers\Controller;
use YellowProject\ICNOW\Section\Section;
use YellowProject\ICNOW\Section\SectionImages;

class SectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sections = Section::orderByDesc('created_at')->get();

        return response()->json([
            'datas' => $sections,
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
        $section = Section::create($datas);
        if(array_key_exists('images_items', $datas)){
            foreach ($datas['images_items'] as $key => $imageItem) {
                SectionImages::create([
                    'icnow_section_id' => $section->id,
                    'img_url' => $imageItem['img_url']
                ]);
            }
        }

        return response()->json([
            'msg_return' => 'บันทึกสำเร็จ',
            'code_return' => 1,
            'id' => $section->id
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
        $section = Section::find($id);
        $sectionImages = $section->sectionImages;
        $datas['id'] = $section->id;
        $datas['section_name'] = $section->section_name;
        $datas['section_desc'] = $section->section_desc;
        $datas['images_items'] = [];
        if($sectionImages->count() > 0){
            foreach ($sectionImages as $key => $sectionImage) {
                $datas['images_items'][$key]['img_url'] = $sectionImage->img_url;
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
        $section = Section::find($id);
        $section->update($datas);
        SectionImages::where('icnow_section_id',$section->id)->delete();
        if(array_key_exists('images_items', $datas)){
            foreach ($datas['images_items'] as $key => $imageItem) {
                SectionImages::create([
                    'icnow_section_id' => $section->id,
                    'img_url' => $imageItem['img_url']
                ]);
            }
        }

        return response()->json([
            'msg_return' => 'บันทึกสำเร็จ',
            'code_return' => 1,
            'id' => $section->id
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
        $section = Section::find($id);
        SectionImages::where('icnow_section_id',$section->id)->delete();
        $section->delete();

        return response()->json([
            'msg_return' => 'ลบข้อมูลสำเร็จ',
            'code_return' => 1,
        ]);
    }
}
