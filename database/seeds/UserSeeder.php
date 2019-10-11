<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        for ($i=0; $i < 2; $i++) {
            DB::table('users')->insert(array(
           'name' => $faker->firstNameFemale,
           'email' => preg_replace('/@example\..*/', '@domain.com', $faker->unique()->safeEmail),
           'email_verified_at' => date('Y-m-d H:m:s'),
           'password' => bcrypt('test@123'),
           'remember_token' => str_random(10),
           'created_at' => date('Y-m-d H:m:s'),
           'updated_at' => date('Y-m-d H:m:s'),
    ));
    }
}
}