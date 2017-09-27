<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGenCatSociosNegocio extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('corporativo')->create('gen_cat_socios_negocio', function (Blueprint $table) {
            $table->increments('id_socio_negocio');
            $table->integer('fk_id_tipo_socio');
            $table->integer('fk_id_forma_pago');
            $table->integer('fk_id_tipo_entrega');
            $table->integer('fk_id_sucursal_entrega');
            $table->integer('fk_id_usuario_modificacion');
            $table->string('razon_social');
            $table->string('rfc');
            $table->string('nombre_corto');
            $table->string('telefono');
            $table->string('sitio_web');
            $table->decimal('monto_credito',16,2);
            $table->integer('dias_credito');
            $table->decimal('monto_minimo_facturacion',16,2);
            // $table->integer('fk_id_correo_orden_compra');
            $table->timestamp('fecha_modificacion')->nullable();
            $table->boolean('activo')->default(true);
            $table->boolean('eliminar')->default(false);

            // $table->foreign('fk_id_tipo_socio')->references('id_tipo_socio')->on('sng_cat_tipos_socios')
            //     ->onUpdate('restrict')->onDelete('restrict');
            $table->foreign('fk_id_forma_pago')->references('id_forma_pago')->on('sat_cat_formas_pago')
                ->onUpdate('restrict')->onDelete('restrict');
            $table->foreign('fk_id_tipo_entrega')->references('id_tipo_entrega')->on('sng_cat_tipos_entrega')
                ->onUpdate('restrict')->onDelete('restrict');
            // $table->foreign('fk_id_correo_orden_compra')->references('id_correo')->on('sng_det_correos_orden_compra')
            //     ->onUpdate('restrict')->onDelete('restrict');
            $table->foreign('fk_id_sucursal_entrega')->references('id_sucursal')->on('ges_cat_sucursales')
                ->onUpdate('restrict')->onDelete('restrict');
            $table->foreign('fk_id_usuario_modificacion')->references('id_usuario')->on('ges_cat_usuarios')
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
        Schema::connection('corporativo')->dropIfExists('gen_cat_socios_negocio');
    }
}
