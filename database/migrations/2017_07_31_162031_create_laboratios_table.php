<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLaboratiosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('corporativo')
            ->create('gen_cat_laboratorios', function (Blueprint $table) {
                $table->increments('id_laboratorio');
                $table->string('laboratorio ');
                $table->date('fecha_captura');
                $table->date('fecha_modificacion');
                $table->integer('id_usuario_captura');
                $table->integer('id_usuario_modificacion');

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
            ->dropIfExists('gen_cat_laboratorios');
    }
}
