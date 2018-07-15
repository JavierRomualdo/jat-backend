<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLocalfotoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('localfoto', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('local_id')->unsigned();
            $table->integer('foto_id')->unsigned();
            $table->boolean('estado')->default(true);

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('local_id')->references('id')->on('local');
            $table->foreign('foto_id')->references('id')->on('foto');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('localfoto');
    }
}
