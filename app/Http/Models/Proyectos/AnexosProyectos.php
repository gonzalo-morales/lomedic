<?php

namespace App\Http\Models\Proyectos;

use App\Http\Models\ModelCompany;

class AnexosProyectos extends ModelCompany
{
    /**
     * The table associated with the model.
     * @var string
     */
    protected $table = 'pry_det_anexos';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_anexo';

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = ['fk_id_proyecto', 'nombre', 'archivo', 'activo'];

    /**
     * Indicates if the model should be timestamped.
     * @var bool
     */
    public $timestamps = false;

    /**
     * The validation rules
     * @var array
     */
    public $rules = [];

    public function proyecto(){
        return $this->belongsTo(Proyectos::class,'fk_id_proyecto');
    }
}