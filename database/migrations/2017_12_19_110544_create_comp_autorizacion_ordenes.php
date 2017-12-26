<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompAutorizacionOrdenes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('abisa')->create('com_det_autorizaciones', function (Blueprint $table) {
            $table->increments('id_autorizacion');
            $table->integer('fk_id_tipo_documento');
            $table->integer('fk_id_condicion');
            $table->integer('fk_id_usuario_autoriza');
            $table->integer('fk_id_estatus');
            $table->date('fecha_creacion');
            $table->date('fecha_autorizacion');
            $table->string('observaciones');
            $table->boolean('activo')->default(true);
            $table->boolean('eliminar')->default(false);

            $table->foreign('fk_id_condicion')->references('id_condicion')->on('com_cat_condiciones_autorizacion')
                ->onUpdate('restrict')->onDelete('restrict');
            $table->foreign('fk_id_usuario_autoriza')->references('id_usuario')->on('maestro.ges_cat_usuarios')
                ->onUpdate('restrict')->onDelete('restrict');
            $table->foreign('fk_id_estatus')->references('id_estatus')->on('maestro.com_cat_estatus_autorizaciones')
                ->onUpdate('restrict')->onDelete('restrict');
        });
        Schema::connection('lomedic')->create('com_det_autorizaciones', function (Blueprint $table) {
            $table->increments('id_autorizacion');
            $table->integer('fk_id_tipo_documento');
            $table->integer('fk_id_condicion');
            $table->integer('fk_id_usuario_autoriza');
            $table->integer('fk_id_estatus');
            $table->date('fecha_creacion');
            $table->date('fecha_autorizacion');
            $table->string('observaciones');
            $table->boolean('activo')->default(true);
            $table->boolean('eliminar')->default(false);

            $table->foreign('fk_id_condicion')->references('id_condicion')->on('com_cat_condiciones_autorizacion')
                ->onUpdate('restrict')->onDelete('restrict');
            $table->foreign('fk_id_usuario_autoriza')->references('id_usuario')->on('maestro.ges_cat_usuarios')
                ->onUpdate('restrict')->onDelete('restrict');
            $table->foreign('fk_id_estatus')->references('id_estatus')->on('maestro.com_cat_estatus_autorizaciones')
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
        Schema::connection('abisa')->dropIfExists('com_det_autorizaciones');
        Schema::connection('lomedic')->dropIfExists('com_det_autorizaciones');
    }
}
