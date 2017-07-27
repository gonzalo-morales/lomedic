<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTiposComprobantesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::connection('corporativo')
            ->create('sat_cat_tipos_comprobantes', function (Blueprint $table) {
                $table->increments('id_tipo_comprobante');
                $table->string('tipo_comprobante','2');
                $table->string('descripcion','50');
                $table->decimal('limite',20,10)->nullable();

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
            ->dropIfExists('sat_cat_tipos_comprobantes');

    }
}
