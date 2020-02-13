<?php

use Illuminate\Database\Seeder;

class ProductsForSearchTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       DB::table('products_for_search')->insert([
            'name' => 'pagaré',
            'code' => 'DCP001',
            'value' => '9900',
            'page' => 'https://ludcis.com/documento-pagare'
        ]);
       DB::table('products_for_search')->insert([
            'name' => 'contrato de confidencialidad',
            'code' => 'DCC002',
            'value' => '19900',
            'page' => 'https://ludcis.com/contrato-confidencialidad'
        ]);
       DB::table('products_for_search')->insert([
            'name' => 'contrato de trabajo',
            'code' => 'DCT003',
            'value' => '19900',
            'page' => 'https://ludcis.com/contrato-trabajo'
        ]);
       DB::table('products_for_search')->insert([
            'name' => 'contrato de prestación de servicios',
            'code' => 'DCS004',
            'value' => '19900',
            'page' => 'https://ludcis.com/contrato-servicios'
        ]);
       DB::table('products_for_search')->insert([
            'name' => 'contrato de trabajo servicio domestico',
            'code' => 'DCD005',
            'value' => '19900',
            'page' => 'https://ludcis.com/contrato-domestico'
        ]);
       DB::table('products_for_search')->insert([
            'name' => 'contrato de arrendamiento local comercial',
            'code' => 'DCA006',
            'value' => '19900',
            'page' => 'https://ludcis.com/contrato-arrendamiento-local'
        ]);
       DB::table('products_for_search')->insert([
            'name' => 'derecho de petición fotodetección',
            'code' => 'DPT007',
            'value' => '14900',
            'page' => 'https://ludcis.com/peticion-fotodeteccion'
        ]);
       DB::table('products_for_search')->insert([
            'name' => 'cobro prejurídico',
            'code' => 'DCP008',
            'value' => '9900',
            'page' => 'https://ludcis.com/cobro-prejuridico'
        ]);
       DB::table('products_for_search')->insert([
            'name' => 'contrato de cesion',
            'code' => 'DCC009',
            'value' => '19900',
            'page' => 'https://ludcis.com/contrato-cesion'
        ]);
       DB::table('products_for_search')->insert([
            'name' => 'poder otorgado por persona natural',
            'code' => 'DPN010',
            'value' => '9900',
            'page' => 'https://ludcis.com/poder-natural'
        ]);
       DB::table('products_for_search')->insert([
            'name' => 'poder otorgado por representante legal',
            'code' => 'DPC011',
            'value' => '9900',
            'page' => 'https://ludcis.com/poder-ceo'
        ]);
       DB::table('products_for_search')->insert([
            'name' => 'contrato de comodato',
            'code' => 'DCC012',
            'value' => '19900',
            'page' => 'https://ludcis.com/contrato-comodato'
        ]);
       DB::table('products_for_search')->insert([
            'name' => 'contrato de compraventa',
            'code' => 'DCC013',
            'value' => '19900',
            'page' => 'https://ludcis.com/compraventa-vehiculo'
        ]);
    }
}
