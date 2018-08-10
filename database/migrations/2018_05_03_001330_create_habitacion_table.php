<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHabitacionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('habitacion', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('persona_id')->unsigned();
            $table->decimal('precio', 7, 2);
            $table->decimal('largo', 7, 2);
            $table->decimal('ancho', 7, 2);
            //$table->integer('idPrecio')->unsigned();
            $table->string('ubicacion', 50);
            $table->string('direccion', 100);
            $table->integer('ncamas');
            $table->boolean('tbanio')->default(false);
            $table->string('descripcion', 250)->nullable();
            $table->string('path', 50)->nullable();
            $table->string('foto', 250)->nullable();
            $table->boolean('estado')->default(true);
            $table->timestamps();
            $table->softDeletes();            

            $table->foreign('persona_id')->references('id')->on('persona');
            //$table->foreign('idPrecio')->references('id')->on('precios');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('habitacion');
    }
}
