<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCondicionesPagoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('corporativo')
            ->create('fnz_cat_condiciones_pago', function (Blueprint $table) {
            $table->smallIncrements('id_condicion_pago');
            $table->string('condicion_pago','50');
            $table->boolean('activo')->default('1');
            $table->boolean('eliminar')->default('0');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('maestro')
            ->dropIfExists('fnz_cat_condiciones_pago');
    }
}
