<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCorreosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ges_cat_correos', function (Blueprint $table) {
            $table->increments('id_correo');
            $table->string('correo')->unique()->comment('Correo electronico');
            $table->integer('fk_id_empresa')->comment('Empresa del correo');
            $table->integer('fk_id_usuario')->comment('Dueno del correo');

            /*Campos generales*/
            $table->boolean('activo');
            $table->boolean('eliminar');

            $table->integer('fk_id_usuario_crea');
            $table->timestamp('fecha_crea')->default(DB::raw('now()'));

            $table->integer('fk_id_usuario_actualiza');
            $table->timestamp('fecha_actualiza');

            $table->integer('fk_id_usuario_elimina');
            $table->timestamp('fecha_elimina');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ges_cat_correos');
    }
}
