<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterGenCatSociosNegocio extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('corporativo')
            ->table('gen_cat_socios_negocio', function (Blueprint $table) {
                $table->string('ejecutivo_venta',50)->nullable();
                $table->integer('fk_id_ramo')->nullable();
                $table->integer('fk_id_pais_origen')->unsigned()->nullable();
                $table->integer('fk_id_moneda')->unsigned()->nullable();
                $table->foreign('fk_id_ramo')->references('id_ramo')
                    ->onUpdate('restrict')->onDelete('restrict')
                    ->on('sng_cat_ramos_socio');
                $table->foreign('fk_id_pais_origen')->references('id_pais')
                    ->onUpdate('restrict')->onDelete('restrict')
                    ->on('gen_cat_paises');
                $table->foreign('fk_id_moneda')->references('id_moneda')
                    ->onUpdate('restrict')->onDelete('restrict')
                    ->on('sat_cat_monedas');
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
            ->table('gen_cat_socios_negocio', function (Blueprint $table) {
                $table->dropColumn('ejecutivo_venta');
                $table->dropForeign("gen_cat_socios_negocio_fk_id_ramo_foreign");
                $table->dropForeign("gen_cat_socios_negocio_fk_id_pais_origen_foreign");
                $table->dropForeign("gen_cat_socios_negocio_fk_id_moneda_foreign");
                $table->dropColumn('fk_id_ramo');
                $table->dropColumn('fk_id_pais_origen');
                $table->dropColumn('fk_id_moneda');
        });
    }
}
