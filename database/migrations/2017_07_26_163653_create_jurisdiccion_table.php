<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJurisdiccionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('corporativo')
            ->create('gen_cat_jurisdiccion', function (Blueprint $table) {
            $table->increments('id_jurisdiccion');
            $table->string('jurisdiccion');
            $table->integer('fk_id_estado');
            $table->boolean('activo')->default('true');
            $table->boolean('eliminar')->default('false');

            $table->foreign('fk_id_estado')->references('id_estado')->on('gen_cat_estados')
                ->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('corporativo')
            ->dropIfExists('gen_cat_jurisdiccion');
    }
}
