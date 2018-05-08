<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lote', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('persona_id')->unsigned();
            //$table->integer('idPrecio')->unsigned();
            $table->decimal('largo', 7, 2);
            $table->decimal('ancho', 7, 2);
            $table->string('direccion', 100);
            $table->string('ubicacion', 50);
            $table->binary('foto');
            $table->string('descripcion', 100)->nullable();
            //$table->bit('estado');
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
        Schema::dropIfExists('lote');
    }
}
