<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArchivosAdjuntosTickesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('corporativo')
            ->create('sop_det_archivos_adjuntos', function (Blueprint $table) {
            $table->increments('id_archivo_adjunto');
            $table->integer('fk_id_solicitud');
            $table->text('ruta_archivo');
            $table->boolean('activo')->default(true);
            $table->boolean('eliminar')->default(false);
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
            ->dropIfExists('sop_det_archivos_adjuntos');
    }
}
