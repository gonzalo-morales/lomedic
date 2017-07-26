<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTipocombustibleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('corporativo')
            ->table('gen_cat_tipo_combustible', function (Blueprint $table) {
            $table->renameColumn('estatus','activo')->default('true');
            $table->boolean('eliminar')->default('false');
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
            ->table('gen_cat_tipo_combustible', function (Blueprint $table) {
            $table->renameColumn('activo','estatus');
            $table->dropColumn('eliminar');
        });
    }
}
