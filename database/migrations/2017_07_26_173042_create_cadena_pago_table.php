<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCadenaPagoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('corporativo')
        ->create('sat_cat_cadenas_pagos', function (Blueprint $table) {
            $table->increments('id_cadena_pago');
            $table->string('cadena_pago');
            $table->string('descripcion');
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
            ->dropIfExists('sat_cat_cadenas_pagos');
    }
}
