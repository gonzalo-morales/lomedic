<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProyectosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('abisa')
            ->create('pry_cat_proyectos', function (Blueprint $table) {
            $table->increments('id_proyecto');
            $table->string('proyecto');
            $table->boolean('activo')->default('t');
            $table->boolean('eliminar')->default('f');
        });
        Schema::connection('lomedic')
            ->create('pry_cat_proyectos', function (Blueprint $table) {
                $table->increments('id_proyecto');
                $table->string('proyecto');
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
        Schema::connection('lomedic')->dropIfExists('pry_cat_proyectos');
        Schema::connection('abisa')->dropIfExists('pry_cat_proyectos');
    }
}
