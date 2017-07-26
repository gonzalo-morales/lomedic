<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Models\Administracion\Empresas;
use App\Http\Models\AccionLog;

class Logs extends Model
{

    protected $connection = 'logs';

    protected $table = 'log_movimientos';

    protected $primaryKey = 'id_log';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['tabla','fk_id_usuario', 'fk_id_empresa', 'fk_id_accion','id_registro','fecha_hora','comentario'
        ,'ip_cliente','ip_servidor','dominio_servidor'];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public static function createLog($table,$created,$company)//Crear un registro en log cuando se agrega un registro a una tabla
    {//Creación de un registro
        Logs::create([
            'id_log' => '',
            'tabla' => $table,
            'fk_id_usuario' => Auth::id(),
            'fk_id_empresa' => Empresas::where('nombre_comercial',strtoupper($company))->first(['id_empresa'])->id_empresa,
            'fk_id_accion' =>1,
            'id_registro' => $created,
            'fecha_hora' => DB::raw('now()'),
            'comentario' => 'Registro insertado',
            'ip_cliente' => $_SERVER['REMOTE_ADDR'],
            'ip_servidor' => $_SERVER['SERVER_ADDR'],
            'dominio_servidor' => $_SERVER['SERVER_NAME']
        ]);
    }
    public static function editLog($table,$company,$id,$accion,$comentario)//Crear un registro en log cuando se modifica un registro a una tabla
    {//Editar, eliminar, mostrar e index




        Logs::create([
            'id_log' => '',
            'tabla' => $table,
            'fk_id_usuario' => Auth::id(),
            'fk_id_empresa' => Empresas::where('nombre_comercial',strtoupper($company))->first(['id_empresa'])->id_empresa,
            'fk_id_accion' =>AccionLog::where('accion',$accion)->first(['id_accion'])->id_accion,
            'id_registro' => $id,
            'fecha_hora' => DB::raw('now()'),
            'comentario' => $comentario,
            'ip_cliente' => $_SERVER['REMOTE_ADDR'],
            'ip_servidor' => $_SERVER['SERVER_ADDR'],
            'dominio_servidor' => $_SERVER['SERVER_NAME']
        ]);
    }
}
