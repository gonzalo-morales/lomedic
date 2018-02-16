<?php
namespace App\Http\Models\Soporte;

use App\Http\Models\ModelBase;

class ModosContacto extends ModelBase
{

    // use SoftDeletes;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sop_cat_modos_contacto';

    /**
     * The primary key of the table
     * 
     * @var string
     */
    protected $primaryKey = 'id_modo_contacto';

    protected $unique = ['modo_contacto'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'modo_contacto',
        'activo'
    ];

    /**
     * Los atributos que seran visibles en index-datable
     * 
     * @var array
     */
    protected $fields = [
        'modo_contacto' => 'Modo Contacto',
        'activo' => 'Activo'
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The validation rules
     * 
     * @var array
     */
    public $rules = [
        'modo_contacto' => 'required|regex:/^([0-9a-zA-ZÃ±Ã‘Ã¡Ã©Ã­Ã³ÃºÃ�Ã‰Ã�Ã“Ãš_-])+((\s*)+([0-9a-zA-ZÃ±Ã‘Ã¡Ã©Ã­Ã³ÃºÃ�Ã‰Ã�Ã“Ãš_-]*)*)+$/|max:255'
    ];

    public function solicitudes()
    {
        return $this->belongsToMany('App\Http\Models\Soporte\Solicitudes');
    }
}
