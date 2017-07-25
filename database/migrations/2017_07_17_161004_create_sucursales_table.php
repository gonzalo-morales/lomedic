<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSucursalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ges_cat_sucursales', function (Blueprint $table) {
            /*Principal fields*/
            $table->increments('id_sucursal');
            $table->string('nombre_sucursal')->unique()->comment('Nombre de la sucursal');
            $table->integer('fk_id_localidad')->comment('ID de la localidad')->nullable();
            $table->string('latitud','10')->comment('Latitud para mapas')->nullable();
            $table->string('longitud','10')->comment('Longitud para mapas')->nullable();
            $table->integer('fk_id_tipo_sucursal')->comment('ID tipo de sucursal: hospital, centro de distribucion, matriz')->nullable();
            $table->integer('fk_id_red')->comment('Red de ')->nullable();
            $table->integer('fk_id_supervisor')->comment('Usuario supervisor')->nullable();
            $table->integer('fk_id_cliente')->comment('Cliente')->nullable();
            $table->boolean('embarque')->comment('0: no hay embarque; 1: Sí hay embarque')->nullable();
            $table->string('calle')->comment('Calle donde se encuentra la sucursal')->nullable();
            $table->string('no_interior')->comment('Numero interior')->nullable();
            $table->string('no_exterior')->comment('Número exterior')->nullable();
            $table->string('colonia')->comment('Colonia donde se encuentra la sucursal')->nullable();
            $table->string('codigo_postal','5')->comment('Codigo postal')->nullable();
            $table->integer('fk_id_municipio')->comment('Municipio donde se encuentra la sucursal')->nullable();
            $table->integer('fk_id_estado')->comment('Estado donde se encuentra la sucursal')->nullable();
            $table->integer('fk_id_pais')->comment('Pais donde se encuentra la sucursal')->nullable();
            $table->string('registro_sanitario','13')->comment('Numero de registro sanitario')->nullable();
            $table->string('tipo_batallon')->comment('Utilizado cuando la sucursal es militar')->nullable();
            $table->string('region')->comment('Utilizado cuando la sucursal es militar')->nullable();
            $table->string('zona_militar')->comment('Utilizado cuando la sucursal es militar')->nullable();
            $table->string('telefono1')->comment('Telefono 1 de la sucursal')->nullable();
            $table->string('telefono2')->comment('Telefono 2 de la sucursal')->nullable();
            $table->string('clave_presupuestal','12')->comment('Clave de presupuesto')->nullable();
            $table->string('fk_id_jurisdiccion')->comment('Jurisdiccion')->nullable();

            /*General fields*/
            $table->boolean('activo')->default('1');
            $table->boolean('eliminar')->default('0');

            /*Foreign keys*/
//            $table->foreign('fk_id_supervisor')->references('id_usuario')->on('ges_cat_usuarios')->
//            onDelete('restrict')->onUpdate('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ges_cat_sucursales');
    }
}
