<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOfertasComprasDetalleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('abisa')
            ->create('com_det_ofertas', function (Blueprint $table) {
            $table->increments('id_oferta_detalle');
            $table->smallInteger('fk_id_oferta');//Oferta cabecera
            $table->foreign('fk_id_oferta')->references('id_oferta')
                ->onUpdate('restrict')->onDelete('restrict')->on('com_opr_ofertas');
            $table->smallInteger('fk_id_sku');//SKU
            $table->foreign('fk_id_sku')->references('id_sku')
                ->onUpdate('restrict')->onDelete('restrict')->on('inv_cat_skus');
            $table->smallInteger('fk_id_upc')->nullable();//UPC
            $table->foreign('fk_id_upc')->references('id_upc')
                ->onUpdate('restrict')->onDelete('restrict')->on('maestro.inv_cat_upcs');
            $table->smallInteger('fk_id_solicitud')->nullable();//No. Solicitud
            $table->foreign('fk_id_solicitud')->references('id_solicitud')
                ->onUpdate('restrict')->onDelete('restrict')->on('com_opr_solicitudes');
            $table->smallInteger('cantidad');//Cantidad
            $table->smallInteger('fk_id_unidad_medida');//Unidad medida
            $table->foreign('fk_id_unidad_medida')->references('id_unidad_medida')
                ->onUpdate('restrict')->onDelete('restrict')->on('maestro.gen_cat_unidades_medidas');
            $table->smallInteger('fk_id_cliente')->nullable();//Cliente
            $table->foreign('fk_id_cliente')->references('id_socio_negocio')
                ->onUpdate('restrict')->onDelete('restrict')->on('maestro.gen_cat_socios_negocio');
            $table->smallInteger('fk_id_proyecto')->nullable();//Proyecto
            $table->foreign('fk_id_proyecto')->references('id_proyecto')
                ->onUpdate('restrict')->onDelete('restrict')->on('pry_cat_proyectos');
            $table->decimal('precio_unitario',20,10);//Precio unitario
            $table->smallInteger('fk_id_impuesto');//Impuesto
            $table->foreign('fk_id_impuesto')->references('id_impuesto')
                ->onUpdate('restrict')->onDelete('restrict')->on('maestro.gen_cat_impuestos');
            $table->decimal('descuento_detalle',7,4)->nullable();//Descuentos detalle % decimal(7,4)
            $table->decimal('total_producto',20,10);//Total
        });
        Schema::connection('lomedic')
            ->create('com_det_ofertas', function (Blueprint $table) {
                $table->increments('id_oferta_detalle');
                $table->smallInteger('fk_id_oferta');//Oferta cabecera
                $table->foreign('fk_id_oferta')->references('id_oferta')
                    ->onUpdate('restrict')->onDelete('restrict')->on('com_opr_ofertas');
                $table->smallInteger('fk_id_sku');//SKU
                $table->foreign('fk_id_sku')->references('id_sku')
                    ->onUpdate('restrict')->onDelete('restrict')->on('inv_cat_skus');
                $table->smallInteger('fk_id_upc')->nullable();//UPC
                $table->foreign('fk_id_upc')->references('id_upc')
                    ->onUpdate('restrict')->onDelete('restrict')->on('maestro.inv_cat_upcs');
                $table->smallInteger('fk_id_solicitud')->nullable();//No. Solicitud
                $table->foreign('fk_id_solicitud')->references('id_solicitud')
                    ->onUpdate('restrict')->onDelete('restrict')->on('com_opr_solicitudes');
                $table->smallInteger('cantidad');//Cantidad
                $table->smallInteger('fk_id_unidad_medida');//Unidad medida
                $table->foreign('fk_id_unidad_medida')->references('id_unidad_medida')
                    ->onUpdate('restrict')->onDelete('restrict')->on('maestro.gen_cat_unidades_medidas');
                $table->smallInteger('fk_id_cliente')->nullable();//Cliente
                $table->foreign('fk_id_cliente')->references('id_socio_negocio')
                    ->onUpdate('restrict')->onDelete('restrict')->on('maestro.gen_cat_socios_negocio');
                $table->smallInteger('fk_id_proyecto')->nullable();//Proyecto
                $table->foreign('fk_id_proyecto')->references('id_proyecto')
                    ->onUpdate('restrict')->onDelete('restrict')->on('pry_cat_proyectos');
                $table->decimal('precio_unitario',20,10);//Precio unitario
                $table->smallInteger('fk_id_impuesto');//Impuesto
                $table->foreign('fk_id_impuesto')->references('id_impuesto')
                    ->onUpdate('restrict')->onDelete('restrict')->on('maestro.gen_cat_impuestos');
                $table->decimal('descuento_detalle',7,4)->nullable();//Descuentos detalle % decimal(7,4)
                $table->decimal('total_producto',20,10);//Total
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('abisa')
            ->dropIfExists('com_det_ofertas');
        Schema::connection('lomedic')
            ->dropIfExists('com_det_ofertas');
    }
}
