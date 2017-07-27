<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAplicacionesMedicacmentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('corporativo')
            ->create('gen_cat_aplicaciones_medicamentos', function (Blueprint $table) {
            $table->increments('id_aplicacion');
            $table->string('aplicacion');
            $table->boolean('activo')->default('true');
            $table->boolean('eliminar')->default('false');
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
            ->dropIfExists('gen_cat_aplicaciones_medicamentos');
    }
}
