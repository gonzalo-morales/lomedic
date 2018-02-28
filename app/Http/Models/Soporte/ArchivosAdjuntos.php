<?php

namespace App\Http\Models\Soporte;

use App\Http\Models\ModelBase;

class ArchivosAdjuntos extends ModelBase
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'maestro.sop_det_archivos_adjuntos';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_archivo_adjunto';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['fk_id_solicitud','ruta_archivo','nombre_archivo','fk_id_mensaje'];

    public function seguimiento()
    {
        return $this->belongsTo('App\Http\Models\Soporte\SeguimientoSolicitudes','fk_id_mensaje','id_seguimiento');
    }
}
