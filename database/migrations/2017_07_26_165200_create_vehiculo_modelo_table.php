<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVehiculoModeloTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('corporativo')
            ->create('gen_cat_vehiculo_modelo', function (Blueprint $table) {
                $table->increments('id_modelo');
                $table->string('modelo');
                $table->integer('fk_id_marca');
                $table->boolean('activo')->default('true');
                $table->boolean('eliminar')->default('false');
                $table->foreign('fk_id_marca')->references('id_marca')->on('gen_cat_vehiculo_marca')
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
            ->dropIfExists('gen_cat_vehiculo_modelo');
    }
}
