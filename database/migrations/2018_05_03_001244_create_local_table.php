<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLocalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('local', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('persona_id')->unsigned();
            $table->decimal('precio', 7, 2);
            //$table->integer('idPrecio')->unsigned();
            $table->decimal('largo', 7, 2);
            $table->decimal('ancho', 7, 2);
            $table->string('ubicacion', 50);
            $table->string('direccion', 100);
            $table->integer('nbaños');
            $table->string('descripcion', 250)->nullable();
            $table->binary('foto');
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
        Schema::dropIfExists('local');
    }
}
