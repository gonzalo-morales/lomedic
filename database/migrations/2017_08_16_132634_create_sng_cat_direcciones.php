<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSngCatDirecciones extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('corporativo')->create('sng_cat_direcciones', function (Blueprint $table) {
            $table->increments('id_direccion');
            $table->integer('fk_id_socio_negocio');
            $table->integer('fk_id_pais');
            $table->integer('fk_id_estado');
            $table->integer('fk_id_municipio');
            $table->integer('fk_id_colonia');
            $table->integer('fk_id_tipo_direccion');
            $table->string('calle',100);
            $table->string('num_exterior',10);
            $table->string('num_interior',10);
            $table->string('cp',10);
            $table->boolean('activo')->default(true);
            $table->boolean('eliminar')->default(false);

            $table->foreign('fk_id_socio_negocio')->references('id_socio_negocio')->on('gen_cat_socios_negocio')
                ->onUpdate('restrict')->onDelete('restrict');
            $table->foreign('fk_id_pais')->references('id_pais')->on('gen_cat_paises')
                ->onUpdate('restrict')->onDelete('restrict');
            $table->foreign('fk_id_estado')->references('id_estado')->on('gen_cat_estados')
                ->onUpdate('restrict')->onDelete('restrict');
            $table->foreign('fk_id_municipio')->references('id_municipio')->on('gen_cat_municipios')
                ->onUpdate('restrict')->onDelete('restrict');
            $table->foreign('fk_id_colonia')->references('id_colonia')->on('gen_cat_colonias')
                ->onUpdate('restrict')->onDelete('restrict');
            $table->foreign('fk_id_tipo_direccion')->references('id_tipo_direccion')->on('sng_cat_tipos_direccion')
                ->onUpdate('restrict')->onDelete('restrict');
            // $table->foreign('fk_id_correo_contacto')->references('fk_id_contacto')->on('sng_cat_correos_contacto')
            //     ->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('corporativo')->dropIfExists('sng_cat_direcciones');
    }
}
