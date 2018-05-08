<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLocalservicioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('localservicio', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('local_id')->unsigned();
            $table->integer('servicio_id')->unsigned();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('local_id')->references('id')->on('local');
            $table->foreign('servicio_id')->references('id')->on('servicios');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('localservicio');
    }
}
