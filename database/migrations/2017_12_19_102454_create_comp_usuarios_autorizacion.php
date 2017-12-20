<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompUsuariosAutorizacion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::connection('abisa')->create('com_det_usuarios_autorizados', function (Blueprint $table) {
            $table->integer('fk_id_condicion');
            $table->integer('fk_id_usuario');
            $table->boolean('activo')->default(true);
            $table->boolean('eliminar')->default(false);

            $table->foreign('fk_id_condicion')->references('id_condicion')->on('com_cat_condiciones_autorizacion')
                ->onUpdate('restrict')->onDelete('restrict');
            $table->foreign('fk_id_usuario')->references('id_usuario')->on('maestro.ges_cat_usuarios')
                ->onUpdate('restrict')->onDelete('restrict');
        });
        Schema::connection('lomedic')->create('com_det_usuarios_autorizados', function (Blueprint $table) {
            $table->integer('fk_id_condicion');
            $table->integer('fk_id_usuario');
            $table->boolean('activo')->default(true);
            $table->boolean('eliminar')->default(false);

            $table->foreign('fk_id_condicion')->references('id_condicion')->on('com_cat_condiciones_autorizacion')
                ->onUpdate('restrict')->onDelete('restrict');
            $table->foreign('fk_id_usuario')->references('id_usuario')->on('maestro.ges_cat_usuarios')
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
        Schema::connection('abisa')->dropIfExists('com_det_usuarios_autorizados');
        Schema::connection('lomedic')->dropIfExists('com_det_usuarios_autorizados');
    }
}
