<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSngCatCuentasBancarias extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('corporativo')->create('sng_cat_cuentas_bancarias', function (Blueprint $table) {
                    $table->increments('id_cuenta');
                    $table->integer('fk_id_banco')->unique();
                    $table->integer('fk_id_socio_negocio')->unique();
                    $table->string('no_cuenta')->unique();
                    $table->boolean('activo')->default(true);
                    $table->boolean('eliminar')->default(false);

                    $table->foreign('fk_id_banco')->references('id_banco')->on('gen_cat_bancos')
                        ->onUpdate('restrict')->onDelete('restrict');
                    $table->foreign('fk_id_socio_negocio')->references('id_socio_negocio')->on('gen_cat_socios_negocio')
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
                ->dropIfExists('sng_cat_cuentas_bancarias');
    }
}
