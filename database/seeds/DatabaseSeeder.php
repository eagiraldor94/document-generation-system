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
        $this->call(ProductsTableSeeder::class);
        //$this->call(ProductsForSearchTableSeeder::class);
        //$this->call(ParametersTableSeeder::class);
        //$this->call(ResolutionsTableSeeder::class);
        //$this->call(CodesTableSeeder::class);
    }
}
