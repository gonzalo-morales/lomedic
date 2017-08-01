<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterSopSubcategoriasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('corporativo')
            ->table('sop_cat_subcategorias', function (Blueprint $table) {
            $table->addColumn('integer','fk_id_categoria');

            $table->foreign('fk_id_categoria')->references('id_categoria')->onUpdate('restrict')
                ->onDelete('restrict')->on('sop_cat_categorias');
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
            ->table('sop_cat_subcategorias', function (Blueprint $table) {
                $table->dropForeign('fk_id_categoria');
                $table->dropColumn('fk_id_categoria');
        });
    }
}
