<?php

namespace YellowProject\Http\Controllers\API\v1;

use Illuminate\Http\Request;
use YellowProject\Http\Controllers\Controller;

class EnvAngularController extends Controller
{
    public function getData()
    {
        $arr = Array();
        $arr["bc_tracking"] = Array(
            "coupon_api" => "/api/tracking-bc-coupon"
        );
        $arr["coupon_form_save_btn_color1"] = "#f8bfbd";
        $arr["coupon_form_save_btn_color2"] = "#f8bfbd";
        $arr["coupon_form_back_btn_color1"] = "#a5151a";
        $arr["coupon_form_back_btn_color2"] = "#a5151a";
        $arr["segment"] = Array(
            "coupon_api" => "/api/coupon" , 
            "ecommerce"=> Array(
                "is_use" => true,
                "api_url_product" => "/api/ecommerce-product",
                "api_url_product_category" => "/api/ecommerce-category",
            )
        );
        $arr["profilling_dogjun_color"] = "#e27826";
        $arr["profilling"] = Array(
            "hello_soda_show" => true,
            "wallpaper_page" => "#ffffff",
            "page_color" => "#ffffff",
            "btn_submit_color" => "#00b900",
            "font_color" => "#000000",
            "font_color_link" => "blue",
            "font_color_submit" => "#ffffff"
        );
        $arr["carousel_action_mapping"] = Array(
            Array("id"=>"","title"=>"No Action"),
            Array("id"=>"Web Link URL","title"=>"Web Link URL"),
            Array("id"=>"Keyword","title"=>"Keyword"),
            Array("id"=>"Tel","title"=>"Tel"),
            Array("id"=>"Google Map","title"=>"Google Map"),
            Array("id"=>"Location","title"=>"Location")
        );
        $arr["carousel_field_csv_mapping"] = Array(
            "ignore",
            "name",
            "description",
            "image_url",
            "keyword",
            "link_url_1",
            "link_url_2",
            "link_url_3",
            "tel_1",
            "tel_2",
            "tel_3",
            "keyword_text_1",
            "keyword_text_2",
            "keyword_text_3",
            "latitude",
            "longtitude",
            "auto_reply_keyword",
            "auto_reply_message",
            "autoreply_title",
            "autoreply_folder_name"
        );
        $arr["carousel_options"]["show_conf"] = true;
        $arr["carousel_options"]["show_is_autoreply"] = true;
        $arr["carousel_csv_old_column"] = Array(
            Array("field"=>"id","title"=>"id","sortable"=>true,"align"=>"center","visible"=>false),
            Array("field"=>"_no","title"=>"#","sortable"=>true,"align"=>"center"),
            Array("field"=>"name","title"=>"name","sortable"=>true,"align"=>"left"),
            Array("field"=>"description","title"=>"description","sortable"=>true,"align"=>"left"),
            Array("field"=>"image_url","title"=>"image_url","sortable"=>true,"align"=>"left"),
            Array("field"=>"keyword","title"=>"keyword","sortable"=>true,"align"=>"left"),
            Array("field"=>"link_url_1","title"=>"link_url_1","sortable"=>true,"align"=>"left"),
            Array("field"=>"link_url_2","title"=>"link_url_2","sortable"=>true,"align"=>"left"),
            Array("field"=>"link_url_3","title"=>"link_url_3","sortable"=>true,"align"=>"left"),
            Array("field"=>"tel_1","title"=>"tel_1","sortable"=>true,"align"=>"left"),
            Array("field"=>"tel_2","title"=>"tel_2","sortable"=>true,"align"=>"left"),
            Array("field"=>"tel_3","title"=>"tel_3","sortable"=>true,"align"=>"left"),
            Array("field"=>"keyword_text_1","title"=>"keyword_text_1","sortable"=>true,"align"=>"left"),
            Array("field"=>"keyword_text_2","title"=>"keyword_text_2","sortable"=>true,"align"=>"left"),
            Array("field"=>"keyword_text_3","title"=>"keyword_text_3","sortable"=>true,"align"=>"left"),
            Array("field"=>"latitude","title"=>"latitude","sortable"=>true,"align"=>"left"),
            Array("field"=>"longtitude","title"=>"longtitude","sortable"=>true,"align"=>"left"),
            Array("field"=>"auto_reply_keyword","title"=>"auto_reply_keyword","sortable"=>true,"align"=>"left"),
            Array("field"=>"auto_reply_message","title"=>"auto_reply_message","sortable"=>true,"align"=>"left"),
            Array("field"=>"autoreply_title","title"=>"autoreply_title","sortable"=>true,"align"=>"left"),
            Array("field"=>"autoreply_folder_name","title"=>"autoreply_folder_name","sortable"=>true,"align"=>"left"),
            Array("field"=>"_btn_edit","title"=>"_btn_edit","sortable"=>true,"align"=>"center"),
            Array("field"=>"_btn_delete","title"=>"_btn_delete","sortable"=>true,"align"=>"center")
        );
        $arr["tableau_report"]["show"] = true;

    	return response()->json($arr);
    }
}
