<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterOrdenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('abisa')
            ->table('com_opr_ordenes', function (Blueprint $table) {
                $table->boolean('importacion')->default('f');
        });
        Schema::connection('lomedic')
            ->table('com_opr_ordenes', function (Blueprint $table) {
                $table->boolean('importacion')->default('f');
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
            ->table('com_opr_ordenes', function (Blueprint $table) {
                $table->dropColumn('importacion');

            });

        Schema::connection('lomedic')
            ->table('com_opr_ordenes', function (Blueprint $table) {
                $table->dropColumn('importacion');
            });
    }
}
