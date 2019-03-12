<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use YellowProject\MessageFolder;

class MessageFolderTableSeeder extends Seeder
{
        /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Generate Field
        $messageFolders = array(
            array('name' => 'MessageFolder A','description' => 'descriptionMessageFolder A'),
            array('name' => 'MessageFolder B','description' => 'descriptionMessageFolder B'),
            array('name' => 'MessageFolder C','description' => 'descriptionMessageFolder C'),
            array('name' => 'MessageFolder D','description' => 'descriptionMessageFolder D'),
        );

        foreach ($messageFolders as $messageFolder) {
	        $messageFolder = MessageFolder::create(array(
	            'name'       => $messageFolder['name'] ,
	            'description'=> $messageFolder['description'] ,
	        ));
    	}

    }
    
}
