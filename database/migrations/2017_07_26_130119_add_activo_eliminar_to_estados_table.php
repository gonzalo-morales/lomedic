<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddActivoEliminarToEstadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gen_cat_estados', function (Blueprint $table) {
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
        Schema::table('gen_cat_estados',function(Blueprint $table){
            $table->dropColumn('activo');
            $table->dropColumn('eliminar');
        });
    }
}
