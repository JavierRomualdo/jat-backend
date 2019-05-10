<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCocheraarchivoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cocheraarchivo', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cochera_id')->unsigned();
            $table->string('nombre', 250);
            $table->string('archivo', 250);
            $table->string('tipoarchivo', 5);
            $table->boolean('estado')->default(true);

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('cochera_id')->references('id')->on('cochera');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cocheraarchivo');
    }
}
