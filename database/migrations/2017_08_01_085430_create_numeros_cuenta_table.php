<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNumerosCuentaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('corporativo')
            ->create('gen_cat_numeros_cuenta', function (Blueprint $table) {
            $table->increments('id_numero_cuenta');
            $table->string('numero_cuenta');
            $table->integer('fk_id_banco');
            $table->integer('fk_id_sat_moneda');
            $table->integer('fk_id_empresa');
            $table->boolean('activo')->default('true');
            $table->boolean('eliminar')->default('false');

            $table->foreign('fk_id_banco')->references('id_banco')->onUpdate('restrict')->onDelete('restrict')
                ->on('gen_cat_bancos');
            $table->foreign('fk_id_sat_moneda')->references('id_moneda')->onUpdate('restrict')->onDelete('restrict')
                ->on('sat_cat_monedas');
            $table->foreign('fk_id_empresa')->references('id_empresa')->onUpdate('restrict')->onDelete('restrict')
                ->on('gen_cat_empresas');
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
            ->dropIfExists('gen_cat_numeros_cuenta');
    }
}
