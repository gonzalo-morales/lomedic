<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableSustanciasActivas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('corporativo')
        ->create('gen_cat_sustancias_activas', function (Blueprint $table) {
            $table->increments('id_sustancia_activa');
            $table->string('sustancia_activa');
            $table->boolean('opcion_gramaje');
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
        Schema::dropIfExists('gen_cat_sustancias_activas');
    }
}
