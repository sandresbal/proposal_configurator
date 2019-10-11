<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;


class FunctionalitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        $rand_dev = array('CMS', 'Web TV', 'Aplicaciones móviles', 'Aplicaciones de TV conectada');
        $rand_pay = array('Monthly fee', 'Set-up');
        $rand_atom = array('base','extra');

        for ($i=0; $i < 10; $i++) {

        DB::table('functionalities') ->insert(array(
        	'device'=> $rand_dev[random_int(0,3)],
        	'atom'=> $rand_atom[random_int(0,1)],
        	'name' => $faker->word,
        	'description_short' => $faker->text($maxNbChars = 200),
        	'description_long' => $faker->text($maxNbChars = 1000),
            'price_setup' => $faker->randomFloat(0,1,4),
            'price_monthly' => $faker->randomFloat(0,1,4),
        	//'payment' => $rand_pay[random_int(0,1)],
        	'units' => $faker->boolean,
        ));
    }

    }
}
