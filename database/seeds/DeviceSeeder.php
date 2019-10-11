<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;


class DeviceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        $array_name = array('CMS', 'Web TV', 'Aplicaciones móviles', 'Aplicaciones de TV conectada');
        $array_os = array('Android', 'iOS', 'Samsung', 'LG', 'Android', 'Apple', 'Amazon');
        $array_payment = array('montly fee', 'set-up');

        for ($i=0; $i < 10; $i++) {
        DB::table('devices')->insert(array(
        'name' => $array_name[random_int(0,3)],
        'os' => $array_os [random_int(0,6)],
        'price' =>$faker->randomFloat(0,1,5),
        'payment' => $array_payment[random_int(0,1)],
        ));
    }
}}
