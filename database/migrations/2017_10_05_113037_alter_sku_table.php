<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterSkuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('corporativo')
            ->table('inv_cat_skus', function (Blueprint $table) {
                $table->string('nombre_comercial',255)->default('');
                $table->string('descripcion',255)->default('');
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
            ->table('inv_cat_skus', function (Blueprint $table) {
            $table->dropColumn('descripcion','nombre_comercial');
        });
    }
}
