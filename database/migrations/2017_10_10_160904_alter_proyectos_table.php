<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterProyectosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('abisa')
            ->table('pry_cat_proyectos', function (Blueprint $table) {
            $table->smallInteger('fk_id_cliente')->nullable();
                $table->foreign('fk_id_cliente')->references('id_socio_negocio')
                    ->onUpdate('restrict')->onDelete('restrict')->on('maestro.gen_cat_socios_negocio');
            $table->date('fecha_contrato')->default(DB::raw('now()'));
            $table->date('fecha_inicio_contrato')->default(DB::raw('now()'));
            $table->date('fecha_fin_contrato')->default(DB::raw('now()'));
            $table->string('numero_contrato',200)->nullable();
            $table->string('numero_proyecto',50)->nullable();
            $table->decimal('monto_adjudicado',20,10)->nullable();
            $table->smallInteger('fk_id_clasificacion_proyecto')->nullable();
                $table->foreign('fk_id_clasificacion_proyecto')->references('id_clasificacion_proyecto')
                    ->onUpdate('restrict')->onDelete('restrict')->on('maestro.pry_cat_clasificaciones_proyecto');
            $table->text('plazo')->nullable();
            $table->string('representante_legal',200)->nullable();
            $table->string('numero_fianza',60)->nullable();
            });

        Schema::connection('lomedic')
            ->table('pry_cat_proyectos', function (Blueprint $table) {
                $table->smallInteger('fk_id_cliente')->nullable();
                $table->foreign('fk_id_cliente')->references('id_socio_negocio')
                    ->onUpdate('restrict')->onDelete('restrict')->on('maestro.gen_cat_socios_negocio');
                $table->date('fecha_contrato')->default(DB::raw('now()'));
                $table->date('fecha_inicio_contrato')->default(DB::raw('now()'));
                $table->date('fecha_fin_contrato')->default(DB::raw('now()'));
                $table->string('numero_contrato',200)->nullable();
                $table->string('numero_proyecto',50)->nullable();
                $table->decimal('monto_adjudicado',20,10)->nullable();
                $table->smallInteger('fk_id_clasificacion_proyecto')->nullable();
                $table->foreign('fk_id_clasificacion_proyecto')->references('id_clasificacion_proyecto')
                    ->onUpdate('restrict')->onDelete('restrict')->on('maestro.pry_cat_clasificaciones_proyecto');
                $table->text('plazo')->nullable();
                $table->string('representante_legal',200)->nullable();
                $table->string('numero_fianza',60)->nullable();
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
            ->table('pry_cat_proyectos', function (Blueprint $table) {
                $table->dropForeign('pry_cat_proyectos_fk_id_cliente_foreign');
                $table->dropForeign('pry_cat_proyectos_fk_id_clasificacion_proyecto_foreign');
                $table->dropColumn(['fk_id_cliente','fecha_contrato','fecha_inicio_contrato','fecha_fin_contrato',
                    'numero_contrato','numero_contrato','numero_proyecto','monto_adjudicado',
                    'fk_id_clasificacion_proyecto','plazo','representante_legal','numero_fianza','activo','eliminar']);
        });
        Schema::connection('lomedic')
            ->table('pry_cat_proyectos', function (Blueprint $table) {
                $table->dropForeign('pry_cat_proyectos_fk_id_cliente_foreign');
                $table->dropForeign('pry_cat_proyectos_fk_id_clasificacion_proyecto_foreign');
                $table->dropColumn(['fk_id_cliente','fecha_contrato','fecha_inicio_contrato','fecha_fin_contrato',
                    'numero_contrato','numero_contrato','numero_proyecto','monto_adjudicado',
                    'fk_id_clasificacion_proyecto','plazo','representante_legal','numero_fianza','activo','eliminar']);
            });
    }
}
