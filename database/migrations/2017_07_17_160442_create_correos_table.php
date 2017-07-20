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
        Schema::create('ges_det_correos', function (Blueprint $table) {
            /*Principal fields*/
            $table->increments('id_correo');
            $table->string('correo')->unique()->comment('Correo electronico');
            $table->integer('fk_id_empresa')->unsigned()->comment('Empresa del correo');/*Foreign key from 'empresa' left*/
            $table->integer('fk_id_usuario')->unsigned()->comment('Dueno del correo');

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

            /*Foreign keys*/
            $table->foreign('fk_id_usuario')->references('id_usuario')->on('ges_cat_usuarios');
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
