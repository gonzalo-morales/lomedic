<?php

namespace App\Http\Models\Soporte;

use Illuminate\Database\Eloquent\Model;

class ArchivosAdjuntos extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sop_det_archivos_adjuntos';

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
    protected $fillable = ['fk_id_solicitud','ruta_archivo'];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public function seguimiento()
    {
        return $this->belongsTo('App\Http\Models\Soporte\SeguimientoSolicitudes','fk_id_mensaje','id_seguimiento');
    }
}
