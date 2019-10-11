<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;


class LiveplanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        $array_name = array('Básico','Medio','Premium');
        $array_channels = array('2','4','10');
        $array_capacity = array('2TB','4TB','10TB');
        $array_concurrency = array('500','1000','3000');
        $rand_pay = array('Monthly fee', 'Set-up');

        for ($i=0; $i < 3; $i++) {
            DB::table('liveplans')->insert(array(
           'name' =>$array_name[$i],
           'channels' => $array_channels[$i],
           'capacity' =>$array_capacity[$i],
           'concurrency' =>$array_concurrency[$i],
           'price' => $faker->randomFloat(8,1,4),
           //'payment' => $rand_pay[random_int(0,1)],
    ));
    }
    }
}
