<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;


class CdnSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        $faker = Faker::create();
        $array_name = array('Nucleus','Element','Compound', 'Custom');
        $array_capacity = array('2TB','4TB','8TB', 'Custom');
        $array_concurrency = array('1000','2500','5000', 'Custom');
        $array_encoding= array('20GB/mes','30GB/mes','40GB/mes', 'Custom');
        $rand_pay = array('Monthly fee', 'Set-up');

        for ($i=0; $i < 4; $i++) {
            DB::table('cdns')->insert(array(
           'name' =>$array_name[$i],
           'capacity' =>$array_capacity[$i],
           'concurrency' =>$array_concurrency[$i],
           'encoding' =>$array_encoding[$i],
           'price' => $faker->randomFloat(8,1,4),
           'payment' => $rand_pay[random_int(0,1)],
    ));
        }
}
}
