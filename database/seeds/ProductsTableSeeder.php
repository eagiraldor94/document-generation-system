<?php

use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       DB::table('products')->insert([
            'name' => 'pagaré',
            'code' => 'DCP001',
            'view' => 'layouts.pagare',
            'value' => '0'
        ]);
       DB::table('products')->insert([
            'name' => 'contrato de confidencialidad',
            'code' => 'DCC002',
            'view' => 'layouts.confidentiality',
            'value' => '0'
        ]);
       DB::table('products')->insert([
            'name' => 'contrato de trabajo',
            'code' => 'DCT003',
            'view' => 'layouts.work_contract',
            'value' => '0'
        ]);
       DB::table('products')->insert([
            'name' => 'contrato de prestación de servicios',
            'code' => 'DCS004',
            'view' => 'layouts.services_contract',
            'value' => '0'
        ]);
       DB::table('products')->insert([
            'name' => 'contrato de trabajo servicio domestico',
            'code' => 'DCD005',
            'view' => 'layouts.domestic_contract',
            'value' => '0'
        ]);
       DB::table('products')->insert([
            'name' => 'contrato de arrendamiento',
            'code' => 'DCA006',
            'view' => 'layouts.rent_contract',
            'value' => '0'
        ]);
       DB::table('products')->insert([
            'name' => 'prueba',
            'code' => 'TEST000',
            'view' => 'test',
            'value' => '15000'
        ]);
    }
}
