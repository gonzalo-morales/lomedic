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
            ->create('ges_det_parents', function (Blueprint $table) {
            /*Principal fields*/
            $table->integer('fk_id_modulo')->unsigned()->comment('Llave foranea al modulo');
            $table->integer('fk_id_parent')->unsigned()->comment('Llave foranea al modulo');
            $table->boolean('activo')->default('1');

            /*Foreign keys*/
            $table->foreign('fk_id_modulo')->references('id_modulo')->on('ges_cat_modulos')->
            onDelete('restrict')->onUpdate('restrict');
            $table->foreign('fk_id_parent')->references('id_modulo')->on('ges_cat_modulos')->
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
            ->dropIfExists('parents');
    }
}
