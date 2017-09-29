<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterDiagnosticos2Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gen_cat_diagnosticos', function (Blueprint $table) {
            /*General fields*/
            $table->dropColumn('estatus');
            $table->dropColumn('id_diagnostico_sp');
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
            $table->smallInteger('estatus');
            $table->mediumInteger('id_diagnostico_sp');
        });
    }
}
