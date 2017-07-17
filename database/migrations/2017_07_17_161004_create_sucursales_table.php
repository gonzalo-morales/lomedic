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
            $table->increments('id_sucursal');
            $table->string('nombre_sucursal')->unique()->comment('Nombre de la sucursal');
            $table->integer('fk_id_localidad')->comment('ID de la localidad');
            $table->string('latitud','10')->comment('Latitud para mapas');
            $table->string('longitud','10')->comment('Longitud para mapas');
            $table->integer('fk_id_tipo_sucursal')->comment('ID tipo de sucursal: hospital, centro de distribucion, matriz');
            $table->integer('fk_id_red')->comment('Red de ');
            $table->integer('fk_id_supervisor')->comment('Usuario supervisor');
            $table->integer('fk_id_cliente')->comment('Cliente');
            $table->boolean('embarque')->comment('0: no hay embarque; 1: Sí hay embarque');
            $table->string('calle')->comment('Calle donde se encuentra la sucursal');
            $table->string('no_interior')->comment('Numero interior');
            $table->string('no_exterior')->comment('Número exterior');
            $table->string('colonia')->comment('Colonia donde se encuentra la sucursal');
            $table->string('codigo_postal','5')->comment('Codigo postal');
            $table->integer('fk_id_municipio')->comment('Municipio donde se encuentra la sucursal');
            $table->integer('fk_id_estado')->comment('Estado donde se encuentra la sucursal');
            $table->integer('fk_id_pais')->comment('Pais donde se encuentra la sucursal');
            $table->string('registro_sanitario','13')->comment('Numero de registro sanitario');
            $table->string('tipo_batallon')->comment('Utilizado cuando la sucursal es militar');
            $table->string('region')->comment('Utilizado cuando la sucursal es militar');
            $table->string('zona_militar')->comment('Utilizado cuando la sucursal es militar');
            $table->string('telefono1')->comment('Telefono 1 de la sucursal');
            $table->string('telefono2')->comment('Telefono 2 de la sucursal');
            $table->string('clave_presupuestal','12')->comment('Clave de presupuesto');
            $table->string('fk_id_jurisdiccion')->comment('Jurisdiccion');

            /*Campos generales*/
            $table->boolean('activo')->default('1');
            $table->boolean('eliminar')->default('0');

            $table->integer('fk_id_usuario_crea');
            $table->timestamp('fecha_crea')->default(DB::raw('now()'));

            $table->integer('fk_id_usuario_actualiza')->nullable();
            $table->timestamp('fecha_actualiza')->nullable();

            $table->integer('fk_id_usuario_elimina')->nullable();
            $table->timestamp('fecha_elimina')->nullable();
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
