<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Models\Administracion\Empresas;

class Logs extends Model
{

    protected $connection = 'logs';

    protected $table = 'log_movimientos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['tabla','fk_id_usuario', 'fk_id_empresa', 'accion','id_registro','fecha_hora','comentario'
        ,'ip_cliente','ip_servidor','dominio_servidor'];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
    public $primaryKey = false;
    public $incrementing = false;

    public static function createLog($table,$created,$company)//Crear un registro en log cuando se agrega un registro a una tabla
    {
        Logs::create([
            'tabla' => $table,
            'fk_id_usuario' => Auth::id(),
            'fk_id_empresa' => Empresas::where('nombre_comercial',strtoupper($company))->first(['id_empresa'])->id_empresa,
            'accion' =>'crear',
            'id_registro' => $created,
            'fecha_hora' => DB::raw('now()'),
            'comentario' => 'Registro insertado',
            'ip_cliente' => $_SERVER['REMOTE_ADDR'],
            'ip_servidor' => $_SERVER['SERVER_ADDR'],
            'dominio_servidor' => $_SERVER['SERVER_NAME']
        ]);
    }
    public static function editLog($table,$company,$id)//Crear un registro en log cuando se modifica un registro a una tabla
    {
        Logs::create([
            'tabla' => $table,
            'fk_id_usuario' => Auth::id(),
            'fk_id_empresa' => Empresas::where('nombre_comercial',strtoupper($company))->first(['id_empresa'])->id_empresa,
            'accion' =>'editar',
            'id_registro' => $id,
            'fecha_hora' => DB::raw('now()'),
            'comentario' => 'Registro editado',
            'ip_cliente' => $_SERVER['REMOTE_ADDR'],
            'ip_servidor' => $_SERVER['SERVER_ADDR'],
            'dominio_servidor' => $_SERVER['SERVER_NAME']
        ]);
    }

    public static function deleteLog($table,$company,$id)//Crear un registro en log cuando se elimina un registro a una tabla
    {
        Logs::create([
            'tabla' => $table,
            'fk_id_usuario' => Auth::id(),
            'fk_id_empresa' => Empresas::where('nombre_comercial',strtoupper($company))->first(['id_empresa'])->id_empresa,
            'accion' =>'eliminar',
            'id_registro' => $id,
            'fecha_hora' => DB::raw('now()'),
            'comentario' => 'Registro eliminado',
            'ip_cliente' => $_SERVER['REMOTE_ADDR'],
            'ip_servidor' => $_SERVER['SERVER_ADDR'],
            'dominio_servidor' => $_SERVER['SERVER_NAME']
        ]);
    }
}
