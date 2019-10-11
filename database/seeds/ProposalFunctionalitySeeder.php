<?php

use Illuminate\Database\Seeder;

class ProposalFunctionalitySeeder extends Seeder
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
       	DB::table('proposal_functionalities')->insert(array(
           'proposal_id' => random_int(1,10),
           'functionality_id' => random_int(1,3),
    ));
    }
}}