<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;


class ProposalDeviceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        for ($i=0; $i < 50; $i++) {
       	DB::table('proposal_devices')->insert(array(
           'proposal_id' => random_int(1,10),
           'device_id' => random_int(1,3),
    ));
    }
}
}
