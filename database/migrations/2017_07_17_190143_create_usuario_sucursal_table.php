<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsuarioSucursalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection(config('database.connections.corporativo.schema'))
            ->create('ges_det_usuario_sucursal', function (Blueprint $table) {
            $table->integer('fk_id_usuario')->unsigned()->comment('Llave foranea al usuario');
            $table->integer('fk_id_sucursal')->unsigned()->comment('Llave foranea a la sucursal');
            $table->boolean('activo')->default('1');

            /*Foreign keys*/
            $table->foreign('fk_id_usuario')->references('id_usuario')->on('ges_cat_usuarios')->
            onDelete('restrict')->onUpdate('restrict');
            $table->foreign('fk_id_sucursal')->references('id_sucursal')->on('ges_cat_sucursales')->
            onDelete('restrict')->onUpdate('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection(config('database.connections.corporativo.schema'))
            ->dropIfExists('ges_det_usuario_sucursal');
    }
}
