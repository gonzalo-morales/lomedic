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
            $table->smallInteger('fk_id_tipo_documento');
            $table->boolean('activo')->default(true);
            $table->boolean('eliminar')->default(false);

            $table->foreign('fk_id_tipo_documento')->references('id_tipo_documento')->on('maestro.gen_cat_tipo_documento')
                ->onUpdate('restrict')->onDelete('restrict');
        });
        Schema::connection('lomedic')->create('com_cat_condiciones_autorizacion', function (Blueprint $table) {
            $table->increments('id_condicion');
            $table->string('nombre');
            $table->string('campo')->nullable();
            $table->integer('rango_de')->nullable();
            $table->integer('rango_hasta')->nullable();
            $table->string('consulta_sql')->nullable();
            $table->smallInteger('fk_id_tipo_documento');
            $table->boolean('activo')->default(true);
            $table->boolean('eliminar')->default(false);

            $table->foreign('fk_id_tipo_documento')->references('id_tipo_documento')->on('maestro.gen_cat_tipo_documento')
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
        Schema::connection('abisa')->dropIfExists('com_cat_condiciones_autorizacion');
        Schema::connection('lomedic')->dropIfExists('com_cat_condiciones_autorizacion');
    }
}
