<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::conection(config('database.conecction.corporativo.schema'))
            ->create('ges_cat_usuarios', function (Blueprint $table) {
            /*Principal fields*/
            $table->increments('id_usuario');
            $table->string('usuario','20')->unique()->comment('Nombre de usuario');
            $table->string('nombre_corto','100')->unique()->comment('Nombre corto de la persona');
            $table->integer('fk_id_empleado')->comment('ID de empleado recursos humanos')->unsigned()->nullable();/*Foreign key from 'empleado' left*/
            $table->integer('fk_id_empresa_default')->comment('ID de empresa default')->unsigned()->nullable();/*Foreign key from 'empresas' left*/
            /*$table->string('email')->unique();*/
            $table->string('password','60')->comment('Contrasena de acceso');
            $table->rememberToken();

            /*General fields*/
            $table->boolean('activo')->default('1');
            $table->boolean('eliminar')->default('0');
            
            /*Foreign keys*/
            $table->foreign('fk_id_empresa_default')->references('id_empresa')->on('gen_cat_empresas')->
            onDelete('restrict')->onUpdate('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ges_cat_usuarios');
    }
}
