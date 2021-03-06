<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersonaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('persona', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('rol_id')->unsigned();
            $table->integer('ubigeo_id')->unsigned();
            $table->string('dni', 8);
            $table->string('nombres', 50);
            $table->string('correo', 50)->nullable();
            //$table->string('ubicacion', 50);
            $table->string('direccion', 100);
            $table->string('telefono', 9);
            $table->boolean('estado')->default(true);
            
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('rol_id')->references('id')->on('rol');
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
        Schema::dropIfExists('persona');
    }
}
