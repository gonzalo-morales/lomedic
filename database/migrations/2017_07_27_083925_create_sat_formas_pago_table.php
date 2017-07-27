<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSatFormasPagoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('corporativo')
            ->create('sat_cat_formas_pago', function (Blueprint $table) {
            $table->increments('id_forma_pago');
            $table->string('forma_pago',3);
            $table->string('descripcion');
            $table->boolean('activo')->default(true);
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
            ->dropIfExists('sat_cat_formas_pago');
    }
}
