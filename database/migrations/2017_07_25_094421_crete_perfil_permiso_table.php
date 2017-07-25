<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CretePerfilPermisoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('corporativo')
            ->create('perfil_permiso', function (Blueprint $table) {
            $table->integer('fk_id_perfil');
            $table->integer('fk_id_permiso');

            $table->foreign('fk_id_perfil')->references('id_perfil')->on('ges_cat_perfiles')->
            onDelete('restrict')->onUpdate('restrict');
            $table->foreign('fk_id_permiso')->references('id_permiso')->on('ges_cat_permisos')->
            onDelete('restrict')->onUpdate('restrict');
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
            ->dropIfExists('perfil_permiso');
    }
}
