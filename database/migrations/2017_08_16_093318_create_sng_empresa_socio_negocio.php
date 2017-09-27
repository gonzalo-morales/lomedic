<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSngEmpresaSocioNegocio extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('corporativo')->create('sng_empresa_socio_negocio', function (Blueprint $table) {
                    // $table->increments('id_empresa');
                    $table->integer('fk_id_empresa');
                    $table->integer('fk_id_socio_negocio');
                    $table->boolean('activo')->default(true);
                    $table->boolean('eliminar')->default(false);

                    $table->foreign('fk_id_empresa')->references('id_empresa')->on('gen_cat_empresas')
                        ->onUpdate('restrict')->onDelete('restrict');
                    $table->foreign('fk_id_socio_negocio')->references('id_socio_negocio')->on('gen_cat_socios_negocio')
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
        Schema::connection('corporativo')->dropIfExists('sng_empresa_socio_negocio');
    }
}
