<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModuloPerfilTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ges_det_modulo_perfil', function (Blueprint $table) {
            /*Principal fields*/
            $table->integer('fk_id_modulo')->unsigned()->comment('Llave foranea al modulo');
            $table->integer('fk_id_perfil')->unsigned()->comment('Llave foranea al perfil');
            $table->boolean('activo')->default('1');

            /*Foreign keys*/
            $table->foreign('fk_id_modulo')->references('id_modulo')->on('ges_cat_modulos')->
            onDelete('restrict')->onUpdate('restrict');
            $table->foreign('fk_id_perfil')->references('id_perfil')->on('ges_cat_perfiles')->
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
        Schema::dropIfExists('ges_det_modulo_perfil');
    }
}
