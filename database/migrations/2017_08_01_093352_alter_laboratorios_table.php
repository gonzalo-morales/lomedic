<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterLaboratoriosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gen_cat_laboratorios', function (Blueprint $table) {
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


        Schema::table('gen_cat_laboratorio', function (Blueprint $table) {
            //
            /*General fields*/
            $table->boolean('activo')->default(true);
            $table->boolean('eliminar')->default(false);


        });
    }
}
