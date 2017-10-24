<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOfertasComprasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('abisa')
            ->create('com_opr_ofertas', function (Blueprint $table) {
            $table->increments('id_oferta');
            $table->smallInteger('fk_id_empresa');
            $table->foreign('fk_id_empresa')->references('id_empresa')
                ->onUpdate('restrict')->onDelete('restrict')->on('maestro.gen_cat_empresas');
            $table->smallInteger('fk_id_sucursal');
            $table->foreign('fk_id_sucursal')->references('id_sucursal')
                ->onUpdate('restrict')->onDelete('restrict')->on('maestro.ges_cat_sucursales');
            $table->smallInteger('fk_id_proveedor');
            $table->foreign('fk_id_proveedor')->references('id_socio_negocio')
                ->onUpdate('restrict')->onDelete('restrict')->on('maestro.gen_cat_socios_negocio');
            $table->date('vigencia');//Vigencia (date)
            $table->date('fecha_creacion')->default(DB::raw('now()'));//Fecha creacion (date)
            $table->string('condiciones_oferta')->nullable();//Condiciones oferta (texto)
            $table->smallInteger('tiempo_entrega');//Tiempo entrega
            $table->smallInteger('fk_id_moneda');//Moneda
            $table->foreign('fk_id_moneda')->references('id_moneda')
                ->onUpdate('restrict')->onDelete('restrict')->on('maestro.sat_cat_monedas');
            $table->decimal('descuento_oferta',7,4);//Descuentos oferta % decimal(7,4)
            $table->smallInteger('fk_id_estatus_oferta');
            $table->foreign('fk_id_estatus_oferta')->references('id_estatus')
                ->onUpdate('restrict')->onDelete('restrict')->on('maestro.com_cat_estatus_solicitudes_compras');
        });
        Schema::connection('lomedic')
            ->create('com_opr_ofertas', function (Blueprint $table) {
                $table->increments('id_oferta');
                $table->smallInteger('fk_id_empresa');
                $table->foreign('fk_id_empresa')->references('id_empresa')
                    ->onUpdate('restrict')->onDelete('restrict')->on('maestro.gen_cat_empresas');
                $table->smallInteger('fk_id_sucursal');
                $table->foreign('fk_id_sucursal')->references('id_sucursal')
                    ->onUpdate('restrict')->onDelete('restrict')->on('maestro.ges_cat_sucursales');
                $table->smallInteger('fk_id_proveedor');
                $table->foreign('fk_id_proveedor')->references('id_socio_negocio')
                    ->onUpdate('restrict')->onDelete('restrict')->on('maestro.gen_cat_socios_negocio');
                $table->date('vigencia');//Vigencia (date)
                $table->date('fecha_creacion')->default(DB::raw('now()'));//Fecha creacion (date)
                $table->string('condiciones_oferta')->nullable();//Condiciones oferta (texto)
                $table->smallInteger('tiempo_entrega');//Tiempo entrega
                $table->smallInteger('fk_id_moneda');//Moneda
                $table->foreign('fk_id_moneda')->references('id_moneda')
                    ->onUpdate('restrict')->onDelete('restrict')->on('maestro.sat_cat_monedas');
                $table->decimal('descuento_oferta',7,4);//Descuentos oferta % decimal(7,4)
                $table->smallInteger('fk_id_estatus_oferta');
                $table->foreign('fk_id_estatus_oferta')->references('id_estatus')
                    ->onUpdate('restrict')->onDelete('restrict')->on('maestro.com_cat_estatus_solicitudes_compras');
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
            ->dropIfExists('com_opr_ofertas');
        Schema::connection('lomedic')
            ->dropIfExists('com_opr_ofertas');
    }
}
