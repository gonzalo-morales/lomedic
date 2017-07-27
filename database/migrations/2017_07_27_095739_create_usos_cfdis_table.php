<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsosCfdisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::connection('corporativo')
            ->create('sat_cat_usos_cfdis', function (Blueprint $table) {
                $table->increments('id_uso_cfdi');
                $table->string('uso_cfdi','5')->comment('codigo del uso del cfdi')->nullable();
                $table->string('descripcion','500')->comment('descripcion del cfdi')->nullable();

                $table->boolean('activo')->default(true);
                $table->boolean('eliminar')->default(false);
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('corporativo')
            ->dropIfExists('sat_cat_usos_cfdis');
    }
}
