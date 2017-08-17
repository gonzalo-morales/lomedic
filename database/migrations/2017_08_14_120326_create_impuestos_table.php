<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImpuestosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('corporativo')->create('gen_cat_impuestos', function (Blueprint $table) {
            $table->increments('id_impuesto');
            $table->string('impuesto');
            $table->boolean('activo')->default('t');
            $table->boolean('eliminar')->default('f');
            $table->integer('porcentaje');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('corporativo')->dropIfExists('gen_cat_impuestos');
    }
}
