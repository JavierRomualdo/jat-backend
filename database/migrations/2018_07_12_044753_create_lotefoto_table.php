<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLotefotoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lotefoto', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('lote_id')->unsigned();
            $table->integer('foto_id')->unsigned();
            $table->boolean('estado')->default(true);

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('lote_id')->references('id')->on('lote');
            $table->foreign('foto_id')->references('id')->on('foto');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lotefoto');
    }
}
