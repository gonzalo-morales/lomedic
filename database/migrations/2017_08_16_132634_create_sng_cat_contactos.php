<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSngCatContactos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('corporativo')->create('sng_cat_contactos', function (Blueprint $table) {
            $table->increments('id_contacto');
            $table->integer('fk_id_socio_negocio');
            $table->integer('fk_id_tipo_contacto');
            $table->string('nombre',100);
            $table->string('puesto',80);
            $table->string('telefono_oficina',30);
            $table->string('extension_oficina',5);
            $table->string('celular',30);
            // $table->integer('fk_id_correo_contacto');
            $table->boolean('activo')->default(true);
            $table->boolean('eliminar')->default(false);

            $table->foreign('fk_id_socio_negocio')->references('id_socio_negocio')->on('gen_cat_socios_negocio')
                ->onUpdate('restrict')->onDelete('restrict');
            $table->foreign('fk_id_tipo_contacto')->references('id_tipo_contacto')->on('sng_cat_tipos_contacto')
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
        Schema::connection('corporativo')->dropIfExists('sng_cat_contactos');
    }
}
