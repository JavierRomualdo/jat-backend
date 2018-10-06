<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmpresaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empresa', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ubigeo_id')->unsigned();
            $table->string('nombre', 50);
            $table->string('ruc', 11);
            //$table->string('ubicacion', 50);
            $table->string('direccion', 100);
            $table->string('telefono', 15);
            $table->string('correo', 50);
            $table->string('nombrefoto', 250)->nullable();
            $table->string('foto', 250)->nullable();
            $table->boolean('estado')->default(true);
            $table->timestamps();

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
        Schema::dropIfExists('empresa');
    }
}
