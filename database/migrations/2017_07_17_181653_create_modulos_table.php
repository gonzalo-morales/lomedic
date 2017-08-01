<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModulosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('corporativo')
            ->create('ges_cat_modulos', function (Blueprint $table) {
            /*Principal fields*/
            $table->increments('id_modulo');
            $table->string('nombre')->comment('Nombre del modulo')->unique();
            $table->mediumText('descripcion')->comment('Descripción del módulo')->nullable();
            $table->string('url')->comment('URL a seguir')->nulabble();
            $table->string('icono')->comment('Clase de materialize')->nullable();
            $table->boolean('activo_modulo')->default('1')->comment('Indica si el modulo esta activo o no');
            $table->boolean('accion_menu')->default('0')->comment('Indica si el modulo es parte del menu');
            $table->boolean('accion_barra')->default('0')->comment('Indica si el modulo es parte de la barra');
            $table->boolean('accion_tabla')->default('0')->comment('Indica si el modulo es parte de la tabla');
            $table->boolean('modulo_seguro')->default('1')->comment('Indica si el modulo es abierto a todos o no');

            /*General fields*/
            $table->boolean('activo')->default('1');
            $table->boolean('eliminar')->default('0');
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
            ->dropIfExists('modulos');
    }
}
