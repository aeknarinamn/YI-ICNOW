<?php

namespace YellowProject\Subscriber;

use Illuminate\Database\Eloquent\Model;
use YellowProject\SubscriberFolder;
use YellowProject\Subscriber;
use YellowProject\FieldFolder;
use YellowProject\Field;
use YellowProject\FieldItem;
use YellowProject\Subscriber\SubscriberCategory;

class MasterSubscriber extends Model
{
    public static function genMasterSubscriber()
    {
        $subscriberCategory = SubscriberCategory::create([
            'name' => 'Master Subscriber Category',
            'desc' => 'Master Subscriber Category'
        ]);

    	$subscriberFolder = SubscriberFolder::create([
    		"name" => "Master Subscriber",
    		"desc" => "Master Subscriber"
    	]);

    	$subscriber = Subscriber::create([
    		"folder_id" => $subscriberFolder->id,
            "category_id" => $subscriberCategory->id,
    		"name" => "Master Subscriber",
    		"desc" => "Master Subscriber",
            "is_master" => 1
    	]);

    	$fieldFolder = FieldFolder::create([
    		"name" => "Field Master Subscriber",
    		"desc" => "Field Master Subscriber"
    	]);

    	$field = Field::create([
    		"folder_id" => $fieldFolder->id,
    		"name" => "e_customer_code",
    		"type" => "string",
    		"description" => "Customer Code",
    		"is_personalize" => 0,
    		"primary_key" => 0,
    		"is_segment" => 1,
    		"subscriber_id" => $subscriber->id,
    		"field_name" => "Customer Code",
    		"personalize_default" => null,
    		"api_url" => null,
    		"is_required" => 0,
    		"is_readonly" => 0,
    		"is_api" => 0,
    		"is_encrypt" => 0,
            "is_master_subscriber" => 1
    	]);

        $field = Field::create([
            "folder_id" => $fieldFolder->id,
            "name" => "e_first_name",
            "type" => "string",
            "description" => "First Name",
            "is_personalize" => 0,
            "primary_key" => 0,
            "is_segment" => 1,
            "subscriber_id" => $subscriber->id,
            "field_name" => "First Name",
            "personalize_default" => null,
            "api_url" => null,
            "is_required" => 0,
            "is_readonly" => 0,
            "is_api" => 0,
            "is_encrypt" => 0,
            "is_master_subscriber" => 1
        ]);

        $field = Field::create([
            "folder_id" => $fieldFolder->id,
            "name" => "e_last_name",
            "type" => "string",
            "description" => "Last Name",
            "is_personalize" => 0,
            "primary_key" => 0,
            "is_segment" => 1,
            "subscriber_id" => $subscriber->id,
            "field_name" => "Last Name",
            "personalize_default" => null,
            "api_url" => null,
            "is_required" => 0,
            "is_readonly" => 0,
            "is_api" => 0,
            "is_encrypt" => 0,
            "is_master_subscriber" => 1
        ]);

        $field = Field::create([
            "folder_id" => $fieldFolder->id,
            "name" => "e_phone_number",
            "type" => "tel",
            "description" => "Phone Number",
            "is_personalize" => 0,
            "primary_key" => 0,
            "is_segment" => 1,
            "subscriber_id" => $subscriber->id,
            "field_name" => "Phone Number",
            "personalize_default" => null,
            "api_url" => null,
            "is_required" => 0,
            "is_readonly" => 0,
            "is_api" => 0,
            "is_encrypt" => 0,
            "is_master_subscriber" => 1
        ]);

        $field = Field::create([
            "folder_id" => $fieldFolder->id,
            "name" => "e_address",
            "type" => "string",
            "description" => "Address",
            "is_personalize" => 0,
            "primary_key" => 0,
            "is_segment" => 1,
            "subscriber_id" => $subscriber->id,
            "field_name" => "Address",
            "personalize_default" => null,
            "api_url" => null,
            "is_required" => 0,
            "is_readonly" => 0,
            "is_api" => 0,
            "is_encrypt" => 0,
            "is_master_subscriber" => 1
        ]);

        $field = Field::create([
            "folder_id" => $fieldFolder->id,
            "name" => "e_district",
            "type" => "string",
            "description" => "District",
            "is_personalize" => 0,
            "primary_key" => 0,
            "is_segment" => 1,
            "subscriber_id" => $subscriber->id,
            "field_name" => "District",
            "personalize_default" => null,
            "api_url" => null,
            "is_required" => 0,
            "is_readonly" => 0,
            "is_api" => 0,
            "is_encrypt" => 0,
            "is_master_subscriber" => 1
        ]);

        $field = Field::create([
            "folder_id" => $fieldFolder->id,
            "name" => "e_sub_district",
            "type" => "string",
            "description" => "Sub District",
            "is_personalize" => 0,
            "primary_key" => 0,
            "is_segment" => 1,
            "subscriber_id" => $subscriber->id,
            "field_name" => "Sub District",
            "personalize_default" => null,
            "api_url" => null,
            "is_required" => 0,
            "is_readonly" => 0,
            "is_api" => 0,
            "is_encrypt" => 0,
            "is_master_subscriber" => 1
        ]);

        $field = Field::create([
            "folder_id" => $fieldFolder->id,
            "name" => "e_province",
            "type" => "string",
            "description" => "Province",
            "is_personalize" => 0,
            "primary_key" => 0,
            "is_segment" => 1,
            "subscriber_id" => $subscriber->id,
            "field_name" => "Province",
            "personalize_default" => null,
            "api_url" => null,
            "is_required" => 0,
            "is_readonly" => 0,
            "is_api" => 0,
            "is_encrypt" => 0,
            "is_master_subscriber" => 1
        ]);

        $field = Field::create([
            "folder_id" => $fieldFolder->id,
            "name" => "e_post_code",
            "type" => "string",
            "description" => "Postcode",
            "is_personalize" => 0,
            "primary_key" => 0,
            "is_segment" => 1,
            "subscriber_id" => $subscriber->id,
            "field_name" => "Postcode",
            "personalize_default" => null,
            "api_url" => null,
            "is_required" => 0,
            "is_readonly" => 0,
            "is_api" => 0,
            "is_encrypt" => 0,
            "is_master_subscriber" => 1
        ]);

        $field = Field::create([
            "folder_id" => $fieldFolder->id,
            "name" => "e_tel",
            "type" => "tel",
            "description" => "Tel",
            "is_personalize" => 0,
            "primary_key" => 0,
            "is_segment" => 1,
            "subscriber_id" => $subscriber->id,
            "field_name" => "Tel",
            "personalize_default" => null,
            "api_url" => null,
            "is_required" => 0,
            "is_readonly" => 0,
            "is_api" => 0,
            "is_encrypt" => 0,
            "is_master_subscriber" => 1
        ]);

        $field = Field::create([
            "folder_id" => $fieldFolder->id,
            "name" => "e_store_name",
            "type" => "string",
            "description" => "Store Name",
            "is_personalize" => 0,
            "primary_key" => 0,
            "is_segment" => 1,
            "subscriber_id" => $subscriber->id,
            "field_name" => "Store Name",
            "personalize_default" => null,
            "api_url" => null,
            "is_required" => 0,
            "is_readonly" => 0,
            "is_api" => 0,
            "is_encrypt" => 0,
            "is_master_subscriber" => 1
        ]);

        $field = Field::create([
            "folder_id" => $fieldFolder->id,
            "name" => "e_store_type",
            "type" => "string",
            "description" => "Store Type",
            "is_personalize" => 0,
            "primary_key" => 0,
            "is_segment" => 1,
            "subscriber_id" => $subscriber->id,
            "field_name" => "Store Type",
            "personalize_default" => null,
            "api_url" => null,
            "is_required" => 0,
            "is_readonly" => 0,
            "is_api" => 0,
            "is_encrypt" => 0,
            "is_master_subscriber" => 1
        ]);
    }
}
