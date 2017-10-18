<?php

namespace App\Http\Models\Administracion;

use App\Http\Models\ModelBase;


class ModulosAcciones extends ModelBase
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'adm_det_modulo_accion';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_modulo_accion';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['fk_id_modulo','fk_id_accion','fk_id_empresa','fk_id_empresa'];

    /**
     * Los atributos que seran visibles en index-datable
     * @var array
     */

    //    protected $fields = [
    //        'banco' => 'Banco',
    //        'rfc' => 'RFC',
    //        'razon_social' => 'Razón Social',
    //        'nacional' => 'Nacional'
    //    ];

    /**
     * The validation rules
     * @var array
     */
    public $rules = [
        'fk_id_modulo' => 'required',
        'fk_id_accion' => 'required',
        'fk_id_empresa' => 'required',
    ];

    public function empresas()
    {
        return $this->belongsTo('App\Http\Models\Administracion\Empresas','fk_id_empresa','id_empresa');
    }
    public function modulos()
    {
        return $this->belongsTo('App\Http\Models\Administracion\Modulos','fk_id_modulo','id_modulo');
    }
    public function acciones()
    {
        return $this->belongsTo('App\Http\Models\Administracion\Acciones','fk_id_accion','id_accion');
    }
//    public function numeroscuenta()
//    {
//        return $this->hasMany('App\Http\Models\Administracion\NumerosCuenta');
//    }
}
