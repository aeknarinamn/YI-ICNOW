<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use YellowProject\LineMessageType;

class LineMessageTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
     public function run()
    {
        Model::unguard();
        $this->addlineMessageType();
        Model::reguard();
    }

    private function addlineMessageType()
    {
    	$datas = array(
    		'text',
    		'image',
    		'video',
    		'audio',
    		'location',
    		'sticker',
    		'imagemap',
    		'template'
    	);
    	$lineMessageType = array();
    	$active = false;
    	foreach ($datas as $type) {
    		if ($type == "text" || $type == "sticker" || $type == "imagemap") {
    			$active = true;
    		} else {
    			$active = false;
    		}
   			$lineMessageType = new LineMessageType([
   				'type'  	=> $type,
   				'active'	=>	$active
   			]);
   			if ($lineMessageType) {
   				$lineMessageType->save();
   			}
   			unset($lineMessageType);
    	}
    }
}
