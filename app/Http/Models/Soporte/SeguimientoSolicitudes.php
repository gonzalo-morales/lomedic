<?php

namespace App\Http\Models\Soporte;

use App\Http\Models\ModelBase;

class SeguimientoSolicitudes extends ModelBase
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sop_det_seguimiento_solicitudes';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_seguimiento';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['asunto','fk_id_solicitud','comentario','fk_id_empleado_comentario','fecha_hora'];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public $rules = [
        'asunto' => 'required|regex:/^([0-9a-zA-ZÃ±Ã‘Ã¡Ã©Ã­Ã³ÃºÃ�Ã‰Ã�Ã“Ãš_-])+((\s*)+([0-9a-zA-ZÃ±Ã‘Ã¡Ã©Ã­Ã³ÃºÃ�Ã‰Ã�Ã“Ãš_-]*)*)+$/|max:255',
        'comentario' => 'required|max:255'
    ];
    
    public function solicitud()
    {
        return $this->hasOne('App\Http\Models\Soporte\Solicitudes','id_solicitud', 'fk_id_solicitud');
    }

    public function empleado()
    {
        return $this->belongsTo('App\Http\Models\RecursosHumanos\Empleados','fk_id_empleado_comentario','id_empleado');
    }

    public function archivo_adjunto()
    {
        return $this->hasMany('App\Http\Models\Soporte\ArchivosAdjuntos','fk_id_mensaje');
    }
}
