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
            'value' => '9900',
            'page' => 'https://ludcis.com/documento-pagare',
            'pdf' => 'Views/documents/Manuals/PAGARE.pdf'
        ]);
       DB::table('products')->insert([
            'name' => 'contrato de confidencialidad',
            'code' => 'DCC002',
            'view' => 'layouts.confidentiality',
            'value' => '19900',
            'page' => 'https://ludcis.com/contrato-confidencialidad',
            'pdf' => 'Views/documents/Manuals/ACUERDO_DE_CONFIDENCIALIDAD.pdf'
        ]);
       DB::table('products')->insert([
            'name' => 'contrato de trabajo',
            'code' => 'DCT003',
            'view' => 'layouts.work_contract',
            'value' => '19900',
            'page' => 'https://ludcis.com/contrato-trabajo',
            'pdf' => 'Views/documents/Manuals/TRABAJO.pdf'
        ]);
       DB::table('products')->insert([
            'name' => 'contrato de prestación de servicios',
            'code' => 'DCS004',
            'view' => 'layouts.services_contract',
            'value' => '19900',
            'page' => 'https://ludcis.com/contrato-servicios',
            'pdf' => 'Views/documents/Manuals/PRESTACION_DE_SERVICIOS.pdf'
        ]);
       DB::table('products')->insert([
            'name' => 'contrato de trabajo servicio domestico',
            'code' => 'DCD005',
            'view' => 'layouts.domestic_contract',
            'value' => '19900',
            'page' => 'https://ludcis.com/contrato-domestico',
            'pdf' => 'Views/documents/Manuals/TRABAJO_EMPLEADO_DOMESTICO.pdf'
        ]);
       DB::table('products')->insert([
            'name' => 'contrato de arrendamiento local comercial',
            'code' => 'DCA006',
            'view' => 'layouts.rent_contract',
            'value' => '19900',
            'page' => 'https://ludcis.com/contrato-arrendamiento-local'
        ]);
       DB::table('products')->insert([
            'name' => 'derecho de petición fotodetección',
            'code' => 'DPT007',
            'view' => 'layouts.transit_petition',
            'value' => '14900',
            'page' => 'https://ludcis.com/peticion-fotodeteccion',
            'pdf' => 'Views/documents/Manuals/FOTODETECCION.pdf'
        ]);
       DB::table('products')->insert([
            'name' => 'cobro prejurídico',
            'code' => 'DCP008',
            'view' => 'layouts.legal_charge',
            'value' => '9900',
            'page' => 'https://ludcis.com/cobro-prejuridico',
            'pdf' => 'Views/documents/Manuals/COBRO_PRE_JURIDICO.pdf'
        ]);
       DB::table('products')->insert([
            'name' => 'contrato de cesión',
            'code' => 'DCC009',
            'view' => 'layouts.contract_transfer',
            'value' => '19900',
            'page' => 'https://ludcis.com/contrato-cesion',
            'pdf' => 'Views/documents/Manuals/CESION.pdf'
        ]);
       DB::table('products')->insert([
            'name' => 'poder otorgado por persona natural',
            'code' => 'DPN010',
            'view' => 'layouts.natural_person_power',
            'value' => '9900',
            'page' => 'https://ludcis.com/poder-natural',
            'pdf' => 'Views/documents/Manuals/PODER_PERSONA_NATURAL.pdf'
        ]);
       DB::table('products')->insert([
            'name' => 'poder otorgado por representante legal',
            'code' => 'DPC011',
            'view' => 'layouts.CEO_power',
            'value' => '9900',
            'page' => 'https://ludcis.com/poder-ceo',
            'pdf' => 'Views/documents/Manuals/PODER_REPRESENTANTE_LEGAL.pdf'
        ]);
       DB::table('products')->insert([
            'name' => 'contrato de comodato',
            'code' => 'DCC012',
            'view' => 'layouts.comodate_contract',
            'value' => '19900',
            'page' => 'https://ludcis.com/contrato-comodato',
            'pdf' => 'Views/documents/Manuals/COMODATO.pdf'
        ]);
       DB::table('products')->insert([
            'name' => 'contrato de compraventa',
            'code' => 'DCC013',
            'view' => 'layouts.sale_contract',
            'value' => '19900',
            'page' => 'https://ludcis.com/compraventa-vehiculo',
            'pdf' => 'Views/documents/Manuals/COMPRAVENTA.pdf'
        ]);
       DB::table('products')->insert([
            'name' => 'contrato de teletrabajo',
            'code' => 'DTT014',
            'view' => 'layouts.remote_work_contract',
            'value' => '4900',
            'page' => 'https://ludcis.com/teletrabajo',
            'pdf' => 'Views/documents/Manuals/TRABAJO.pdf'
        ]);
       DB::table('products')->insert([
            'name' => 'otro sí de teletrabajo',
            'code' => 'OTT015',
            'view' => 'layouts.remote_work_modification',
            'value' => '0',
            'page' => 'https://ludcis.com/otrosi-teletrabajo',
            'pdf' => 'Views/documents/Manuals/TRABAJO.pdf'
        ]);
       DB::table('products')->insert([
            'name' => 'prueba',
            'code' => 'TEST000',
            'view' => 'test',
            'value' => '15000'
        ]);
    }
}
