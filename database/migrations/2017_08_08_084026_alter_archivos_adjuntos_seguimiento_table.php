<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterArchivosAdjuntosSeguimientoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('corporativo')
            ->table('sop_det_archivos_adjuntos', function (Blueprint $table) {
                $table->integer('fk_id_mensaje')->nullable();
                $table->foreign('fk_id_mensaje')->references('id_seguimiento')->onUpdate('restrict')->onDelete('restrict')
                    ->on('sop_det_seguimiento_solicitudes');
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
            ->table('sop_det_archivos_adjuntos', function (Blueprint $table) {
                $table->dropForeign('fk_id_mensaje');
                $table->dropColumn('fk_id_mensaje');
        });
    }
}
