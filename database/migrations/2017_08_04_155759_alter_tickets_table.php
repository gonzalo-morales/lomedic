<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('corporativo')
            ->table('sop_opr_solicitudes', function (Blueprint $table) {
                $table->string('nombre_solicitante')->nullable();
                $table->integer('fk_id_departamento')->nullable();
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
            ->table('sop_opr_solicitudes', function (Blueprint $table) {
                $table->dropColumn('nombre_solicitante');
                $table->dropColumn('fk_id_departamento');
        });
    }
}
