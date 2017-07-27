<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterDiagnosticosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gen_cat_diagnosticos', function (Blueprint $table) {
            //
            /*General fields*/
            $table->integer('fk_id_padre')->nullable();
            $table->boolean('activo')->default('1');
            $table->boolean('eliminar')->default('0');

            $table->foreign('fk_id_padre')->references('id_diagnostico')->onDelete('restrict')->onUpdate('restrict')
                ->on(config('database.connections.corporativo.schema').'.gen_cat_diagnosticos');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('gen_cat_diagnosticos', function (Blueprint $table) {
            //
            /*General fields*/
            $table->boolean('activo')->default('1');
            $table->boolean('eliminar')->default('0');

            $table->foreign('fk_id_padre')->references('id_diagnotico')->onDelete('restrict')->onUpdate('restrict')
                ->on(config('database.connections.corporativo.schema').'.gen_cat_usuarios');


        });
    }
}
