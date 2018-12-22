<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlquilerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alquiler', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('apartamento_id')->unsigned()->nullable();;
            $table->integer('casa_id')->unsigned()->nullable();;
            $table->integer('cochera_id')->unsigned()->nullable();;
            $table->integer('local_id')->unsigned()->nullable();;
            $table->integer('lote_id')->unsigned()->nullable();;
            $table->integer('persona_id')->unsigned(); // cliente
            $table->string('fecha', 20); // fecha alquiler
            $table->string('fechacontrato', 20); // fecha venta
            $table->boolean('estado')->default(true);

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('apartamento_id')->references('id')->on('apartamento');
            $table->foreign('casa_id')->references('id')->on('casa');
            $table->foreign('cochera_id')->references('id')->on('cochera');
            $table->foreign('local_id')->references('id')->on('local');
            $table->foreign('lote_id')->references('id')->on('lote');
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
        Schema::dropIfExists('alquiler');
    }
}
