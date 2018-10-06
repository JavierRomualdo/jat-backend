<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCasamensajeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('casamensaje', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('casa_id')->unsigned();
            $table->string('nombres', 50);
            $table->string('telefono', 15);
            $table->string('email', 50)->nullable();
            $table->string('titulo', 50);
            $table->string('mensaje', 255);
            $table->boolean('estado')->default(true);

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('casa_id')->references('id')->on('casa');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('casamensaje');
    }
}
