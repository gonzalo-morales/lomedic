<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlmacenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('corporativo')
            ->create('inv_cat_almacenes', function (Blueprint $table) {
            $table->increments('id_almacen');
            $table->string('codigo');
            $table->string('nombre');
            $table->integer('fk_id_sucursal');
            $table->boolean('virtual')->default('f');
            $table->integer('fk_id_almacen')->nullable();
            $table->boolean('generar_inventario')->default('t');
            $table->integer('longitud')->default(0)->nullable();
            $table->integer('ancho')->default(0)->nullable();
            $table->integer('altura')->default(0)->nullable();
            $table->integer('volumen')->default(0)->nullable();
            $table->integer('peso')->default(0)->nullable();

            $table->boolean('activo')->default('t');
            $table->boolean('eliminar')->default('f');

            $table->foreign('fk_id_sucursal')
                ->references('id_sucursal')->onUpdate('restrict')->onDelete('restrict')
            ->on('ges_cat_sucursales');
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
            ->dropIfExists('inv_cat_almacenes');
    }
}
