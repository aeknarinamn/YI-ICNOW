<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Carbon\Carbon;

class CampaignItemTableSeeder extends Seeder
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
            DB::table('fact_campaigns')->insert([
                'campaign_id'		=> rand(1,10),
                'order_id'			=> $index,
                'message_type'		=> rand(1,4),
                'payload'		    => $faker->name,
            ]);
        }
    }
}
