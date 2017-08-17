<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSkuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('corporativo')->create('inv_cat_skus', function (Blueprint $table) {
            $table->increments('id_sku');
            $table->string('sku');
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
        Schema::connection('corporativo')->dropIfExists('inv_cat_skus');
    }
}
