<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterSopAccionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('corporativo')
            ->table('sop_cat_acciones', function (Blueprint $table) {
                $table->addColumn('integer','fk_id_subcategoria');

                $table->foreign('fk_id_subcategoria')->references('id_subcategoria')->onUpdate('restrict')
                    ->onDelete('restrict')->on('sop_cat_subcategorias');
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
            ->table('sop_cat_acciones', function (Blueprint $table) {
                $table->dropForeign('fk_id_subcategoria');
                $table->dropColumn('fk_id_subcategoria');
        });
    }
}
