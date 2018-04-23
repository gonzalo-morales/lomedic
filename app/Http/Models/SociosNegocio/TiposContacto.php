<?php

namespace App\Http\Models\SociosNegocio;

use App\Http\Models\ModelBase;

class TiposContacto extends ModelBase
{
    /**
     * The table associated with the model.
     * @var string
     */
    protected $table = 'sng_cat_tipos_contacto';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_tipo_contacto';

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = ['tipo_contacto', 'activo'];
    
    protected $fields = [
        'tipo_contacto' => 'Tipo Contacto',
        'activo_text' => 'Estatus'
    ];

    /**
	 * The validation rules
	 * @var array
	 */
	public $rules = [
		'tipo_contacto' => ['required','max:60','regex:/^[a-zA-Z\s]+/']
	];

    protected $unique = ['tipo_contacto'];

    public function contactos(){
        return $this->hasOne(ContactosSociosNegocio::class);
    }
}