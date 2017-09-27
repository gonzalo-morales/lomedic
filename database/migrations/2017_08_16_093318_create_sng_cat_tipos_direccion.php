<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSngCatTiposDireccion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('corporativo')->create('sng_cat_tipos_direccion', function (Blueprint $table) {
                    $table->increments('id_tipo_direccion');
                    $table->string('tipo_direccion');
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
        Schema::connection('corporativo')
                ->dropIfExists('sng_cat_tipos_direccion');
    }
}
