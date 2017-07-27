<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterClaveProductoServicioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('corporativo')
            ->rename('sat_cat_clave_producto_servicio', 'sat_cat_claves_productos_servicios');

        Schema::connection('corporativo')
            ->table('sat_cat_clave_producto_servicio', function (Blueprint $table) {
            //
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
            ->table('sat_cat_clave_producto_servicio', function (Blueprint $table) {
            //
        });
    }
}
