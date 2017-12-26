<?php

namespace App\Http\Models\Inventarios;

use App\Http\Models\ModelBase;

class Ubicaciones extends ModelBase
{
    protected $table = 'maestro.inv_cat_ubicaciones';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_ubicacion';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['fk_id_almacen','rack', 'ubicacion', 'posicion', 'nivel', 'activo','eliminar'];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['nomenclatura'];

    public function getNomenclaturaAttribute()
    {
        return implode('-', [$this->rack, $this->ubicacion, $this->posicion, $this->nivel]);
    }
    #Tomamos los valores de almacenes
    public function almacen()
    {
        return $this->belongsTo(Almacenes::class,'fk_id_almacen');
    }

    public function stock()
    {
        return $this->hasMany(StockDetalle::class, 'fk_id_ubicacion', 'id_ubicacion');
    }


}