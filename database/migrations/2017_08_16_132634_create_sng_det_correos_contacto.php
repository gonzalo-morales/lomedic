<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSngDetCorreosContacto extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('corporativo')->create('sng_det_correos_contacto', function (Blueprint $table) {
            $table->integer('fk_id_contacto');
            $table->string('correo');
            $table->boolean('activo')->default(true);
            $table->boolean('eliminar')->default(false);

            $table->foreign('fk_id_contacto')->references('id_contacto')->on('sng_cat_contactos')
                ->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('corporativo')->dropIfExists('sng_det_correos_contacto');
    }
}
