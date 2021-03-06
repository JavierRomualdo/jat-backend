<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApartamentoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('apartamento', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ubigeo_id')->unsigned();
            $table->string('codigo', 7);
            $table->decimal('precioadquisicion', 9, 2);
            $table->decimal('preciocontrato', 9, 2);
            $table->decimal('ganancia', 9, 2);
            $table->decimal('largo', 7, 2);
            $table->decimal('ancho', 7, 2);
            $table->decimal('npisos', 7, 2);
            $table->string('direccion', 100);
            $table->boolean('tcochera')->default(false);
            $table->string('descripcion', 255)->nullable();
            $table->string('path', 50)->nullable();
            $table->string('foto', 250)->nullable();
            $table->integer('nmensajes')->default(0);
            $table->char('contrato',1);
            $table->char('estadocontrato',1)->default('L');
            $table->boolean('estado')->default(true);

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('ubigeo_id')->references('id')->on('ubigeo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('apartamento');
    }
}
