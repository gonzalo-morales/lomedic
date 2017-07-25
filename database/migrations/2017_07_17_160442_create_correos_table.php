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

            /*Foreign keys*/
            $table->foreign('fk_id_usuario')->references('id_usuario')->on('ges_cat_usuarios')->
            onDelete('restrict')->onUpdate('restrict');
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
