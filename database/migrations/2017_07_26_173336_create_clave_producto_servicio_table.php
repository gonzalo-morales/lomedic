<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClaveProductoServicioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('corporativo')
            ->create('sat_cat_claves_producto_servicio', function (Blueprint $table) {
            $table->increments('id_clave_producto_servicio');
            $table->string('clave_producto_servicio');
            $table->string('descripcion');
            $table->string('vigencia')->nullable();
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
            ->dropIfExists('sat_cat_clave_producto_servicio');
    }
}
