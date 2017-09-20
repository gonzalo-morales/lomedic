<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUbicacionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inv_cat_ubicaciones', function (Blueprint $table) {
            $table->increments('id_ubicacion');
            $table->string('nombre');
            $table->integer('fk_id_almacen');
            $table->string('rack',5);
            $table->string('pasillo',5);
            $table->string('nivel',5);
            $table->string('posicion',5);
            $table->integer('longitud')->default(0)->nullable();
            $table->integer('ancho')->default(0)->nullable();
            $table->integer('altura')->default(0)->nullable();
            $table->integer('volumen')->default(0)->nullable();
            $table->integer('peso')->default(0)->nullable();

            $table->foreign('fk_id_almacen')
                ->references('id_almacen')->onUpdate('restrict')->onDelete('restrict')
                ->on('inv_cat_almacenes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inv_cat_ubicaciones');
    }
}
