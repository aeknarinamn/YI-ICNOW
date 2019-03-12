<?php

use YellowProject\User;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Generate Users
        $users = array(
            array('name' => 'เอก','username'=>'aeknarin@1','email' => 'aeknarinS@Coffeebackend.co.th','password' => bcrypt('Passw0rd@1'),'position' => 'Programmer','telephone' => '02-7777777'),
            array('name' => 'เท่ห์','username'=>'wisa@1','email' => 'wisal@Coffeebackend.co.th','password' => bcrypt('Passw0rd@1'),'position' => 'Developer','telephone' => '02-5555555'),
            array('name' => 'ไอส์','username'=>'supabhorn@1','email' => 'supabhorn@Coffeebackend.co.th','password' => bcrypt('Passw0rd@1'),'position' => 'Developer','telephone' => '02-8888888'),
            array('name' => 'หนุ่ย','username'=>'suradath@1','email' => 'Bondnuy007@Coffeebackend.co.th','password' => bcrypt('Passw0rd@1'),'position' => 'Developer','telephone' => '02-9999999'),
            array('name' => 'แซม','username'=>'Sameme@1','email' => 'Sameme@Coffeebackend.co.th','password' => bcrypt('Passw0rd@1'),'position' => 'Developer','telephone' => '02-9999999'),
        );

        foreach ($users as $user) {
            $user = User::create(array(
                'name'=> $user['name'] ,
                'username'=>$user['username'],
                'email' => $user['email'] ,
                'password'=> $user['password'] ,
                'position'=> $user['position'] ,
                'telephone'=> $user['telephone'] ,
            ));
        }
    }
}
