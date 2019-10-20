<?php

use Illuminate\Database\Seeder;

class ParametersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       DB::table('parameters')->insert([
            'name' => 'name',
            'value' => 'Ludcis S.A.S.'
        ]);
       DB::table('parameters')->insert([
            'name' => 'NIT',
            'value' => '901323761-1'
        ]);
    }
}
