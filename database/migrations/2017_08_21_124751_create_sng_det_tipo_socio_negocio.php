<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSngDetTipoSocioNegocio extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('corporativo')->create('sng_det_tipo_socio_negocio', function (Blueprint $table) {
            $table->integer('fk_id_socio_negocio');
            $table->integer('fk_id_tipo_socio');
            // $table->boolean('activo')->default(true);
            // $table->boolean('eliminar')->default(false);

            $table->foreign('fk_id_socio_negocio')->references('id_socio_negocio')->on('gen_cat_socios_negocio')
                ->onUpdate('restrict')->onDelete('restrict');
            $table->foreign('fk_id_tipo_socio')->references('id_tipo_socio')->on('sng_cat_tipos_socio_negocio')
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
        Schema::connection('corporativo')->dropIfExists('sng_det_tipo_socio_negocio');
    }
}
