<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;


class ProposalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$faker = Faker::create();
    	for ($i=0; $i < 30; $i++) {

        $array_status = array('final', 'draft');        
        $array_package = array('base', 'extra');
        $array_cms = array('fractal', 'none');

        DB::table('proposals') ->insert(array(
        	'company' => $faker->company,
        	'name' => $faker->catchPhrase,
        	//'description' => $faker->text($maxNbChars = 200),
        	'description' => $faker->catchPhrase,
        	'receiver' => $faker->firstName,
        	'creator' => $faker->firstName,
            'commitment' => $faker->randomElement($array_com = array(12,15)),
            'cms' => $array_cms[random_int(0,1)],
            'cdn' => random_int(1,4),
            'bizmodel' => random_int(1,3),
            'package' => $array_package[random_int(0,1)],
            'soporte24' => $faker->boolean,
            'cdn_cost_monthly' => $faker->randomFloat(8,0,10),
            'cdn_cost_setup'=> $faker->randomFloat(8,0,10), 
            'CMS_cost_monthly'=> $faker->randomFloat(8,0,10),
            'CMS_cost_extras_monthly' => $faker->randomFloat(8,0,10),
            'CMS_cost_extras_setup'=> $faker->randomFloat(8,0,10),   
            'web_cost_monthly'=> $faker->randomFloat(8,0,10),
            'web_extras_cost_monthly'=> $faker->randomFloat(8,0,10),
            'web_extras_cost_setup'=> $faker->randomFloat(8,0,10),
            'mobile_cost_monthly'=> $faker->randomFloat(8,0,10),
            'mobile_extras_cost_monthly'=> $faker->randomFloat(8,0,10),
            'mobile_extras_cost_setup'=> $faker->randomFloat(8,0,10),
            'tv_cost_monthly'=> $faker->randomFloat(8,0,10),
            'tv_extras_cost_monthly'=> $faker->randomFloat(8,0,10),
            'tv_extras_cost_setup'=> $faker->randomFloat(8,0,10),
            'price_live_month' => $faker->randomFloat(8,0,10),
            'soporte24_cost_monthly' => $faker->randomFloat(8,0,10),
            'monthly_cost_total'=> $faker->randomFloat(8,0,10),
            'set_up_cost_total'=> $faker->randomFloat(8,0,10),

            'status' => $array_status[random_int(0,1)],

        ));
    }
    }
}
