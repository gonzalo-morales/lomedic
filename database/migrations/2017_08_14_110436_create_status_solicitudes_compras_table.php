<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStatusSolicitudesComprasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('corporativo')
            ->create('com_cat_estatus_solicitudes_compras', function (Blueprint $table) {
            $table->increments('id_estatus');
            $table->string('estatus');
            $table->boolean('activo')->default('t');
            $table->boolean('eliminar')->default('f');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('corporativo')->dropIfExists('com_cat_estatus_solicitudes_compras');
    }
}
