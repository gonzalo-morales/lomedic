<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProyectoProductoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('abisa')
            ->create('pry_cat_productos_proyectos', function (Blueprint $table) {
                $table->integer('id_producto_proyecto', true);
                    $table->dropPrimary('pry_cat_productos_proyectos_pkey');
                $table->smallInteger('fk_id_proyecto');
                $table->string('clave_producto_cliente', 20);
                $table->string('subclave', 20);
                $table->text('descripcion');
                $table->text('presentacion');
                $table->smallInteger('cantidad_presentacion');
                $table->smallInteger('fk_id_unidad_medida');
                $table->foreign('fk_id_unidad_medida')->references('id_unidad_medida')
                    ->onUpdate('restrict')->onDelete('restrict')->on('maestro.gen_cat_unidades_medidas');
                $table->mediumInteger('fk_id_clave_producto_servicio');
                $table->foreign('fk_id_clave_producto_servicio')->references('id_clave_producto_servicio')
                    ->onUpdate('restrict')->onDelete('restrict')->on('maestro.sat_cat_claves_productos_servicios');
                $table->smallInteger('fk_id_clave_unidad');
                $table->foreign('fk_id_clave_unidad')->references('id_clave_unidad')
                    ->onUpdate('restrict')->onDelete('restrict')->on('maestro.sat_cat_claves_unidades');
                $table->string('marca', 100);
                $table->text('fabricante');
                $table->decimal('precio', 20, 10);
                $table->decimal('precio_referencia', 20, 10);
                $table->decimal('descuento', 20, 10);
                $table->decimal('descuento_porcentaje', 20, 10);
                $table->smallInteger('fk_id_impuesto');
                $table->foreign('fk_id_impuesto')->references('id_impuesto')
                    ->onUpdate('restrict')->onDelete('restrict')->on('maestro.gen_cat_impuestos');
                $table->decimal('dispensacion', 20, 10);
                $table->decimal('dispensacion_porcentaje', 20, 10);
                $table->boolean('activo')->default('t');
                $table->smallInteger('fk_id_proyecto_tipo_producto');
                $table->foreign('fk_id_proyecto_tipo_producto')->references('id_tipo_producto')
                    ->onUpdate('restrict')->onDelete('restrict')->on('pry_cat_tipos_productos');
                $table->smallInteger('fk_id_propietario');
                $table->foreign('fk_id_propietario')->references('id_propietario_proyecto')
                    ->onUpdate('restrict')->onDelete('restrict')->on('maestro.pry_cat_propietarios_proyectos');
                $table->smallInteger('fk_id_tipo_almacen');
                $table->foreign('fk_id_tipo_almacen')->references('id_tipo')
                    ->onUpdate('restrict')->onDelete('restrict')->on('maestro.gen_cat_tipo_almacen');
                $table->boolean('pertenece_cuadro')->default('t');
                $table->smallInteger('minimo',false,true);
                $table->smallInteger('maximo',false,true);

                $table->primary(['fk_id_proyecto','clave_producto_cliente']);
                $table->unique('id_producto_proyecto');
            });

        Schema::connection('lomedic')
            ->create('pry_cat_productos_proyectos', function (Blueprint $table) {
                $table->integer('id_producto_proyecto', true);
                    $table->dropPrimary('pry_cat_productos_proyectos_pkey');
                $table->smallInteger('fk_id_proyecto');
                $table->string('clave_producto_cliente', 20);
                $table->string('subclave', 20);
                $table->text('descripcion');
                $table->text('presentacion');
                $table->smallInteger('cantidad_presentacion');
                $table->smallInteger('fk_id_unidad_medida');
                $table->foreign('fk_id_unidad_medida')->references('id_unidad_medida')
                    ->onUpdate('restrict')->onDelete('restrict')->on('maestro.gen_cat_unidades_medidas');
                $table->mediumInteger('fk_id_clave_producto_servicio');
                $table->foreign('fk_id_clave_producto_servicio')->references('id_clave_producto_servicio')
                    ->onUpdate('restrict')->onDelete('restrict')->on('maestro.sat_cat_claves_productos_servicios');
                $table->smallInteger('fk_id_clave_unidad');
                $table->foreign('fk_id_clave_unidad')->references('id_clave_unidad')
                    ->onUpdate('restrict')->onDelete('restrict')->on('maestro.sat_cat_claves_unidades');
                $table->string('marca', 100);
                $table->text('fabricante');
                $table->decimal('precio', 20, 10);
                $table->decimal('precio_referencia', 20, 10);
                $table->decimal('descuento', 20, 10);
                $table->decimal('descuento_porcentaje', 20, 10);
                $table->smallInteger('fk_id_impuesto');
                $table->foreign('fk_id_impuesto')->references('id_impuesto')
                    ->onUpdate('restrict')->onDelete('restrict')->on('maestro.gen_cat_impuestos');
                $table->decimal('dispensacion', 20, 10);
                $table->decimal('dispensacion_porcentaje', 20, 10);
                $table->boolean('activo')->default('t');
                $table->smallInteger('fk_id_proyecto_tipo_producto');
                $table->foreign('fk_id_proyecto_tipo_producto')->references('id_tipo_producto')
                    ->onUpdate('restrict')->onDelete('restrict')->on('pry_cat_tipos_productos');
                $table->smallInteger('fk_id_propietario');
                $table->foreign('fk_id_propietario')->references('id_propietario_proyecto')
                    ->onUpdate('restrict')->onDelete('restrict')->on('maestro.pry_cat_propietarios_proyectos');
                $table->smallInteger('fk_id_tipo_almacen');
                $table->foreign('fk_id_tipo_almacen')->references('id_tipo')
                    ->onUpdate('restrict')->onDelete('restrict')->on('maestro.gen_cat_tipo_almacen');
                $table->boolean('pertenece_cuadro')->default('t');
                $table->smallInteger('minimo',false,true);
                $table->smallInteger('maximo',false,true);

                $table->primary(['clave_producto_cliente','fk_id_proyecto']);
                $table->unique('id_producto_proyecto');
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('abisa')
            ->dropIfExists('pry_cat_propiedades_proyectos');
        Schema::connection('lomedic')
            ->dropIfExists('pry_cat_propiedades_proyectos');
    }
}
