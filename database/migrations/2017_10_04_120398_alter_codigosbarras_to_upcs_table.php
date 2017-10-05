<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCodigosbarrasToUpcsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('corporativo')
            ->table('inv_cat_codigos_barras', function (Blueprint $table) {
                $table->renameColumn('id_codigo_barras','id_upc');
                $table->rename('inv_cat_upcs');
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
            ->table('inv_cat_codigos_barras', function (Blueprint $table) {
                $table->renameColumn('id_upc','id_codigo_barras');
                $table->rename('inv_cat_codigos_barras');
            });
    }
}
