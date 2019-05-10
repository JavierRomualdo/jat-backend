<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLotearchivoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lotearchivo', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('lote_id')->unsigned();
            $table->string('nombre', 250);
            $table->string('archivo', 250);
            $table->string('tipoarchivo', 5);
            $table->boolean('estado')->default(true);

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('lote_id')->references('id')->on('lote');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lotearchivo');
    }
}
