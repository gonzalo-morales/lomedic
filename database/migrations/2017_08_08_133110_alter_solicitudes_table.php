<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterSolicitudesTable extends Migration
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
                $table->string('fecha_hora_creacion')->nullable()->default(DB::raw('CURRENT_TIMESTAMP(0)'));
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
                $table->dropColumn('fecha_hora_creacion');
        });
    }
}
