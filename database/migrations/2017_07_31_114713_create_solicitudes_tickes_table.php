<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSolicitudesTickesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('corporativo')
            ->create('sop_opr_solicitudes', function (Blueprint $table) {
            $table->increments('id_solicitud');
            $table->text('descripcion');
            $table->string('asunto');
            $table->integer('fk_id_empleado_solicitud');
            $table->integer('fk_id_empresa_empleado_solicitud')->nullable();
            $table->integer('fk_id_sucursal')->nullable();
            $table->integer('fk_id_estatus_ticket');
            $table->integer('fk_id_categoria');
            $table->integer('fk_id_subcategoria')->nullable();
            $table->integer('fk_id_accion')->nullable();
            $table->integer('fk_id_prioridad');
            $table->integer('fk_id_modo_contacto');
            $table->integer('fk_id_empleado_tecnico')->nullable();
            $table->integer('fk_id_impacto')->nullable();
            $table->integer('fk_id_urgencia')->nullable();
            $table->text('resolucion')->nullable();
            $table->dateTime('fecha_hora_resolucion')->nullable();
            $table->boolean('activo')->default(true);
            $table->boolean('eliminar')->default(false);

            $table->foreign('fk_id_empleado_solicitud')->references('id_empleado')->on('rh_cat_empleados')
                ->onUpdate('restrict')->onDelete('restrict');
            $table->foreign('fk_id_empresa_empleado_solicitud')->references('id_empresa')->on('gen_cat_empresas')
                ->onUpdate('restrict')->onDelete('restrict');
            $table->foreign('fk_id_sucursal')->references('id_sucursal')->on('ges_cat_sucursales')
                ->onUpdate('restrict')->onDelete('restrict');
            $table->foreign('fk_id_estatus_ticket')->references('id_estatus_ticket')->on('sop_cat_estatus_tickets')
                ->onUpdate('restrict')->onDelete('restrict');
            $table->foreign('fk_id_categoria')->references('id_categoria')->on('sop_cat_categorias')
                ->onUpdate('restrict')->onDelete('restrict');
            $table->foreign('fk_id_subcategoria')->references('id_subcategoria')->on('sop_cat_subcategorias')
                ->onUpdate('restrict')->onDelete('restrict');
            $table->foreign('fk_id_accion')->references('id_accion')->on('sop_cat_acciones')
                ->onUpdate('restrict')->onDelete('restrict');
            $table->foreign('fk_id_prioridad')->references('id_prioridad')->on('sop_cat_prioridades')
                ->onUpdate('restrict')->onDelete('restrict');
            $table->foreign('fk_id_modo_contacto')->references('id_modo_contacto')->on('sop_cat_modos_contacto')
                ->onUpdate('restrict')->onDelete('restrict');
            $table->foreign('fk_id_empleado_tecnico')->references('id_empleado')->on('rh_cat_empleados')
                ->onUpdate('restrict')->onDelete('restrict');
            $table->foreign('fk_id_impacto')->references('id_impacto')->on('sop_cat_impactos')
                ->onUpdate('restrict')->onDelete('restrict');
            $table->foreign('fk_id_urgencia')->references('id_urgencia')->on('sop_cat_urgencias')
                ->onUpdate('restrict')->onDelete('restrict');
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
            ->dropIfExists('sop_opr_solicitudes');
    }
}
