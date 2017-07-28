<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmpleadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('corporativo')
            ->create('rh_cat_empleados', function (Blueprint $table) {
            $table->increments('id_empleado');
            $table->string('nombre');
            $table->string('appellido_paterno');
            $table->string('apellido_materno')->nullable();
            $table->string('curp',18);
            $table->string('rfc',13);
            $table->date('fecha_nacimiento');
            $table->string('correo_personal');
            $table->string('telefono')->nullable();
            $table->string('celular')->nullable();
            $table->integer('fk_id_empresa_alta_imms');
            $table->integer('numero_imms');
            $table->integer('fk_id_empresa_laboral');
            $table->string('numero_infonavit',11)->nullable();
            $table->string('factor_documento')->nullable();

            $table->foreign('fk_id_empresa_alta_imms')->references('id_empresa')->onDelete('restrict')->onUpdate('restrict')
                ->on('gen_cat_empresas');
            $table->foreign('fk_id_empresa_laboral')->references('id_empresa')->onDelete('restrict')->onUpdate('restrict')
                ->on('gen_cat_empresas');
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
            ->dropIfExists('rh_cat_empleados');
    }
}
