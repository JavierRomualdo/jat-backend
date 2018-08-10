<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCasaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('casa', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('persona_id')->unsigned();
            $table->decimal('precio', 7, 2);
            $table->decimal('largo', 7, 2);
            $table->decimal('ancho', 7, 2);
            //$table->integer('idPrecio')->unsigned();
            $table->string('ubicacion', 50);
            $table->string('direccion', 100);
            $table->integer('npisos')->default(0);
            $table->integer('ncuartos')->default(0);
            $table->integer('nbanios')->default(0);
            $table->boolean('tjardin')->default(false);
            $table->boolean('tcochera')->default(false);
            $table->string('descripcion', 250)->nullable();
            // $table->binary('foto');
            $table->string('path', 50)->nullable();
            $table->string('foto', 250)->nullable();
            $table->boolean('estado')->default(true);
            $table->timestamps();
            $table->softDeletes();

            //$table->foreign('idPrecio')->references('id')->on('precios');
            $table->foreign('persona_id')->references('id')->on('persona');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('casa');
    }
}
