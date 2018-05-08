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
            $table->string('nombres', 50);
            $table->string('telefono', 15);
            $table->string('correo', 50)->nullable();
            $table->string('direccion', 100);
            $table->string('ubicacion', 50);
            $table->integer('rol_id')->unsigned();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('rol_id')->references('id')->on('rol');
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
