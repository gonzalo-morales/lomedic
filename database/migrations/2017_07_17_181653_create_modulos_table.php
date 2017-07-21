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
        Schema::create('ges_cat_modulos', function (Blueprint $table) {
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
            $table->integer('fk_id_usuario_crea')->unsigned();
            $table->timestamp('fecha_crea')->default(DB::raw('now()'));
            $table->integer('fk_id_usuario_actualiza')->unsigned()->nullable();
            $table->timestamp('fecha_actualiza')->nullable();
            $table->integer('fk_id_usuario_elimina')->unsigned()->nullable();
            $table->timestamp('fecha_elimina')->nullable();
            $table->foreign('fk_id_usuario_crea')->references('id_usuario')->on('ges_cat_usuarios');
            $table->foreign('fk_id_usuario_actualiza')->references('id_usuario')->on('ges_cat_usuarios');
            $table->foreign('fk_id_usuario_elimina')->references('id_usuario')->on('ges_cat_usuarios');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('modulos');
    }
}
