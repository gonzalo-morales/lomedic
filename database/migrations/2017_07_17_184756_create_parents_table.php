<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('corporativo')
            ->create('ges_det_modulos', function (Blueprint $table) {
            /*Principal fields*/
            $table->integer('fk_id_modulo')->unsigned()->comment('Llave foranea al modulo');
            $table->integer('fk_id_modulo_padre')->unsigned()->comment('Llave foranea al modulo');
            $table->integer('fk_id_empresa')->unsigned()->comment('Llave foranea a la empresa');

            /*Foreign keys*/
            $table->foreign('fk_id_modulo')->references('id_modulo')->on('ges_cat_modulos')->
            onDelete('restrict')->onUpdate('restrict');
            $table->foreign('fk_id_modulo_padre')->references('id_modulo')->on('ges_cat_modulos')->
            onDelete('restrict')->onUpdate('restrict');
            $table->foreign('fk_id_empresa')->references('id_empresa')->on('gen_cat_empresas')->
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
        Schema::connection('corporativo')->dropIfExists('ges_det_modulos');
    }
}