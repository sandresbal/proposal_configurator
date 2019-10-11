<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;


class BizmodelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $array_bizmodels = array('advod','svod','ppvvod');
        $faker = Faker::create();
        for ($i=0; $i < 3; $i++) {
            DB::table('bizmodels')->insert(array(            
            'name' => $array_bizmodels[$i],
    ));
    }
}
}
