<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePrioridadesTickesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('corporativo')
            ->create('sop_cat_prioridades', function (Blueprint $table) {
            $table->increments('id_prioridad');
            $table->string('prioridad');
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
        Schema::connection('corporativo')
            ->dropIfExists('sop_cat_prioridades');
    }
}
