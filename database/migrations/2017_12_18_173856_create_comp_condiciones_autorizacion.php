<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompCondicionesAutorizacion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('abisa')->create('com_cat_condiciones_autorizacion', function (Blueprint $table) {
            $table->increments('id_condicion');
            $table->string('nombre');
            $table->string('campo')->nullable();
            $table->integer('rango_de')->nullable();
            $table->integer('rango_hasta')->nullable();
            $table->string('consulta_sql')->nullable();
            $table->smallInteger('tipo_documento');
            $table->boolean('activo')->default(true);
            $table->boolean('eliminar')->default(false);
        });
        Schema::connection('lomedic')->create('com_cat_condiciones_autorizacion', function (Blueprint $table) {
            $table->increments('id_condicion');
            $table->string('nombre');
            $table->string('campo')->nullable();
            $table->integer('rango_de')->nullable();
            $table->integer('rango_hasta')->nullable();
            $table->string('consulta_sql')->nullable();
            $table->smallInteger('tipo_documento');
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
        Schema::connection('abisa')->dropIfExists('com_cat_condiciones_autorizacion');
        Schema::connection('lomedic')->dropIfExists('com_cat_condiciones_autorizacion');
    }
}
