<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //$this->call(UsersTableSeeder::class);
       // $this->call(UserTableSeeder::class);
        //$this->call(MessageFolderTableSeeder::class);
        //$this->call(CampaignTableSeeder::class);
        //$this->call(CampaignItemTableSeeder::class);
        $this->call(LineEmoticonSeeder::class);
        $this->call(LineMessageTypeSeeder::class);
        //$this->call(AddressInThailandSeeder::class);
        


    }
}
