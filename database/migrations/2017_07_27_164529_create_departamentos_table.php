<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDepartamentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('corporativo')
            ->create('rh_cat_departamentos', function (Blueprint $table) {
                $table->increments('id_departamento');
                $table->string('descripcion');
                $table->string('nomenclatura','50');

                $table->boolean('activo')->default(true);
                $table->boolean('eliminar')->default(false);
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
            ->dropIfExists('rh_cat_departamentos');
    }
}
