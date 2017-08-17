<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCodigosBarrasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('corporativo')->create('inv_cat_codigos_barras', function (Blueprint $table) {
            $table->increments('id_codigo_barras');
            $table->string('codigo_barras');
            $table->string('descripcion');
            $table->integer('fk_id_sku');
                $table->foreign('fk_id_sku')->references('id_sku')->onUpdate('restrict')->onDelete('restrict')
                    ->on('inv_cat_skus');
            $table->boolean('activo')->default('t');
            $table->boolean('eliminar')->default('f');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('corporativo')->dropIfExists('inv_cat_codigos_barras');
    }
}
