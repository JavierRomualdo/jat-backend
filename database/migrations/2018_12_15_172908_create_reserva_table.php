<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReservaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reserva', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('apartamento_id')->unsigned();
            $table->integer('casa_id')->unsigned();
            $table->integer('cochera_id')->unsigned();
            $table->integer('local_id')->unsigned();
            $table->integer('lote_id')->unsigned();
            $table->integer('persona_id')->unsigned(); // cliente
            $table->integer('ubigeo_id')->unsigned();
            $table->date('fecha'); // fecha reserva
            $table->date('fechalimite');
            $table->boolean('estado')->default(true);

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('apartamento_id')->references('id')->on('apartamento');
            $table->foreign('casa_id')->references('id')->on('casa');
            $table->foreign('cochera_id')->references('id')->on('cochera');
            $table->foreign('local_id')->references('id')->on('local');
            $table->foreign('lote_id')->references('id')->on('lote');
            $table->foreign('persona_id')->references('id')->on('persona');
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
        Schema::dropIfExists('reserva');
    }
}
