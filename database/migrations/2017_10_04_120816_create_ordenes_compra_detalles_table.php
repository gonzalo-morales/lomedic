<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdenesCompraDetallesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('abisa')
            ->create('com_det_ordenes', function (Blueprint $table) {
            $table->increments('id_orden_detalle');
            $table->integer('fk_id_orden');
                $table->foreign('fk_id_orden')->references('id_orden')->on('com_opr_ordenes')
                ->onUpdate('restrict')->onDelete('restrict');
            $table->integer('fk_id_sku');
                $table->foreign('fk_id_sku')->references('id_sku')->onUpdate('restrict')->onDelete('restrict')
                ->on('maestro.inv_cat_skus');
            $table->integer('fk_id_upc')->nullable();
                $table->foreign('fk_id_upc')->references('id_upc')->onUpdate('restrict')->onDelete('restrict')
                    ->on('maestro.inv_cat_upcs');
            $table->integer('fk_id_cliente')->nullable();
                $table->foreign('fk_id_cliente')->references('id_socio_negocio')->onUpdate('restrict')->onDelete('restrict')
                    ->on('maestro.gen_cat_socios_negocio');
            $table->integer('fk_id_proyecto')->nullable();
                $table->foreign('fk_id_proyecto')->references('id_proyecto')->onUpdate('restrict')->onDelete('restrict')
                    ->on('pry_cat_proyectos');
            $table->decimal('precio_unitario',20,10);
            $table->smallInteger('cantidad');
            $table->date('fecha_necesario')->nullable();
            $table->decimal('total','20','10');
            $table->integer('fk_id_impuesto')->default('1');
                $table->foreign('fk_id_impuesto')->references('id_impuesto')->onUpdate('restrict')->onDelete('restrict')
                    ->on('maestro.gen_cat_impuestos');
            $table->boolean('cerrado')->default('f');
            });
        Schema::connection('lomedic')
            ->create('com_det_ordenes', function (Blueprint $table) {
                $table->increments('id_orden_detalle');
                $table->integer('fk_id_orden');
                $table->foreign('fk_id_orden')->references('id_orden')->on('com_opr_ordenes')
                    ->onUpdate('restrict')->onDelete('restrict');
                $table->integer('fk_id_sku');
                $table->foreign('fk_id_sku')->references('id_sku')->onUpdate('restrict')->onDelete('restrict')
                    ->on('maestro.inv_cat_skus');
                $table->integer('fk_id_upc')->nullable();
                $table->foreign('fk_id_upc')->references('id_upc')->onUpdate('restrict')->onDelete('restrict')
                    ->on('maestro.inv_cat_upcs');
                $table->integer('fk_id_cliente')->nullable();
                $table->foreign('fk_id_cliente')->references('id_socio_negocio')->onUpdate('restrict')->onDelete('restrict')
                    ->on('maestro.gen_cat_socios_negocio');
                $table->integer('fk_id_proyecto')->nullable();
                $table->foreign('fk_id_proyecto')->references('id_proyecto')->onUpdate('restrict')->onDelete('restrict')
                    ->on('pry_cat_proyectos');
                $table->decimal('precio_unitario',20,10);
                $table->smallInteger('cantidad');
                $table->date('fecha_necesario')->nullable();
                $table->decimal('total','20','10');
                $table->integer('fk_id_impuesto')->default('1');
                $table->foreign('fk_id_impuesto')->references('id_impuesto')->onUpdate('restrict')->onDelete('restrict')
                    ->on('maestro.gen_cat_impuestos');
                $table->boolean('cerrado')->default('f');
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ordenes_compra_detalles');
    }
}
