<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSatMonedasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('corporativo')
            ->create('sat_cat_monedas', function (Blueprint $table) {
            $table->increments('id_moneda');
            $table->string('moneda');
            $table->string('descripcion');
            $table->integer('total_decimales');
            $table->decimal('porcentaje_variacion',5,3);
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
            ->dropIfExists('sat_cat_monedas');
    }
}
