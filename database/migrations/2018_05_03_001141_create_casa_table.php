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
            //$table->integer('idPrecio')->unsigned();
            $table->integer('npisos');
            $table->integer('ncuartos');
            $table->integer('nbaños');
            $table->boolean('tjardin')->default(false);
            $table->boolean('tcochera')->default(false);
            $table->decimal('largo', 7, 2);
            $table->decimal('ancho', 7, 2);
            $table->string('direccion', 100);
            $table->string('ubicacion', 50);
            $table->binary('foto');
            $table->string('descripcion', 100)->nullable();
            //$table->bit('estado');
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
