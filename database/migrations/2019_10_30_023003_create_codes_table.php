<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('codes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code')->unique(); //Y-m-d-h-i-s-code-ip
            $table->double('amount')->default(0);
            $table->integer('porcentual')->default(0);
            $table->integer('burnable')->default(1);
            $table->integer('active')->default(1);
            $table->integer('restricted')->default(0);
            $table->string('res_type')->nullable();
            $table->string('res_value')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('codes');
    }
}
