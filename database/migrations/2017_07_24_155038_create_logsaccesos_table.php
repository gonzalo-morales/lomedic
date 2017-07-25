<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogsaccesosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('logs')
            ->create('log_accesos', function (Blueprint $table) {
            $table->integer('fk_id_usuario')->comment('ID del usuario que intento loguearse; si existe');
            $table->string('nombre_usuario')->comment('Nombre del usuario que intento loguearse');
            $table->integer('fk_id_empresa')->comment('ID empresa del usuario; si existe');
            $table->string('modulo')->comment('Modulo al que se accedio');
            $table->boolean('acceso')->comment('Indica si el usuario logró accesar o no');
            $table->timestamp('fecha_hora')->comment('Fecha y hora del suceso')->default(DB::raw('now()'));
            $table->string('ip_cliente',15)->comment('IP del cliente');
            $table->string('ip_servidor',15)->comment('IP del servidor');
            $table->string('dominio_servidor')->comment('Dominio del servidor');

            $table->foreign('fk_id_usuario')->references('id_usuario')->onDelete('restrict')->onUpdate('restrict')
                ->on(config('database.connections.corporativo.schema').'.ges_cat_usuarios');
            $table->foreign('fk_id_empresa')->references('id_empresa')->onDelete('restrict')->onUpdate('restrict')
                ->on(config('database.connections.corporativo.schema').'.gen_cat_empresas');
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
            ->dropIfExists('log_accesos');
    }
}
