<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdenCompraDetalle extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('abisa')
            ->create('com_opr_ordenes', function (Blueprint $table) {
                $table->increments('id_solicitud');
                $table->integer('fk_id_socio_negocio');
                $table->foreign('fk_id_socio_negocio')->references('id_socio_negocio')->onDelete('restrict')->onUpdate('restrict')
                    ->on(config('database.connections.corporativo.schema').'.gen_cat_socios_negocio');
                $table->integer('fk_id_sucursal');
                $table->foreign('fk_id_sucursal')->references('id_sucursal')->onDelete('restrict')->onUpdate('restrict')
                    ->on(config('database.connections.corporativo.schema').'.ges_cat_sucursales');
                $table->integer('fk_id_condicion_pago')->nullable();
//                $table->foreign('fk_id_departamento')->references('id_departamento')->onDelete('restrict')->onUpdate('restrict')
//                    ->on(config('database.connections.corporativo.schema').'.rh_cat_departamentos');
                $table->date('fecha_creacion');
                $table->date('fecha_necesidad');
                $table->date('fecha_cancelacion')->nullable();
                $table->string('motivo_cancelacion')->nullable();
                $table->integer('fk_id_estatus_orden');
                $table->foreign('fk_id_estatus_orden')->references('id_estatus')->onDelete('restrict')->onUpdate('restrict')
                    ->on(config('database.connections.corporativo.schema').'.com_cat_estatus_solicitudes_compras');
            });
        Schema::connection('lomedic')
            ->create('com_opr_ordenes', function (Blueprint $table) {
                $table->increments('id_solicitud');
                $table->integer('fk_id_socio_negocio');
                $table->foreign('fk_id_socio_negocio')->references('id_socio_negocio')->onDelete('restrict')->onUpdate('restrict')
                    ->on(config('database.connections.corporativo.schema').'.gen_cat_socios_negocio');
                $table->integer('fk_id_sucursal');
                $table->foreign('fk_id_sucursal')->references('id_sucursal')->onDelete('restrict')->onUpdate('restrict')
                    ->on(config('database.connections.corporativo.schema').'.ges_cat_sucursales');
                $table->integer('fk_id_condicion_pago')->nullable();
//                $table->foreign('fk_id_departamento')->references('id_departamento')->onDelete('restrict')->onUpdate('restrict')
//                    ->on(config('database.connections.corporativo.schema').'.rh_cat_departamentos');
                $table->date('fecha_creacion');
                $table->date('fecha_necesidad');
                $table->date('fecha_cancelacion')->nullable();
                $table->string('motivo_cancelacion')->nullable();
                $table->integer('fk_id_estatus_orden');
                $table->foreign('fk_id_estatus_orden')->references('id_estatus')->onDelete('restrict')->onUpdate('restrict')
                    ->on(config('database.connections.corporativo.schema').'.com_cat_estatus_solicitudes_compras');
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('abisa')->dropIfExists('com_opr_ordenes');
        Schema::connection('lomedic')->dropIfExists('com_opr_ordenes');
    }
}
