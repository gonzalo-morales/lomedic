<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterComprasSolcitudesDetallesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('abisa')
            ->table('com_det_solicitudes', function (Blueprint $table) {
                $table->renameColumn('fk_id_codigo_barras','fk_id_upc');
                $table->foreign('fk_id_upc')->references('id_upc')->onDelete('restrict')->onUpdate('restrict')
                    ->on('maestro.com_det_solicitudes');
            });
        Schema::connection('lomedic')
            ->table('com_det_solicitudes', function (Blueprint $table) {
                $table->renameColumn('fk_id_codigo_barras','fk_id_upc');
                $table->foreign('fk_id_upc')->references('id_upc')->onDelete('restrict')->onUpdate('restrict')
                    ->on('maestro.inv_cat_upcs');
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
            ->table('inv_cat_codigos_barras', function (Blueprint $table) {
                $table->renameColumn('fk_id_upc','fk_id_codigo_barras');
            });
        Schema::connection('lomedic')
            ->table('inv_cat_codigos_barras', function (Blueprint $table) {
                $table->renameColumn('fk_id_upc','fk_id_codigo_barras');
            });
    }
}
