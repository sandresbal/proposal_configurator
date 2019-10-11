<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;


class FunctionalityBizmodelSeeder extends Seeder
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
       	DB::table('functionality_bizmodel')->insert(array(
           'functionality_id' => random_int(1,10),
           'bizmodel_id' => random_int(1,3)
           ));
        }
    }
}
