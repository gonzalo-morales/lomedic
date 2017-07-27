<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUnidadesMedicasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('corporativo')
        ->create('gen_cat_unidades_medicas', function (Blueprint $table) {
            $table->increments('id_unidad_medica');
            $table->string('nombre')->comment('Nombre de la unidad medica')->nullable();
            $table->boolean('activo')->default(false);
            $table->boolean('eliminar')->default(false);

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
        ->dropIfExists('gen_cat_unidades_medicas');
    }
}
