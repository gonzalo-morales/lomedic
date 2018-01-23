<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateComDetSeguimientoDesviaciones extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('abisa')->create('com_det_seguimiento_desviacion', function (Blueprint $table) {
            $table->increments('id_detalle_seguimiento_desviacion');
            $table->integer('fk_id_seguimiento_desviacion');
            $table->integer('fk_id_orden_compra')->nullable();
            $table->integer('fk_id_detalle_orden_compra')->nullable();
            $table->integer('fk_id_entrada')->nullable();
            $table->integer('fk_id_detalle_entrada')->nullable();
            $table->smallInteger('cantidad_orden_compra')->nullable();
            $table->smallInteger('cantidad_entrada')->nullable();
            $table->smallInteger('cantidad_desviacion')->nullable();
            $table->integer('fk_id_factura_proveedor')->nullable();
            $table->integer('fk_id_detalle_factura_proveedor')->nullable();
            $table->decimal('precio_orden_compra')->nullable();
            $table->decimal('precio_factura')->nullable();
            $table->decimal('precio_desviacion')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('abisa')->dropIfExists('com_det_seguimiento_desviacion');
    }
}
