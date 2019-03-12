<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Carbon\Carbon;

class CampaignTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        foreach (range(1,10) as $index) {
            DB::table('dim_campaigns')->insert([
                'segment_id'		=> rand(1,4),
                'message_folder_id'	=> rand(1,4),
                'report_tag_id'		=> rand(1,4),
                'name'				=> $faker->name,
                'message_status'    => rand(1,5),
                'send_status'       => rand(1,2),
                'sent_date'         => Carbon::now()->addDay(rand(1,100)),
                'last_sent_date'    => Carbon::now(),
            ]);
        }
    }
}
