<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClasificacionesTiposCuadroTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('corporativo')
            ->create('pry_cat_clasificaciones_proyecto', function (Blueprint $table) {
                $table->smallIncrements('id_clasificacion_proyecto');
                $table->string('clasificacion',255);
                $table->string('nomenclatura', 15);
                $table->smallInteger('fk_id_tipo_proyecto');
                $table->foreign('fk_id_tipo_proyecto')->references('id_tipo_proyecto')
                    ->onUpdate('restrict')->onDelete('restrict')->on('pry_cat_tipos_proyectos');
                $table->boolean('activo')->default('t');
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
            ->dropIfExists('pry_cat_clasificaciones_proyecto');
    }
}
