<?php

use Illuminate\Database\Seeder;

class ResolutionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       DB::table('resolutions')->insert([
            'prefix' => '',
            'res_number' => '18763001110976',
            'res_expedition' => '2019-10-17',
            'start_number' => '1001',
            'end_number' => '2000',
            'start_date' => '2019-10-17',
            'end_date' => '2020-10-16',
        ]);
    }
}
