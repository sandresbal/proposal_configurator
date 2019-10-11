<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //$this->call(UsersTableSeeder::class);
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $this ->call('UserSeeder');
        $this ->call(BizmodelSeeder::class);
        $this ->call(CdnSeeder::class);
        $this ->call(LiveplanSeeder::class);
        $this ->call(DeviceSeeder::class);
        $this ->call(FunctionalityBizmodelSeeder::class);
        $this ->call(FunctionalitySeeder::class);
        $this ->call(ProposalDeviceSeeder::class);
        $this ->call(ProposalSeeder::class);
    }
}
