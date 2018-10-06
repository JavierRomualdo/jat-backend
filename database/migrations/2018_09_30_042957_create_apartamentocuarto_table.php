<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApartamentocuartoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('apartamentocuarto', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('apartamento_id')->unsigned();
            $table->integer('persona_id')->unsigned();
            $table->decimal('precio', 7, 2);
            $table->decimal('largo', 7, 2);
            $table->decimal('ancho', 7, 2);
            //$table->integer('idPrecio')->unsigned();
            //$table->string('ubicacion', 50);
            $table->integer('piso')->default(0);// piso
            $table->integer('nbanios')->default(0);
            $table->string('descripcion', 255)->nullable();
            // $table->binary('foto');
            $table->string('path', 50)->nullable();
            $table->string('foto', 250)->nullable();
            $table->integer('nmensajes')->default(0);
            $table->boolean('estado')->default(true);

            $table->timestamps();
            $table->softDeletes();

            //$table->foreign('idPrecio')->references('id')->on('precios');
            $table->foreign('apartamento_id')->references('id')->on('apartamento');
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
        Schema::dropIfExists('apartamentocuarto');
    }
}
