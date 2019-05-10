<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHabitacionarchivoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('habitacionarchivo', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('habitacion_id')->unsigned();
            $table->string('nombre', 250);
            $table->string('archivo', 250);
            $table->string('tipoarchivo', 5);
            $table->boolean('estado')->default(true);

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('habitacion_id')->references('id')->on('habitacion');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('habitacionarchivo');
    }
}
