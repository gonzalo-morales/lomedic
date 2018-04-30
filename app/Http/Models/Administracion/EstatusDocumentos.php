<?php

namespace App\Http\Models\Administracion;

use App\Http\Models\ModelBase;
use DB;
use Illuminate\Support\HtmlString;

class EstatusDocumentos extends ModelBase
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'gen_cat_estatus_documentos';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_estatus';
    
    protected $fillable = ['estatus','activo','class'];
    
    protected $fields = ['id_estatus'=>'#','estatus'=>'Nombre de Estatus','class_span'=>'Clases','activo_span' => 'Estatus'];
    
    public $rules = ['estatus'=>'required|max:50|regex:/^[a-zA-Záéíóúäëïöü\s]+/'];

    protected $unique = ['estatus'];

    public function tiposdocumentos()
    {
        return $this->belongsToMany(TiposDocumentos::class,'gen_det_estatus_tipo_documento','fk_id_estatus','fk_id_tipo_documento','id_estatus','id_tipo_documento');
    }

    public function getClassSpanAttribute()
    {
        $format = new HtmlString('<span class="p-1 '.$this->class.'">&nbsp;'.$this->class.'&nbsp;</span>');
        if(request()->ajax()){
            return $format->toHtml();
        }
        return $format;
    }
}