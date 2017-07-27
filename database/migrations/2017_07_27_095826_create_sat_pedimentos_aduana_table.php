<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSatPedimentosAduanaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('corporativo')
            ->create('sat_cat_pedimentos_aduana', function (Blueprint $table) {
            $table->increments('id_pedimento');
            $table->string('aduana',2);
            $table->integer('patente');
            $table->integer('ejercicio');
            $table->integer('cantidad');
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
            ->dropIfExists('sat_cat_pedimentos_aduana');
    }
}
