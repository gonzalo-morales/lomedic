<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateGesCatProveedoresTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::connection('corporativo')->create('ges_cat_proveedores', function(Blueprint $table)
		{
			$table->integer('id_proveedor', true);
			$table->string('nombre');
			$table->string('rfc', 16)->nullable();
			$table->string('calle')->nullable();
			$table->string('num_exterior', 10)->nullable();
			$table->string('num_interior', 10)->nullable();
			$table->string('colonia', 100)->nullable();
			$table->string('estado', 60)->nullable();
			$table->string('municipio', 60)->nullable();
			$table->integer('cp')->nullable();
			$table->string('telefono1', 20);
			$table->string('telefono2', 20)->nullable();
			$table->string('fax', 20)->nullable();
			$table->string('url')->nullable();
			$table->dateTime('fecha_captura')->nullable();
			$table->integer('id_usuario')->nullable();
			$table->smallInteger('tipo_proveedor')->nullable()->comment('0: Insumos, 1:Servicios');
			$table->integer('plazo')->nullable()->comment('plazo en dias');
			$table->smallInteger('estatus')->nullable()->comment('0: Inactivo; 1: Activo');
			$table->integer('total_direcciones')->nullable();
			$table->integer('id_pais');
			$table->integer('id_estado')->nullable();
			$table->integer('id_municipio')->nullable();
			$table->string('nombre_empresa', 50)->nullable();
			$table->smallInteger('mayorista')->nullable()->default(0)->comment('0: No Mayorista, 1: Mayorista');
			$table->string('otro_tipo_insumo', 50)->nullable();
			$table->smallInteger('tipo_pago')->nullable()->comment('0:Credito,1:Contado,2:50/50,3Otro');
			$table->string('otro_tipo_pago', 50)->nullable();
			$table->smallInteger('lugar_entrega')->nullable()->comment('0:Sucursal, 1:Paqueteria, 2:Recolección');
			$table->smallInteger('dias_tiempo_entrega')->nullable();
			$table->smallInteger('id_tipo_insumo')->nullable()->comment('1: Equipo Medico, 2: Instrumental, 3: Material de Curación, 4: Medicamento, 5: Otro');
			$table->text('id_sucursal_entrega')->nullable()->comment('referencia a cat_sucursal');
			$table->smallInteger('quien_paga')->nullable()->comment('1: Proveedor, 2: Nosotros ');
			$table->decimal('monto_credito', 16)->nullable();
			$table->string('email_envio_oc', 50)->nullable();
			$table->string('email_envio_oc2', 50)->nullable();
			$table->string('email_envio_oc3', 50)->nullable();
			$table->smallInteger('prioridad')->nullable()->comment('1: Alta 2: Media 3: Baja');
			$table->smallInteger('apoyo')->nullable()->comment('1: Apoyo, 0: No Apoyo');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::connection('corporativo')->drop('ges_cat_proveedores');
	}

}
