<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSolicitudCompraTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('abisa')
            ->create('com_opr_solicitudes', function (Blueprint $table) {
            $table->increments('id_solicitud');
            $table->integer('fk_id_solicitante');
                $table->foreign('fk_id_solicitante')->references('id_empleado')->onDelete('restrict')->onUpdate('restrict')
                    ->on(config('database.connections.corporativo.schema').'.rh_cat_empleados');
            $table->integer('fk_id_sucursal');
                $table->foreign('fk_id_sucursal')->references('id_sucursal')->onDelete('restrict')->onUpdate('restrict')
                    ->on(config('database.connections.corporativo.schema').'.ges_cat_sucursales');
            $table->integer('fk_id_departamento')->nullable();
                $table->foreign('fk_id_departamento')->references('id_departamento')->onDelete('restrict')->onUpdate('restrict')
                    ->on(config('database.connections.corporativo.schema').'.rh_cat_departamentos');
            $table->date('fecha_creacion');
            $table->date('fecha_necesidad');
            $table->date('fecha_cancelacion')->nullable();
            $table->string('motivo_cancelacion')->nullable();
            $table->integer('fk_id_estatus_solicitud');
                $table->foreign('fk_id_estatus_solicitud')->references('id_estatus')->onDelete('restrict')->onUpdate('restrict')
                    ->on(config('database.connections.corporativo.schema').'.com_cat_estatus_solicitudes_compras');
        });
        Schema::connection('lomedic')
            ->create('com_opr_solicitudes', function (Blueprint $table) {
                $table->increments('id_solicitud');
                $table->integer('fk_id_solicitante');
                $table->foreign('fk_id_solicitante')->references('id_empleado')->onDelete('restrict')->onUpdate('restrict')
                    ->on(config('database.connections.corporativo.schema').'.rh_cat_empleados');
                $table->integer('fk_id_sucursal');
                $table->foreign('fk_id_sucursal')->references('id_sucursal')->onDelete('restrict')->onUpdate('restrict')
                    ->on(config('database.connections.corporativo.schema').'.ges_cat_sucursales');
                $table->integer('fk_id_departamento')->nullable();
                $table->foreign('fk_id_departamento')->references('id_departamento')->onDelete('restrict')->onUpdate('restrict')
                    ->on(config('database.connections.corporativo.schema').'.rh_cat_departamentos');
                $table->date('fecha_creacion');
                $table->date('fecha_necesidad');
                $table->date('fecha_cancelacion')->nullable();
                $table->string('motivo_cancelacion')->nullable();
                $table->integer('fk_id_estatus_solicitud');
                $table->foreign('fk_id_estatus_solicitud')->references('id_estatus')->onDelete('restrict')->onUpdate('restrict')
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
        Schema::connection('abisa')->dropIfExists('com_opr_solicitudes');
        Schema::connection('lomedic')->dropIfExists('com_opr_solicitudes');
    }
}
