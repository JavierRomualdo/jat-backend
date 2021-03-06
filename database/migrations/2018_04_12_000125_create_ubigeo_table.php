<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUbigeoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ubigeo', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tipoubigeo_id')->unsigned();
            $table->integer('habilitacionurbana_id')->unsigned()->nullable();
            $table->string('ubigeo', 50);
            $table->string('rutaubigeo', 100);
            $table->string('codigo', 10)->nullable();
            $table->boolean('estado')->default(true);

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('tipoubigeo_id')->references('id')->on('ubigeotipo');
            $table->foreign('habilitacionurbana_id')->references('id')->on('habilitacionurbana');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ubigeo');
    }
}
