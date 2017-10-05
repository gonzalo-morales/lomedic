<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterOrdenesCompraTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('abisa')
            ->table('com_opr_ordenes', function (Blueprint $table) {
                $table->smallInteger('fk_id_tipo_entrega');
                $table->foreign('fk_id_tipo_entrega')->references('id_tipo_entrega')
                    ->onDelete('restrict')->onUpdate('restrict')
                    ->on('maestro.sng_cat_tipos_entrega');
                $table->smallInteger('fk_id_empresa');
                $table->foreign('fk_id_empresa')->references('id_empresa')
                    ->onDelete('restrict')->onUpdate('restrict')
                    ->on('maestro.gen_cat_empresas');
                $table->foreign('fk_id_condicion_pago')->references('id_condicion_pago')
                    ->onDelete('restrict')->onUpdate('restrict')
                    ->on('maestro.fnz_cat_condiciones_pago');
                $table->smallInteger('tiempo_entrega');
                $table->renameColumn('fecha_necesidad','fecha_estimada_entrega')->nullable();
                $table->renameColumn('id_solicitud','id_orden');
            });
        Schema::connection('lomedic')
            ->table('com_opr_ordenes', function (Blueprint $table) {
                $table->smallInteger('fk_id_tipo_entrega');
                $table->foreign('fk_id_tipo_entrega')->references('id_tipo_entrega')
                    ->onDelete('restrict')->onUpdate('restrict')
                    ->on('maestro.sng_cat_tipos_entrega');
                $table->smallInteger('fk_id_empresa');
                $table->foreign('fk_id_empresa')->references('id_empresa')
                    ->onDelete('restrict')->onUpdate('restrict')
                    ->on('maestro.gen_cat_empresas');
                $table->foreign('fk_id_condicion_pago')->references('id_condicion_pago')
                    ->onDelete('restrict')->onUpdate('restrict')
                    ->on('maestro.fnz_cat_condiciones_pago');
                $table->smallInteger('tiempo_entrega');
                $table->renameColumn('fecha_necesidad','fecha_estimada_entrega')->nullable();
                $table->renameColumn('id_solicitud','id_orden');
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
            ->table('com_opr_ordenes', function (Blueprint $table) {
                $table->dropForeign('fk_id_tipo_entrega');
                $table->dropColumn('tiempo_entrega','importe','fk_id_tipo_entrega');
            });
        Schema::connection('lomedic')
            ->table('com_opr_ordenes', function (Blueprint $table) {
                $table->dropForeign('fk_id_tipo_entrega');
                $table->dropColumn('tiempo_entrega','importe','fk_id_tipo_entrega');
            });
    }
}
