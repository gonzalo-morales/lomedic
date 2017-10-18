<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterOrdenDetalleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('abisa')
            ->table('com_det_ordenes', function (Blueprint $table) {
            $table->smallInteger('fk_id_solicitud')->nullable();
            $table->foreign('fk_id_solicitud')->onUpdate('restrict')->onDelete('restrict')
                ->references('id_solicitud')->on('com_opr_solicitudes');
        });
        Schema::connection('lomedic')
            ->table('com_det_ordenes', function (Blueprint $table) {
                $table->smallInteger('fk_id_solicitud')->nullable();
                $table->foreign('fk_id_solicitud')->onUpdate('restrict')->onDelete('restrict')
                    ->references('id_solicitud')->on('com_opr_solicitudes');
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
            ->table('com_det_ordenes', function (Blueprint $table) {
            $table->dropForeign('com_det_ordenes_fk_id_solicitud_foreign');
            $table->dropColumn('fk_id_solicitud');
        });

        Schema::connection('lomedic')
            ->table('com_det_ordenes', function (Blueprint $table) {
                $table->dropForeign('com_det_ordenes_fk_id_solicitud_foreign');
                $table->dropColumn('fk_id_solicitud');
        });
    }
}
