<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSeguimientoSolicitudesTickesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('corporativo')
            ->create('sop_det_seguimiento_solicitudes', function (Blueprint $table) {
            $table->increments('id_seguimiento');
            $table->string('asunto');
            $table->integer('fk_id_solicitud');
            $table->text('comentario');
            $table->integer('fk_id_empleado_comentario');
            $table->dateTime('fecha_hora');
            $table->boolean('activo')->default(true);
            $table->boolean('eliminar')->default(false);

            $table->foreign('fk_id_solicitud')->references('id_solicitud')->on('sop_opr_solicitudes')
                ->onUpdate('restrict')->onDelete('restrict');
            $table->foreign('fk_id_empleado_comentario')->references('id_empleado')->on('rh_cat_empleados')
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
            ->dropIfExists('sop_det_seguimiento_solicitudes');
    }
}
