<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('logs')
            ->create('log_movimientos', function (Blueprint $table) {
            $table->increments('id_log');
            $table->string('tabla')->comment('Tabla afectada');
            $table->integer('fk_id_usuario')->comment('Usuario que realizó el movimiento');
            $table->integer('fk_id_empresa')->comment('Empresa en la que se realizó el movimiento');
            $table->integer('fk_id_accion')->comment('Acción realizada: actualizar, eliminar, crear, ver e index');
            $table->integer('id_registro')->comment('Registro afectado en la tabla senalada')->nullable();
            $table->timestamp('fecha_hora')->comment('Fecha del movimiento')->default(DB::raw('now()'));
            $table->string('comentario')->comment('Comentario sobre la accion')->nullable();
            $table->string('ip_cliente',15)->comment('IP del cliente');
            $table->string('ip_servidor',15)->comment('IP del servidor');
            $table->string('dominio_servidor')->comment('Dominio del servidor');

            $table->foreign('fk_id_usuario')->references('id_usuario')->onDelete('restrict')->onUpdate('restrict')
                ->on(config('database.connections.corporativo.schema').'.ges_cat_usuarios');
            $table->foreign('fk_id_empresa')->references('id_empresa')->onDelete('restrict')->onUpdate('restrict')
                ->on(config('database.connections.corporativo.schema').'.gen_cat_empresas');
            $table->foreign('fk_id_accion')->references('id_accion')->onDelete('restrict')->onUpdate('restrict')
                ->on('log_accion');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('logs')
            ->dropIfExists('log_movimientos');
    }
}
