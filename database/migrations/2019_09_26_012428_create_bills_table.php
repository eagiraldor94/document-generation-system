<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bills', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('document_id');
            $table->foreign('document_id')->references('id')->on('documents')->onDelete('cascade');
            $table->unsignedBigInteger('resolution_id')->nullable();
            $table->foreign('resolution_id')->references('id')->on('resolutions')->onDelete('cascade');
            $table->bigInteger('number')->nullable();
            $table->string('name');
            $table->string('id_type');
            $table->string('id_number');
            $table->string('email');
            $table->double('total')->default(0);
            $table->double('tax')->default(0);
            $table->double('base')->default(0);
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
        Schema::dropIfExists('bills');
    }
}
