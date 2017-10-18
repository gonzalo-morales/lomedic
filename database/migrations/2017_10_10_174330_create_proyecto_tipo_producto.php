<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProyectoTipoProducto extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('abisa')
            ->create('pry_cat_tipos_productos', function (Blueprint $table) {
            $table->increments('id_tipo_producto');
            $table->smallInteger('fk_id_proyecto');
                $table->foreign('fk_id_proyecto')->references('id_proyecto')
                    ->onUpdate('restrict')->onDelete('restrict')->on('pry_cat_proyectos');
            $table->string('descripcion',60);
            $table->boolean('activo')->default('f');
        });
        Schema::connection('lomedic')
            ->create('pry_cat_tipos_productos', function (Blueprint $table) {
                $table->increments('id_tipo_producto');
                $table->smallInteger('fk_id_proyecto');
                $table->foreign('fk_id_proyecto')->references('id_proyecto')
                    ->onUpdate('restrict')->onDelete('restrict')->on('pry_cat_proyectos');
                $table->string('descripcion',60);
                $table->boolean('activo')->default('f');
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
            ->dropIfExists('pry_cat_tipo_producto');
    }
}
