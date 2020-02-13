<?php

use Illuminate\Database\Seeder;

class CodesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       DB::table('codes')->insert([
            'code' => 'prueba2019',
            'amount' => '30',
            'porcentual' => '1',
            'burnable' => '1',
            'active' => '0',
            'restricted' => '0'
        ]);
       DB::table('codes')->insert([
            'code' => 'pruebaIp2019',
            'amount' => '18',
            'porcentual' => '1',
            'burnable' => '1',
            'active' => '0',
            'res_type' => 'ip',
            'res_value' => '181.141.228.208',
            'restricted' => '1'
        ]);
       DB::table('codes')->insert([
            'code' => 'pruebaDate2019',
            'amount' => '18',
            'porcentual' => '1',
            'burnable' => '1',
            'active' => '0',
            'res_type' => 'date',
            'res_value' => '11-12-2019',
            'restricted' => '1'
        ]);
       DB::table('codes')->insert([
            'code' => 'pruebaDoc2019',
            'amount' => '4500',
            'porcentual' => '0',
            'burnable' => '0',
            'active' => '0',
            'res_type' => 'document',
            'res_value' => 'TEST000',
            'restricted' => '1'
        ]);
       DB::table('codes')->insert([
            'code' => 'pruebaMail2019',
            'amount' => '20000',
            'porcentual' => '0',
            'burnable' => '0',
            'active' => '0',
            'res_type' => 'email',
            'res_value' => 'ludcis.sas@gmail.com',
            'restricted' => '1'
        ]);
       DB::table('codes')->insert([
            'code' => 'pruebaFree2019',
            'amount' => '100',
            'porcentual' => '1',
            'burnable' => '0',
            'active' => '1',
            'res_type' => 'document',
            'res_value' => 'DCP008',
            'restricted' => '1'
        ]);
    }
}
