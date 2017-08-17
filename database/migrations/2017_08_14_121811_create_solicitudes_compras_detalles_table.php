<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSolicitudesComprasDetallesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('abisa')
            ->create('com_det_solicitudes', function (Blueprint $table) {
            $table->increments('id_solicitud_detalle');
            $table->integer('fk_id_solicitud');
                $table->foreign('fk_id_solicitud')->references('id_solicitud')->onDelete('restrict')->onUpdate('restrict')
                    ->on('com_opr_solicitudes');
            $table->integer('fk_id_sku');
                $table->foreign('fk_id_sku')->references('id_sku')->onDelete('restrict')->onUpdate('restrict')
                    ->on(config('database.connections.corporativo.schema').'.com_cat_skus');
            $table->integer('fk_id_codigo_barras');
                $table->foreign('fk_id_codigo_barras')->references('id_codigo_barras')
                    ->onDelete('restrict')->onUpdate('restrict')
                    ->on(config('database.connections.corporativo.schema').'.com_cat_codigos_barras');
            $table->integer('fk_id_proveedor');
                $table->foreign('fk_id_proveedor')->references('id_proveedor')
                    ->onDelete('restrict')->onUpdate('restrict')
                    ->on(config('database.connections.corporativo.schema').'.gen_cat_proveedores');
            $table->integer('cantidad');
            $table->integer('fk_id_unidad_medida');
                $table->foreign('fk_id_unidad_medida')->references('id_unidad_medida')
                    ->onDelete('restrict')->onUpdate('restrict')
                    ->on(config('database.connections.corporativo.schema').'.gen_cat_unidades_medidas');
            $table->integer('fk_id_impuesto');
                $table->foreign('fk_id_impuesto')->references('id_impuesto')
                    ->onDelete('restrict')->onUpdate('restrict')
                    ->on(config('database.connections.corporativo.schema').'.gen_cat_impuestos');
            $table->decimal('precio_unitario',14,4);
            $table->decimal('total',20,10);
            $table->integer('fk_id_proyecto')->nullable();
                $table->foreign('fk_id_proyecto')->references('id_proyecto')->onDelete('restrict')->onUpdate('restrict')
                    ->on('pry_cat_proyectos');
            $table->date('fecha_necesario')->nullable();
            $table->boolean('cerrado')->default('f');
        });
        Schema::connection('lomedic')
            ->create('com_det_solicitudes', function (Blueprint $table) {
                $table->increments('id_solicitud_detalle');
                $table->integer('fk_id_solicitud');
                $table->foreign('fk_id_solicitud')->references('id_solicitud')->onDelete('restrict')->onUpdate('restrict')
                    ->on('com_opr_solicitudes');
                $table->integer('fk_id_sku');
                $table->foreign('fk_id_sku')->references('id_sku')->onDelete('restrict')->onUpdate('restrict')
                    ->on(config('database.connections.corporativo.schema').'.com_cat_skus');
                $table->integer('fk_id_codigo_barras');
                $table->foreign('fk_id_codigo_barras')->references('id_codigo_barras')
                    ->onDelete('restrict')->onUpdate('restrict')
                    ->on(config('database.connections.corporativo.schema').'.com_cat_codigos_barras');
                $table->integer('fk_id_proveedor');
                $table->foreign('fk_id_proveedor')->references('id_proveedor')
                    ->onDelete('restrict')->onUpdate('restrict')
                    ->on(config('database.connections.corporativo.schema').'.gen_cat_proveedores');
                $table->integer('cantidad');
                $table->integer('fk_id_unidad_medida');
                $table->foreign('fk_id_unidad_medida')->references('id_unidad_medida')
                    ->onDelete('restrict')->onUpdate('restrict')
                    ->on(config('database.connections.corporativo.schema').'.gen_cat_unidades_medidas');
                $table->integer('fk_id_impuesto');
                $table->foreign('fk_id_impuesto')->references('id_impuesto')
                    ->onDelete('restrict')->onUpdate('restrict')
                    ->on(config('database.connections.corporativo.schema').'.gen_cat_impuestos');
                $table->decimal('precio_unitario',14,4);
                $table->decimal('total',20,10);
                $table->integer('fk_id_proyecto')->nullable();
                $table->foreign('fk_id_proyecto')->references('id_proyecto')->onDelete('restrict')->onUpdate('restrict')
                    ->on('pry_cat_proyectos');
                $table->date('fecha_necesario')->nullable();
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
        Schema::connection('lomedic')->dropIfExists('com_det_solicitudes');
        Schema::connection('abisa')->dropIfExists('com_det_solicitudes');
    }
}
