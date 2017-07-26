<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAreasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('corporativo')
            ->table('gen_cat_areas', function (Blueprint $table) {
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
        Schema::connection('corporativo')
            ->table('gen_cat_areas',function(Blueprint $table){
            $table->dropColumn('activo');
            $table->dropColumn('eliminar');
        });
    }
}
