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
            $table->integer('fk_id_modulo_hijo')->unsigned()->comment('Llave foranea al modulo');
            $table->integer('fk_id_modulpo_padre')->unsigned()->comment('Llave foranea al modulo');
            $table->boolean('activo')->default('1');

            /*Foreign keys*/
            $table->foreign('fk_id_modulo_hijo')->references('id_modulo')->on('ges_cat_modulos')->
            onDelete('restrict')->onUpdate('restrict');
            $table->foreign('fk_id_modulpo_padre')->references('id_modulo')->on('ges_cat_modulos')->
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
            ->dropIfExists('ges_det_modulos');
    }
}
