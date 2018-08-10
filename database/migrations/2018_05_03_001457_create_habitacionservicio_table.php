<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHabitacionservicioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('habitacionservicio', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('habitacion_id')->unsigned();
            $table->integer('servicio_id')->unsigned();
            $table->boolean('estado')->default(true);
            $table->timestamps();
            $table->softDeletes();            

            $table->foreign('habitacion_id')->references('id')->on('habitacion');
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
        Schema::dropIfExists('habitacionservicio');
    }
}
