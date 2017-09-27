<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGenCatColonias extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('corporativo')->create('gen_cat_colonias', function (Blueprint $table) {
                    $table->increments('id_colonia');
                    $table->string('colonia',50);
                    $table->integer('fk_id_municipio');
                    $table->boolean('activo')->default(true);
                    $table->boolean('eliminar')->default(false);

                    $table->foreign('fk_id_municipio')->references('id_municipio')->on('gen_cat_municipios')
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
        Schema::connection('corporativo')->dropIfExists('gen_cat_colonias');
    }
}
