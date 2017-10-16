<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMaestroMaterialesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('abisa')
            ->create('pry_cat_proyectos_productos', function (Blueprint $table) {
                $table->increments('id_proyecto_producto');
                $table->integer('fk_id_clave_cliente_producto');
                $table->foreign('fk_id_clave_cliente_producto')->references('id_clave_cliente_producto')
                    ->onUpdate('restrict')->onDelete('restrict')->on('pry_cat_clave_cliente_productos');
                $table->integer('fk_id_upc');
                $table->foreign('fk_id_upc')->references('id_upc')
                    ->onUpdate('restrict')->onDelete('restrict')->on('maestro.inv_cat_upcs');
                $table->smallInteger('prioridad');
                $table->smallInteger('cantidad');
                $table->decimal('precio_sugerido',20,10);
                $table->boolean('activo')->default('t');
                $table->boolean('eliminar')->default('f');
            });

        Schema::connection('lomedic')
            ->create('pry_cat_proyectos_productos', function (Blueprint $table) {
                $table->increments('id_proyecto_producto');
                $table->integer('fk_id_clave_cliente_producto');
                $table->foreign('fk_id_clave_cliente_producto')->references('id_clave_cliente_producto')
                    ->onUpdate('restrict')->onDelete('restrict')->on('pry_cat_clave_cliente_productos');
                $table->integer('fk_id_upc');
                $table->foreign('fk_id_upc')->references('id_upc')
                    ->onUpdate('restrict')->onDelete('restrict')->on('maestro.inv_cat_upcs');
                $table->smallInteger('prioridad');
                $table->smallInteger('cantidad');
                $table->decimal('precio_sugerido',20,10);
                $table->boolean('activo')->default('t');
                $table->boolean('eliminar')->default('f');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('abisa')
            ->dropIfExists('pry_cat_proyectos_productos');
        Schema::connection('lomedic')
            ->dropIfExists('pry_cat_proyectos_productos');
    }
}
