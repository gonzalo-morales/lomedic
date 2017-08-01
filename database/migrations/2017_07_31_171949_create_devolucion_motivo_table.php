<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDevolucionMotivoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('corporativo')
            ->create('gen_cat_devoluciones_motivos', function (Blueprint $table) {
            $table->increments('id_devolucion_motivo');
            $table->string('devolucion_motivo');
            $table->boolean('solicitante_devolucion')->comment('false: localidad; true: proveedor');
            $table->boolean('activo')->default('true');
            $table->boolean('eliminar')->default('false');
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
            ->dropIfExists('gen_cat_devoluciones_motivos');
    }
}
