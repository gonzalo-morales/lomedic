<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterRenameUsuarioSucursalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('corporativo')
            ->table('ges_det_usuario_sucursal', function (Blueprint $table) {
            $table->dropForeign('ges_det_usuario_sucursal_fk_id_usuario_foreign');
            $table->renameColumn('fk_id_usuario','fk_id_empleado');
            $table->foreign('fk_id_empleado')->references('id_empleado')
                ->onUpdate('restrict')->onDelete('restrict')
                ->on('rh_cat_empleados');
                $table->rename('ges_det_empleado_sucursal');
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
            ->table('ges_det_empleado_sucursal', function (Blueprint $table) {
                $table->dropforeign('ges_det_usuario_sucursal_fk_id_empleado_foreign');
                $table->renameColumn('fk_id_empleado','fk_id_usuario');
                $table->foreign('fk_id_usuario')->references('id_usuario')
                    ->onUpdate('restrict')->onDelete('restrict')
                    ->on('ges_cat_usuarios');
                $table->rename('ges_det_usuario_sucursal');
            });
    }
}
