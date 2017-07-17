<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePerfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ges_cat_perfiles', function (Blueprint $table) {
            /*Campos principales*/
            $table->increments('id_perfil');
            $table->String('nombre_perfil','20')->unique()->comment('Nombre del perfil de usuario');
            $table->text('descripcion','')->comment('Descripcion del perfil');

            /*Campos generales*/
            $table->boolean('activo')->default('1');
            $table->boolean('eliminar')->default('0');

            $table->integer('fk_id_usuario_crea');
            $table->timestamp('fecha_crea')->default(DB::raw('now()'));

            $table->integer('fk_id_usuario_actualiza')->nullable();
            $table->timestamp('fecha_actualiza')->nullable();

            $table->integer('fk_id_usuario_elimina')->nullable();
            $table->timestamp('fecha_elimina')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ges_cat_perfiles');
    }
}
