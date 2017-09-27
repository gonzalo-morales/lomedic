<?php

namespace App\Http\Models\SociosNegocio;

use Illuminate\Database\Eloquent\Model;

class ContactosSociosNegocio extends Model
{
    // use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sng_cat_contactos';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_contacto';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['fk_id_socio_negocio', 'fk_id_tipo_contacto', 'nombre', 'puesto', 'telefono_oficina', 'extension_oficina',
                'celular', 'activo'];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The validation rules
     * @var array
     */
    // public $rules = [
    // ];

    public function getTable(){
	    return $this->table;
    }

    public function sociosNegocio(){
        return $this->belongsTo('App\Http\Models\SociosNegocio\SociosNegocio','fk_id_socio_negocio');
    }

    public function tipoContacto(){
        return $this->hasOne('App\Http\Models\SociosNegocio\TiposSocioContacto');
    }

}
