<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSngCatRamosSocio extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('corporativo')->create('sng_cat_ramos_socio', function (Blueprint $table) {
            $table->increments('id_ramo');
            $table->string('ramo');
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
        Schema::connection('corporativo')->dropIfExists('sng_cat_ramos_socio');
    }
}
