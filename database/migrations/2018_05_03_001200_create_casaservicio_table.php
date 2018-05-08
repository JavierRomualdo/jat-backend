<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCasaservicioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('casaservicio', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('casa_id')->unsigned();
            $table->integer('servicio_id')->unsigned();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('casa_id')->references('id')->on('casa');
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
        Schema::dropIfExists('casaservicio');
    }
}
