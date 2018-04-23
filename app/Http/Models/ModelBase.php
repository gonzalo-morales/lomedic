<?php
namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\HtmlString;
use Illuminate\Notifications\Notifiable;
use App\Notifications\Notificaciones;
use Illuminate\Support\Facades\Auth;
use App\Http\Models\Administracion\Sucursales;

class ModelBase extends Model
{
    use Notifiable;
    
    protected $schema = null;

    protected $fillable = [];

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
	
	protected $unique = [];

	/**
	 * Indicates if the model should be timestamped.
	 *
	 * @var bool
	 */
	public $timestamps = false;
	
	public $rules = [];

	public function __construct($attributes = [])
	{
	    $this->schema = !empty($this->schema) ? $this->schema : getSchema($this->connection);
	    $this->table = $this->schema.'.'.$this->table;
	        
		$this->eagerLoaders = $this->getAutoEager();
		$this->rules = $this->getRulesDefaults();
		$this->addUniqueRules();
		return parent::__construct($attributes);
	}

	/**
	 * Nice names to validator
	 * @var array
	 */
	public $niceNames = [];

	public static function boot()
	{
	    parent::boot();
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

	public function newQuery() {
	    $parent = parent::newQuery();

	    if(in_array('eliminar',$this->getlistColumns())) {
	        $parent->newQuery()->where($this->getTable().'.eliminar',0);
	    }
	    /*
	    $route = \Route::getCurrentRoute()->getActionName();
	    $controller = substr($route,0,strpos($route,'@'));

	    if(in_array('activo',$this->getlistColumns()) && !empty($controller) && isset((new $controller)->entity) && get_class((new $controller)->entity) != get_class($this)) {
	        $model = (new $controller)->entity;
	        $parent->newQuery()->where($this->getTable().'.activo',1);
	    }*/

	    return $parent;
	}
	
	public function scopeIsActivo($query,$estatus=1) {
	    if(in_array('activo',$this->getlistColumns()) && !empty($this->{$this->primaryKey}))
	        $query->whereRaw("(activo = $estatus OR $this->primaryKey = $this->{$this->primaryKey})");
        elseif(in_array('activo',$this->getlistColumns()))
            $query->where('activo',$estatus);
	}
	
	public function scopeHasEmpresa($query,$empresa=[]) {
	    $id_empresa = !empty($empresa) ? $empresa : [dataCompany()->id_empresa];
	    
	    if(in_array('id_empresa',$this->getlistColumns()))
	        $query->whereIn('id_empresa',$id_empresa);
        
        if(in_array('fk_id_empresa',$this->getlistColumns()))
            $query->whereIn('fk_id_empresa',$id_empresa);
        
        if(isset($this->empresa))
            $query->whereHas('empresa', function($q) use ($id_empresa) {
                $q->whereIn('id_empresa', $id_empresa);
            });
        
        if(isset($this->empresas))
            $query->whereHas('empresas', function($q) use ($id_empresa) {
                $q->whereIn('id_empresa', $id_empresa);
            });
	}
	
	public function scopeHasUsuario($query,$usuario=[]) {
	    $id_usuario = !empty($usuario) ? $usuario : [Auth::Id()];
	    
	    if(in_array('id_usuario',$this->getlistColumns()))
	        $query->whereIn('id_usuario',$id_usuario);
	        
        if(in_array('fk_id_usuario',$this->getlistColumns()))
            $query->whereIn('fk_id_usuario',$id_usuario);
            
        if(isset($this->usuario))
            $query->whereHas('usuario', function($q) use ($id_usuario) {
                $q->whereIn('id_usuario', $id_usuario);
            });
                
        if(isset($this->usuarios))
            $query->whereHas('usuarios', function($q) use ($id_usuario) {
                $q->whereIn('id_usuario', $id_usuario);
            });
    }
    
    public function scopeHasSucursal($query,$sucursal=[]) {
        $id_sucursal = !empty($sucursal) ? $sucursal : Sucursales::select('id_sucursal')->isActivo()->hasEmpresa()->hasUsuario()->pluck('id_sucursal'); 
        
        if(in_array('id_sucursal',$this->getlistColumns()))
            $query->whereIn('id_sucursal',$id_sucursal);
            
        if(in_array('fk_id_sucursal',$this->getlistColumns()))
            $query->whereIn('fk_id_sucursal',$id_sucursal);
            
        if(isset($this->sucursal))
            $query->whereHas('sucursal', function($q) use ($id_sucursal) {
                $q->whereIn('id_sucursal', $id_sucursal);
            });
                
        if(isset($this->sucursales))
            $query->whereHas('sucursales', function($q) use ($id_sucursal) {
                $q->whereIn('id_sucursal', $id_sucursal);
            });
    }

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
	
	public function addUniqueRules()
	{
	    $schema = config('database.connections.'.request()->company.'.schema');
        $schema = !empty($schema) ? $schema : config('database.connections.'.request()->company.'.database');

	    $table = !strpos($this->getTable(), '.') ? $schema.'.'.$this->getTable() : substr($this->getTable(),strpos($this->getTable(), '.')+1,strlen($this->getTable()));
	    $key = $this->getKeyName();
	    $id = !empty(request()->{$key}) ? request()->{$key} : 'null';
	    
	    foreach ($this->unique as $col) {
	        if(in_array('eliminar',$this->getlistColumns())) {
	            $this->rules[$col] = (isset($this->rules[$col]) ? $this->rules[$col].'|' : ''). "unique:$table,$col,$id,$key,eliminar,0";
	        }
	        else {
	            $this->rules[$col] = (isset($this->rules[$col]) ? $this->rules[$col].'|' : '')."unique:$table,$col,$id,$key";
	        }
	    }
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
	    return isset($this->activo) && $this->activo == 1 ? 'Activo' : 'Inactivo';
	}

	public function getEstatuscfdiSpanAttribute()
    {
        $format = new HtmlString('<span class="p-1 '. ($this->estatuscfdi->id_estatus_cfdi == 1 ? 'alert alert-danger' : 'alert alert-success').'">&nbsp;'.$this->estatuscfdi->estatus.'&nbsp;</span>');
        if(request()->ajax()){
            return $format->toHtml();
        }
        return $format;
    }

    public function getEstatusAutorizacionSpanAttribute()
    {
        switch ($this->estatusautorizacion->id_estatus){
            case 1://Sin autorizacion
                $color = 'alert alert-light';
                break;
            case 2://Pendiente
                $color = 'alert alert-warning';
                break;
            case 3://Rechazada
                $color = 'alert alert-danger';
                break;
            case 4://Autorizada
                $color = 'alert alert-success';
                break;
            case 5://Cancelada
                $color = 'alert alert-danger';
                break;
            default:
                $color = '';
                break;
        }
        $format = new HtmlString('<span class="p-1 '.$color.'">&nbsp;'.$this->estatusautorizacion->estatus.'&nbsp;</span>');
        if(request()->ajax()){
            return $format->toHtml();
        }
        return $format;
    }

    public function getEstatusDocumentoSpanAttribute()
    {
        switch ($this->estatus->id_estatus){
            case 1://Abierto
                $color = 'alert alert-success';
                break;
            case 2://Cerrado
                $color = 'alert alert-secondary';
                break;
            case 3://Cancelado
                $color = 'alert alert-danger';
                break;
            default:
                $color = '';
                break;
        }
        $format = new HtmlString('<span class="p-1 '.$color.'">&nbsp;'.$this->estatus->estatus.'&nbsp;</span>');
        if(request()->ajax()){
            return $format->toHtml();
        }
        return $format;
    }

	/**
	 * Accessor - Obtenemos columna 'activo' formateado en HTML
	 * @return string
	 */
	public function getActivoSpanAttribute()
	{
		# Retornamos HTML-Element
		$format = new HtmlString('<span class="p-1 ' . ($this->activo ? 'alert alert-success' : 'alert alert-danger') . '">&nbsp;'.$this->activo_text.'&nbsp;</span>');
		# Si Ajax, retornamos HTML-String
		if (request()->ajax()) {
			return $format->toHtml();
		}
		return $format;
	}

	public function getlistColumns() {
	    return $this->getConnection()->getSchemaBuilder()->getColumnListing(str_replace($this->schema.'.','',$this->getTable()));
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
	    $types = ['Integer'=>'Integer','String'=>'String','Numeric'=>'Digits','Date'=>'Date','Time'=>'Sometimes'];
	    $columns = $this->getConnection()->getDoctrineSchemaManager()->listTableDetails($this->getTable())->getColumns();

	    $propertys = array_map(function($column) {
	        return [
	            'required' => $column->getNotnull(),
	            'type' => $column->getType(),
	            'length' => !empty($column->getLength()) ? $column->getLength() : $column->getPrecision(),
	            'decimal' => $column->getScale(),
				'comment'  => $column->getComment(),
				'pattern' => '[a-zA-ZñÑáéíóúÁÉÍÓÚ{0-9}\-\_\d\s]',
	        ];
	    }, $columns );

        $rules = [];
        foreach($propertys as $col=>$prop) {
            if(in_array($col, $this->fillable))
            {
                $rules[$col] = [];
                $type = (string)$prop['type'];

                if($prop['required'] == true && $type != 'Boolean') {
                    array_push($rules[$col],'required');
                }elseif ($prop['required'] == false){
                    array_push($rules[$col],'nullable');
                }

                if($type == 'Boolean')
                {
                    array_push($rules[$col],'min:0');
                    array_push($rules[$col],'max:1');
                }
                elseif(in_array($type,['Integer','SmallInt'])) {
                    array_push($rules[$col],'digits_between:0,'.$prop['length']);
                }
                elseif(in_array($type,['Decimal'])){
                    array_push($rules[$col],'regex:/^(\d{0,'.$prop['length'].'}(\.\d{0,'.$prop['decimal'].'})?)$/');
				}
				elseif(in_array($type,['String'])){
					array_push($rules[$col],'regex:/^'.$prop['pattern'].'*$/');
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

//                if(!empty($prop['comment'])) {
//                    array_push($rules[$col],$prop['comment']);
//                }
            }
        }
        foreach($rules as $col=>$rule) {
            $rules[$col] = \implode('|',$rule);
        }
        return $rules;
	}

	public function documentos_destino($tipo_doc)
	{
	    $map_tipos = map_tipos_documentos();
	    if(!empty($tipo_doc) && isset($map_tipos[$tipo_doc])) {
	        return $this->hasMany($map_tipos[$tipo_doc],$this->getKeyName(),'fk_id_linea')->where('fk_id_tipo_documento_base',$this->fk_id_tipo_documento);
	    }
	    
	    return null;
	}

	public function documento_base()
	{
        return $this->belongsTo((New $map_tipos_documentos[$tipo]),$this->getKeyName(),'fk_id_linea')->where('fk_id_tipo_documento_base',$this->fk_id_tipo_documento);
	}
}