<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\HtmlString;
use Illuminate\Notifications\Notifiable;
use App\Notifications\Notificaciones;

class ModelBase extends Model
{
    use Notifiable;
	/**
	 * Los atributos que seran visibles en index-datable
	 * @var null|array
	 */
	protected $fields = [];
	
	/**
	 * Atributos de carga optimizada
	 * @var array
	 */
	protected $eagerLoaders = [];

	/**
	 * Indicates if the model should be timestamped.
	 *
	 * @var bool
	 */
	public $timestamps = false;

	public function __construct($attributes = []) {
		$this->eagerLoaders = $this->getAutoEager();
	}

	/**
	 * Nice names to validator
	 * @var array
	 */
	public $niceNames = [];
	
	public static function boot()
	{
	    return parent::boot();
	    /*
	    //mientras creamos
	    self::creating(function($table){
	    });
	    
        //una vez creado
        self::created(function($table){
        });
        
        //mientras actualizamos
        self::updating(function($table){
        });
	    
        //una vez actualizado
        self::updated(function($table){
        });
	    
	    //mientras salvamos
	    self::saving(function($table){
	    });
	    
	    //una vez salvado
        self::saved(function($table){
        });
	    
	    //mientras eliminamos
        self::deleting(function($table){
        });
	    
	    //una vez eliminado
        self::deleted(function($table){
        });
	    
	    //mientras restauramos
        self::restoring(function($table){
        });
	    
	    //una vez restaurado
        self::restored(function($table){
        });
        */
	}
	/*
	public function newQuery() {
	    if(in_array('eliminar',$this->getlistColumns())) {
	       return parent::newQuery()->whereEliminar(0);
	    }
	    return parent::newQuery();
	}*/
	
	/**
	 * Obtenemos atributos a incluir en append/appends()
	 * @return array
	 */
	public function getAppendableFields()
	{
		return array_where(array_diff(array_keys($this->getFields()), array_keys($this->getColumnsDefaultsValues())), function ($value, $key) {
			return !str_contains($value, '.');
		});
	}
	public function getAutoEager()
	{
	    $keysfields = array_keys($this->fields ?? []) ?? [];
	    $return = [];
	    
	    foreach ($keysfields as $key) {
	        $pos = strpos($key, '.');
	        if($pos !== false)
	            array_push($return,substr($key,0,$pos));
	    }
        return $return;
	}
	
	public function getFillable()
	{
	    return $this->fillable ?? [];
	}

	/**
	 * Obtenemos atributos para smart-datatable
	 * @return array
	 */
	public function getFields()
	{
		if (!$this->fields) {
			throw new \Exception('Undefined $fields in ' . class_basename($this) . ' model.');
		}
		return $this->fields;
	}
	
	public function sendNotification($email,$options)
	{
	    $this->email = $email;
	    $this->notify(new Notificaciones($options));
	}
	
	/**
	 * Obtenemos Eager-Loaders
	 * @return array
	 */
	public function getEagerLoaders()
	{
		return $this->eagerLoaders;
	}

	public function getDataAttributes($row) {
		return collect($this->dataColumns)->map(function ($name) use ($row) {
			return "data-$name=\"{$row->{$name}}\"";
		})->implode(' ');
	}

	/**
	 * Accessor - Obtenemos columna 'activo' formateado en texto
	 * @return string
	 */
	public function getActivoTextAttribute()
	{
	    return isset($this->activo) && $this->activo === true ? 'Activo' : 'Inactivo';
	}

	/**
	 * Accessor - Obtenemos columna 'activo' formateado en HTML
	 * @return string
	 */
	public function getActivoSpanAttribute()
	{
		# Retornamos HTML-Element
		$format = new HtmlString('<span class=' . ($this->activo ? 'toast_success' : 'toast_error') . ">&nbsp;$this->activo_text&nbsp;</span>");
		# Si Ajax, retornamos HTML-String
		if (request()->ajax()) {
			return $format->toHtml();
		}
		return $format;
	}
	
	public function getlistColumns() {
	    return $this->getConnection()->getSchemaBuilder()->getColumnListing(str_replace('maestro.','',$this->getTable()));
	}

	/**
	 * Obtenemos columnas-defaults de modelo
	 * @return array
	 */
	public function getColumnsDefaultsValues()
	{
		$columns = $this->getConnection()->getDoctrineSchemaManager()->listTableDetails($this->getTable())->getColumns();
		
		return array_map(function($column) {
			return $column->getDefault() == 'now()' ? date("Y-m-d H:i:s") : $column->getDefault();
		}, $columns );
	}

	/**
	 * Obtenemos informacion-base de modelo para las reglas
	 * @return array
	 */
	public function getRulesDefaults()
	{
	    $types = ['Integer'=>'Integer','Numeric'=>'Digits','Date'=>'Date','Time'=>'Sometimes'];
	    $columns = $this->getConnection()->getDoctrineSchemaManager()->listTableDetails($this->getTable())->getColumns();

	    $propertys = array_map(function($column) {
	        return [
	            'required' => $column->getNotnull(),
	            'type' => $column->getType(),
	            'length' => !empty($column->getLength()) ? $column->getLength() : $column->getPrecision(),
	            'decimal' => $column->getScale(),
	            'comment'  => $column->getComment(),
	        ];
	    }, $columns );
	        
        $rules = [];
        foreach($propertys as $col=>$prop) {
            if(in_array($col, $this->fillable))
            {
                $rules[$col] = [];
                $type = (string)$prop['type'];

                if($type == 'Boolean')
                {
                    array_push($rules[$col],'min:0');
                    array_push($rules[$col],'max:1');
                }
                elseif(in_array($type,['Integer','Decimal'])) {
                    array_push($rules[$col],'digits_between:0,'.$prop['length']);
                }
                elseif(!in_array($type,['Text','Date','DateTime'])) {
                    array_push($rules[$col],'max:'.$prop['length']);
                }

                if(isset($types[$type])) {
                    if($types[$type] == 'Digits') {
                        array_push($rules[$col],$types[$type].':'.$prop['decimal']);
                    }
                    else {
                        array_push($rules[$col],$types[$type]);
                    }
                }

                if($prop['required'] == true && $type != 'Boolean') {
                    array_push($rules[$col],'required');
                }

                if(!empty($prop['comment'])) {
                    array_push($rules[$col],$prop['comment']);
                }
            }
        }
        return $rules;
	}
	
	public function documento_destino($tipo = '0')
	{
	    
	    if($tipo !== '0')
	        return $this->morphMany('PedidosDetalle',null,'fk_id_tipo_documento_base','fk_id_linea');
	    else
	        return null;
	}
	
	public function documento_base()
	{
	    $tipo_documento = isset($this->fk_id_tipo_documento_base) ? $this->fk_id_tipo_documento_base : 0;
	    switch($tipo_documento)
	    {
	        case 4://Factura
	            return $this->belongsTo(FacturasClientesDetalle::class,'id_documento_detalle','fk_id_linea')->where('fk_id_tipo_documento',$tipo_documento);
	            break;
	        case 5://Crédito
	            return $this->belongsTo(NotasCreditoClientesDetalle::class,'id_documento_detalle','fk_id_linea')->where('fk_id_tipo_documento',$tipo_documento);
	            break;
	        case 6://Cargo
	            return $this->belongsTo(NotasCargoClientesDetalle::class,'id_documento_detalle','fk_id_linea')->where('fk_id_tipo_documento',$tipo_documento);
	            break;
	        default:
	            return null;
	            break;
	    }
	}
	/*
	public static function __callStatic($method, $parameters)
	{
	    return (new static)->$method(...$parameters);
	}*/
}