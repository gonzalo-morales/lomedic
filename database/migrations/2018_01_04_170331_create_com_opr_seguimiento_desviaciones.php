<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateComOprSeguimientoDesviaciones extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('abisa')->create('com_opr_seguimiento_desviaciones', function (Blueprint $table) {
            $table->increments('id_seguimiento_desviacion');
            $table->integer('fk_id_proveedor')->nullable();
            $table->integer('fk_id_localidad')->nullable();
            $table->string('serie_factura')->nullable();
            $table->string('folio_factura')->nullable();
            $table->timestamp('fecha_captura')->nullable();
            $table->timestamp('fecha_revision')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('abisa')->dropIfExists('com_opr_seguimiento_desviaciones');
    }
}
