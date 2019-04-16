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
            $table->integer('ubigeo_id')->unsigned();
            $table->integer('habilitacionurbana_id')->unsigned();
            $table->string('codigo', 7);
            $table->decimal('precioadquisicion', 11, 2);
            $table->decimal('preciocontrato', 11, 2);
            $table->decimal('ganancia', 11, 2);
            $table->tinyInteger('largo');
            $table->tinyInteger('ancho');
            //$table->integer('idPrecio')->unsigned();
            //$table->string('ubicacion', 50);
            $table->string('nombrehabilitacionurbana', 100);
            $table->string('direccion', 100);
            $table->string('latitud', 50);
            $table->string('longitud', 50);
            $table->tinyInteger('npisos')->default(0); // max 127
            $table->tinyInteger('ncuartos')->default(0);
            $table->tinyInteger('nbanios')->default(0);
            $table->boolean('tjardin')->default(false);
            $table->boolean('tcochera')->default(false);
            $table->string('referencia', 255)->nullable();
            $table->string('descripcion', 765)->nullable();
            // $table->binary('foto');
            $table->string('path', 50)->nullable();
            $table->string('foto', 250)->nullable();
            $table->integer('nmensajes')->default(0);
            $table->char('contrato',1)->default('A');
            $table->char('estadocontrato',1)->default('L');
            $table->boolean('estado')->default(true);
            $table->timestamps();
            $table->softDeletes();

            //$table->foreign('idPrecio')->references('id')->on('precios');
            $table->foreign('persona_id')->references('id')->on('persona');
            $table->foreign('ubigeo_id')->references('id')->on('ubigeo');
            $table->foreign('habilitacionurbana_id')->references('id')->on('habilitacionurbana');
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
