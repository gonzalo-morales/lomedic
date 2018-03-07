<?php
#use File;
use App\Http\Models\Administracion\FormasPago;
use App\Http\Models\Administracion\Monedas;
use App\Http\Models\Administracion\MetodosPago;
use App\Http\Models\Administracion\RegimenesFiscales;
use App\Http\Models\Administracion\TiposDocumentos;
use App\Http\Models\Administracion\Empresas;

/**
 * Obtenemos arreglo accion de ruta personalizada
 * @param  string $action - Acción por la que reemplazar
 * @return array
 */
function routeActionReplace($action = null)
{
	# RouteAction actual, ej: HomeController@index
	$current_action = str_replace('App\Http\Controllers\\', '', Route::currentRouteAction());
	#
	return array_map(function($current, $expected) {
		return $expected === '' ? $current : $expected;
	}, explode('@', $current_action), array_pad(explode('@', $action, 2), -2, '') );
}

function controllerByRoute()
{
    $controller_by_route = [];
    foreach (\Route::getRoutes() as $route)
    {
        $action = $route->action;
        if(isset($action['as']) && isset($action['controller']))
            $controller_by_route[$action['as']] = $action['controller'];
    }
    
    return $controller_by_route;
}

function cTrans($key, $dafault = null, $replace = [], $locale = null)
{
    $line = trans($key,$replace,$locale);
    
    if($line != $key)
        return utf8_encode($line);
    
    $line = !empty($dafault) ? utf8_encode($dafault) : $key;
    
    if (empty($replace))
        return $line;
    
    foreach ($replace as $key => $value) {
        $line = str_replace(
            [':'.$key, ':'.strtoupper($key), ':'.ucfirst($key)],
            [$value, strtoupper($value), ucfirst($value)],
            $line
        );
    }
    
    return $line;
}

/**
 * Obtenemos URL de accion
 * @param  string $action - Acción por la que reemplazar
 * @param  array  $params - Parametros personalizados
 * @return string
 */
function companyAction($action = '', $params = [])
{
	#
	$expected_action = routeActionReplace($action);

	# Injectamos empresa
	$autoparams = ['company' => request()->company];

	# Injectamos Id
	if (in_array($expected_action[1], ['show', 'edit', 'update', 'destroy','impress'])) {
		# Obtenemos parametros, omitiendo empresa
		$_params = array_except(request()->route()->parameters, ['company']);
		if (!empty($_params)) {
			# Asiganmos parametro, que por logica debe ser el Id
			$autoparams['id'] =  head($_params);
		}
	}

	try {
		# Generamos URL
		return action(implode('@', $expected_action), array_merge($autoparams, $params));
	} catch (Exception $e) {
		return '#';
	}
}
function smart($route = null) {
    $route = !empty($route) ? $route : Route::currentRouteName();
    return 'layouts.smart'.substr($route,strrpos($route,'.'),strlen($route));
}

function ApiAction($action = '')
{
    return companyAction('HomeController@index')."/$action/api";
}

/**
 * Obtenemos accion de ruta actual
 * @param  string $action - Acción por la que reemplazar
 * @return string
 */
function currentRouteAction($action = '')
{
	return implode('@', routeActionReplace($action));
}

/**
 * Obtenemos arreglo de nombres de ruta actual
 * @param  string $route - nombre por el que reemplazar
 * @return array
 */
function routeNameReplace($route = '')
{
	$routeName = explode('.', Route::currentRouteName());
	$countRouteName = count($routeName);
	return array_map(function($current, $expected) {
		return $expected === '' ? $current : $expected;
	}, $routeName, array_pad(explode('.', $route, $countRouteName), 0 - $countRouteName, '') );
}

function dataCompany()
{
    return Empresas::where('activo',1)->where('conexion', '=', request()->company)->first();
}

/**
 * Obtenemos URL de ruta
 * @param  string $route - Acción por la que reemplazar
 * @param  array  $params - Parametros personalizados
 * @return string
 */
function companyRoute($route = '', $params = [], $replace = true)
{
	#
	$expected_action = $replace ? routeNameReplace($route) : explode('.', $route);

	# Injectamos empresa
	$autoparams = ['company' => request()->company];

	# Injectamos Id
	if (in_array(last($expected_action), ['show', 'edit', 'update', 'destroy','impress'])) {
		# Obtenemos parametros, omitiendo empresa
		$_params = array_except(request()->route()->parameters, ['company']);
		if (!empty($_params)) {
			# Asiganmos parametro, que por logica debe ser el Id
			$autoparams['id'] =  head($_params);
		}
	}

	# Generamos URL
	return route(implode('.', $expected_action), array_merge(
		$autoparams, $params
	));
}

/**
 * Obtenemos nombre de ruta actual
 * @param  string $route - nombre por el que reemplazar
 * @return string
 */
function currentRouteName($route = '')
{
	return implode('.', routeNameReplace($route));
}

/**
 * ¿Es equivalente a la ruta actual?
 * @param  string  $route - Nombre de la ruta
 * @return boolean
 */
function isCurrentRouteName($route = '')
{
    return Route::currentRouteNamed(currentRouteName($route));
}

/**
 * Obtenemos entidad
 * @return Entity
 */
function currentEntity()
{
	return Route::getCurrentRoute()->getController()->entity;
}

/**
 * Obtenemos nombre completo de la entidad
 * @return string
 */
function currentEntityName()
{
	return get_class(currentEntity());
}

/**
 * Obtenemos nombre base de la entidad
 * @return string
 */
function currentEntityBaseName()
{
	return class_basename(currentEntityName());
}

/**
 * Obtenemos nombre llave para cache
 * @return string
 */
function getCacheKey($route = '', $withPage = true)
{
	$keys = [request()->company, currentRouteName($route)];
	if (request()->page && $withPage) $keys[] = request()->page;
	return implode('.', $keys);
}

/**
 * Obtenemos etiqueta para cache
 * @return string
 */
function getCacheTag($route = '') 
{
	return getCacheKey($route, false);
}

function map_tipos_documentos()
{
    $tipos_documentos = [];
    
    foreach(File::allFiles(app_path().'/Http/Models') as $route) {
        if(preg_match("/^.*.php$/", $route->getPathname())){
            $smodel = substr(str_replace([base_path().'\a','/'],['A','\\'],$route->getPathname()),0,-4);
            
            $tipo = TiposDocumentos::where('tabla',(new $smodel)->getTable())->first();
            
            
            
            if(!empty($tipo)) {
                $tipos_documentos[$tipo->id_tipo_documento] = $smodel;
            }
            
            
        }
    }
    return $tipos_documentos;
}

function array_merge_recursive_simple($paArray1, $paArray2)
{
    if (!is_array($paArray1) or !is_array($paArray2)) { return $paArray2; }
    foreach ($paArray2 AS $sKey2 => $sValue2)
    {
        if(!isset($paArray1[$sKey2]))
            $paArray1[$sKey2] = $sValue2;

        if(!is_array($paArray1[$sKey2]) && !is_array($paArray2[$sKey2]) &&isset($paArray1[$sKey2]))
        {
            if($paArray1[$sKey2] != array_merge_recursive_simple(@$paArray1[$sKey2], $sValue2))
                $paArray1[$sKey2] = $paArray1[$sKey2].' '.array_merge_recursive_simple(@$paArray1[$sKey2], $sValue2);
            else
                $paArray1[$sKey2] = $paArray1[$sKey2];
        }
        else
            $paArray1[$sKey2] = array_merge_recursive_simple(@$paArray1[$sKey2], $sValue2);
    }
    return $paArray1;
}

function wsdlService($function = '',$params = [],$connections = null)
{    
    $config = config('wsdl.connections.'.($connections ?? config('wsdl.default')));
    $call = $function ?? $config['function'];
    
    try {
        $client = new SoapClient($config['url'], $config['options'] ?? []);
        $response = $client->__soapCall($call, ['parameters' => ($config['parameters']??[])+$params]);
    }catch(SoapFault $f){
        return collect(['status'=>$f->faultcode,'mensaje'=>"SOAPFault: ".$f->faultcode." - ".$f->faultstring]);
    }
    
    return $response->return;
}

function num2letras($num, $fem = false, $dec = true,$moneda = 'pesos',$abreviaturaMoneda = 'M.N.') {

	//En caso de que no esté formateado
	if(!strpos($num,',')){
		$num = number_format($num,'2','.','');
	}

	$matuni[2]  = "dos";
	$matuni[3]  = "tres";
	$matuni[4]  = "cuatro";
	$matuni[5]  = "cinco";
	$matuni[6]  = "seis";
	$matuni[7]  = "siete";
	$matuni[8]  = "ocho";
	$matuni[9]  = "nueve";
	$matuni[10] = "diez";
	$matuni[11] = "once";
	$matuni[12] = "doce";
	$matuni[13] = "trece";
	$matuni[14] = "catorce";
	$matuni[15] = "quince";
	$matuni[16] = "dieciseis";
	$matuni[17] = "diecisiete";
	$matuni[18] = "dieciocho";
	$matuni[19] = "diecinueve";
	$matuni[20] = "veinte";
	$matunisub[2] = "dos";
	$matunisub[3] = "tres";
	$matunisub[4] = "cuatro";
	$matunisub[5] = "quin";
	$matunisub[6] = "seis";
	$matunisub[7] = "sete";
	$matunisub[8] = "ocho";
	$matunisub[9] = "nove";

	$matdec[2] = "veint";
	$matdec[3] = "treinta";
	$matdec[4] = "cuarenta";
	$matdec[5] = "cincuenta";
	$matdec[6] = "sesenta";
	$matdec[7] = "setenta";
	$matdec[8] = "ochenta";
	$matdec[9] = "noventa";
	$matsub[3]  = 'mill';
	$matsub[5]  = 'bill';
	$matsub[7]  = 'mill';
	$matsub[9]  = 'trill';
	$matsub[11] = 'mill';
	$matsub[13] = 'bill';
	$matsub[15] = 'mill';
	$matmil[4]  = 'millones';
	$matmil[6]  = 'billones';
	$matmil[7]  = 'de billones';
	$matmil[8]  = 'millones de billones';
	$matmil[10] = 'trillones';
	$matmil[11] = 'de trillones';
	$matmil[12] = 'millones de trillones';
	$matmil[13] = 'de trillones';
	$matmil[14] = 'billones de trillones';
	$matmil[15] = 'de billones de trillones';
	$matmil[16] = 'millones de billones de trillones';

	//Zi hack
	$float=explode('.',$num);
	$num=$float[0];

	$num = trim((string)@$num);
	if ($num[0] == '-') {
		$neg = 'menos ';
		$num = substr($num, 1);
	}else
	$neg = '';
	while ($num[0] == '0') $num = substr($num, 1);
	if ($num[0] < '1' or $num[0] > 9) $num = '0' . $num;
	$zeros = true;
	$punt = false;
	$ent = '';
	$fra = '';
	for ($c = 0; $c < strlen($num); $c++) {
		$n = $num[$c];
		if (! (strpos(".,'''", $n) === false)) {
			if ($punt) break;
			else{
				$punt = true;
				continue;
			}

		}elseif (! (strpos('0123456789', $n) === false)) {
			if ($punt) {
				if ($n != '0') $zeros = false;
				$fra .= $n;
			}else

			$ent .= $n;
		}else

		break;

	}
	$ent = '     ' . $ent;
	if ($dec and $fra and ! $zeros) {
		$fin = ' coma';
		for ($n = 0; $n < strlen($fra); $n++) {
			if (($s = $fra[$n]) == '0')
				$fin .= ' cero';
			elseif ($s == '1')
				$fin .= $fem ? ' una' : ' un';
			else
				$fin .= ' ' . $matuni[$s];
		}
	}else
	$fin = '';
	if ((int)$ent === 0) return 'Cero ' . $fin;
	$tex = '';
	$sub = 0;
	$mils = 0;
	$neutro = false;
	while ( ($num = substr($ent, -3)) != '   ') {
		$ent = substr($ent, 0, -3);
		if (++$sub < 3 and $fem) {
			$matuni[1] = 'una';
			$subcent = 'as';
		}else{
			$matuni[1] = $neutro ? 'un' : 'uno';
			$subcent = 'os';
		}
		$t = '';
		$n2 = substr($num, 1);
		if ($n2 == '00') {
		}elseif ($n2 < 21)
		$t = ' ' . $matuni[(int)$n2];
		elseif ($n2 < 30) {
			$n3 = $num[2];
			if ($n3 != 0) $t = 'i' . $matuni[$n3];
			$n2 = $num[1];
			$t = ' ' . $matdec[$n2] . $t;
		}else{
			$n3 = $num[2];
			if ($n3 != 0) $t = ' y ' . $matuni[$n3];
			$n2 = $num[1];
			$t = ' ' . $matdec[$n2] . $t;
		}
		$n = $num[0];
		if ($n == 1) {
			$t = ' ciento' . $t;
		}elseif ($n == 5){
			$t = ' ' . $matunisub[$n] . 'ient' . $subcent . $t;
		}elseif ($n != 0){
			$t = ' ' . $matunisub[$n] . 'cient' . $subcent . $t;
		}
		if ($sub == 1) {
		}elseif (! isset($matsub[$sub])) {
			if ($num == 1) {
				$t = ' mil';
			}elseif ($num > 1){
				$t .= ' mil';
			}
		}elseif ($num == 1) {
			$t .= ' ' . $matsub[$sub] . '?n';
		}elseif ($num > 1){
			$t .= ' ' . $matsub[$sub] . 'ones';
		}
		if ($num == '000') $mils ++;
		elseif ($mils != 0) {
			if (isset($matmil[$sub])) $t .= ' ' . $matmil[$sub];
			$mils = 0;
		}
		$neutro = true;
		$tex = $t . $tex;
	}
	$tex = $neg . substr($tex, 1) . $fin;
	//Zi hack --> return ucfirst($tex);
	$end_num=ucfirst($tex)." $moneda ".$float[1].'/100 '. $abreviaturaMoneda;
	return $end_num;
}

function xmlToArray($xml, $options = array()) {
    $defaults = array(
        'namespaceSeparator' => ':',//you may want this to be something other than a colon
        'attributePrefix' => '@',   //to distinguish between attributes and nodes with the same name
        'alwaysArray' => array(),   //array of xml tag names which should always become arrays
        'autoArray' => true,        //only create arrays for tags which appear more than once
        'textContent' => '$',       //key used for the text content of elements
        'autoText' => true,         //skip textContent key if node has no attributes or child nodes
        'keySearch' => false,       //optional search and replace on tag and attribute names
        'keyReplace' => false       //replace values for above search values (as passed to str_replace())
    );
    $options = array_merge($defaults, $options);
    $namespaces = $xml->getDocNamespaces();
    $namespaces['tfd'] = 'http://www.sat.gob.mx/TimbreFiscalDigital';
    $namespaces[''] = null; //add base (empty) namespace

    //get attributes from all namespaces
    $attributesArray = array();
    foreach ($namespaces as $prefix => $namespace) {
        foreach ($xml->attributes($namespace) as $attributeName => $attribute) {
            //replace characters in attribute name
            if ($options['keySearch']) $attributeName =
                str_replace($options['keySearch'], $options['keyReplace'], $attributeName);
            $attributeKey = $options['attributePrefix']
                . ($prefix ? $prefix . $options['namespaceSeparator'] : '')
                . $attributeName;
            $attributesArray[$attributeKey] = (string)$attribute;
        }
    }

    //get child nodes from all namespaces
    $tagsArray = array();
    foreach ($namespaces as $prefix => $namespace) {
        foreach ($xml->children($namespace) as $childXml) {
            //recurse into child nodes
            $childArray = xmlToArray($childXml, $options);
            list($childTagName, $childProperties) = each($childArray);

            //replace characters in tag name
            if ($options['keySearch']) $childTagName =
                str_replace($options['keySearch'], $options['keyReplace'], $childTagName);
            //add namespace prefix, if any
            if ($prefix) $childTagName = $prefix . $options['namespaceSeparator'] . $childTagName;

            if (!isset($tagsArray[$childTagName])) {
                //only entry with this key
                //test if tags of this type should always be arrays, no matter the element count
                $tagsArray[$childTagName] =
                    in_array($childTagName, $options['alwaysArray']) || !$options['autoArray']
                        ? array($childProperties) : $childProperties;
            } elseif (
                is_array($tagsArray[$childTagName]) && array_keys($tagsArray[$childTagName])
                === range(0, count($tagsArray[$childTagName]) - 1)
            ) {
                //key already exists and is integer indexed array
                $tagsArray[$childTagName][] = $childProperties;
            } else {
                //key exists so convert to integer indexed array with previous value in position 0
                $tagsArray[$childTagName] = array($tagsArray[$childTagName], $childProperties);
            }
        }
    }
    //get text content of node
    $textContentArray = array();
    $plainText = trim((string)$xml);
    if ($plainText !== '') $textContentArray[$options['textContent']] = $plainText;

    //stick it all together
    $propertiesArray = !$options['autoText'] || $attributesArray || $tagsArray || ($plainText === '')
        ? array_merge($attributesArray, $tagsArray, $textContentArray) : $plainText;

    //return node as array
    return array(
        $xml->getName() => $propertiesArray
    );
}

function validarRequerimientosCFDI($arrayData,$fk_id_socio_negocio,$company,$tipo_comprobante){
//Función comprobar(arrayData,id_socio_negocio,company,tipo de comprobante I= ingreso y E= egreso )
    $version = "";
    $uuid = "";
    $relacionados = [];
    $mensaje = '';
    $detalles = array();
    //Para comprobar la versión y saber como obtener los datos de la factura
    if(isset($arrayData['Comprobante']['@version'])){
        $version = $arrayData['Comprobante']['@version'];
    }else if(isset($arrayData['Comprobante']['@Version'])){
        $version = $arrayData['Comprobante']['@Version'];
    }
    if($version == "3.3") {
        if (!isset($arrayData['Comprobante']['@Fecha'])) {
            $mensaje .= "\n-Verifica que el CFDI tenga una fecha";
        }
        if (is_null(FormasPago::where('forma_pago', 'LIKE', $arrayData['Comprobante']['@FormaPago'])->first())) {
            $mensaje .= "\n-Verifica que la forma de pago exista";
        }
        if (!isset($arrayData['Comprobante']['@NoCertificado']) || !isset($arrayData['Comprobante']['@Certificado'])) {
            $mensaje .= "\n-Verifica que exista un certificado";
        }
        if (is_null(Monedas::where('moneda', 'LIKE', $arrayData['Comprobante']['@Moneda'])->first())) {
            $mensaje .= "\n-Verifica que exista la moneda utilizada";
        }
        if ($arrayData['Comprobante']['@TipoDeComprobante'] != $tipo_comprobante || ($tipo_comprobante != 'I' && $tipo_comprobante != 'E')) {
            if ($tipo_comprobante == 'E') {
                $tipo_comprobante = 'egreso (E)';
            } else if ($tipo_comprobante == "I") {
                $tipo_comprobante = 'ingreso (I)';
            }
            $mensaje .= "\n-Verifica que el tipo de comprobante sea $tipo_comprobante";
        }
        if (is_null(MetodosPago::where('metodo_pago', 'LIKE', $arrayData['Comprobante']['@MetodoPago'])->first())) {
            $mensaje .= "\n-Verifica que exista el método de pago";
        }
        if (!isset($arrayData['Comprobante']['@Sello'])) {
            $mensaje .= "\n-No se encontró el sello en la factura";
        }
//        if($arrayData['Comprobante']['cfdi:Emisor']['@Rfc'] != $rfc_emisor){
//            $mensaje .= "\n-Por favor verifica que el proveedor seleccionado sea correcto";
//        }
        if(is_null(RegimenesFiscales::where('id_regimen_fiscal',$arrayData['Comprobante']['cfdi:Emisor']['@RegimenFiscal'])->first())){
            $mensaje .= "\n-Por favor verifica que el régimen fiscal del proovedor exista";
        }
//        if($arrayData['Comprobante']['cfdi:Receptor']['@Rfc'] != $rfc_receptor){
//            $mensaje .= "\n-Por favor verifica que la empresa activa sea la misma del XML";
//        }
        if (!isset($arrayData['Comprobante']['cfdi:Complemento']['tfd:TimbreFiscalDigital'])) {
            $mensaje .= "\n-No se encontró el timbre en la factura";
        } else {
            $uuid = $arrayData['Comprobante']['cfdi:Complemento']['tfd:TimbreFiscalDigital']['@UUID'];
        }

        if(isset($arrayData['Comprobante']['cfdi:CfdiRelacionados'])){
            if(isset($arrayData['Comprobante']['cfdi:CfdiRelacionados'][0])) {
                foreach ($arrayData['Comprobante']['cfdi:CfdiRelacionados'] as $relacionado) {
                    $tipo_relacion = \App\Http\Models\Administracion\TiposRelacionesCfdi::where('tipo_relacion',$relacionado['@TipoRelacion'])->first();
                    $relacionados[] =
                        $relacionado +
                        ["id_sat_tipo_relacion"=>$tipo_relacion->id_sat_tipo_relacion] +
                        ["descripcion"=>$tipo_relacion->descripcion];
                }
            }else{
                $tipo_relacion = \App\Http\Models\Administracion\TiposRelacionesCfdi::where('tipo_relacion',$arrayData['Comprobante']['cfdi:CfdiRelacionados']['@TipoRelacion'])->first();
                $relacionados[0] = $arrayData['Comprobante']['cfdi:CfdiRelacionados'];
                $relacionados[0] += ["id_sat_tipo_relacion"=>$tipo_relacion->id_sat_tipo_relacion]+
                $relacionados[0] += ["descripcion"=>$tipo_relacion->descripcion];
            }
        }

        if (!empty($mensaje)) {
            return response()->json([
                'estatus' => -2,
                'resultado' => $mensaje
            ]);
        } else {
            $collection = collect($arrayData);
            //Esta comprobación se hace debido a la forma en que la función xmlToArray trabaja
            //Si tiene muchos conceptos
            if (isset($collection['Comprobante']['cfdi:Conceptos']['cfdi:Concepto'][0])) {
                foreach ($collection['Comprobante']['cfdi:Conceptos']['cfdi:Concepto'] as $concepto) {
                    $idclaveprodserv = \App\Http\Models\Administracion\ClavesProductosServicios::where('clave_producto_servicio', 'LIKE', $concepto['@ClaveProdServ'])->first();
                    $idclaveunidad = \App\Http\Models\Administracion\ClavesUnidades::where('clave_unidad', 'LIKE', $concepto['@ClaveUnidad'])->first();
                    $tasaocuota = isset($concepto['cfdi:Impuestos']['cfdi:Traslados']['cfdi:Traslado']['@TasaOCuota']) ? $concepto['cfdi:Impuestos']['cfdi:Traslados']['cfdi:Traslado']['@TasaOCuota'] : null;
                    $idimpuesto = \App\Http\Models\Administracion\Impuestos::where('numero_impuesto', 'LIKE', $concepto['cfdi:Impuestos']['cfdi:Traslados']['cfdi:Traslado']['@Impuesto'])
                        ->where('tipo_factor', 'LIKE', $concepto['cfdi:Impuestos']['cfdi:Traslados']['cfdi:Traslado']['@TipoFactor'])
                        ->where('tasa_o_cuota', $tasaocuota)
                        ->first();
                    $detalles[] = [
                        'ClaveProdServ' => $concepto['@ClaveProdServ'],
                        'IdClaveProdServ' => !is_null($idclaveprodserv) ? $idclaveprodserv->id_clave_producto_servicio : 'Producto o servicio no encontrado',
                        'Cantidad' => $concepto['@Cantidad'],
                        'ClaveUnidad' => $concepto['@ClaveUnidad'],
                        'IdClaveUnidad' => !is_null($idclaveunidad) ? $idclaveunidad->id_clave_unidad : 'Clave no encontrada',
                        'Descripcion' => $concepto['@Descripcion'],
                        'ValorUnitario' => $concepto['@ValorUnitario'],
                        'Importe' => $concepto['@Importe'],
                        'Descuento' => $concepto['@Descuento'],
                        'Importe_impuesto' => isset($concepto['cfdi:Impuestos']['cfdi:Traslados']['cfdi:Traslado']['@Importe']) ? $concepto['cfdi:Impuestos']['cfdi:Traslados']['cfdi:Traslado']['@Importe'] : null,
                        'IdImpuesto' => !is_null($idimpuesto) ? $idimpuesto->id_impuesto : 'Impuesto no encontrado'
                    ];
                }
            } elseif (isset($collection['Comprobante']['cfdi:Conceptos']['cfdi:Concepto'])) {
                $detalles[0] = [];
                foreach ($collection['Comprobante']['cfdi:Conceptos']['cfdi:Concepto'] as $key => $value) {
                    switch ($key) {
                        case "@ClaveProdServ":
                            $idclaveprodserv = \App\Http\Models\Administracion\ClavesProductosServicios::where('clave_producto_servicio', 'LIKE', $value)->first();
                            if (!is_null($idclaveprodserv)) {
                                $detalles[0] = array_merge($detalles[0], ['IdClaveProdServ' => $idclaveprodserv->id_clave_producto_servicio]);
                            } else {
                                $detalles[0] = array_merge($detalles[0], ['IdClaveProdServ' => null]);
                            }
                            $detalles[0] = array_merge($detalles[0], [str_replace("@", '', $key) => $value]);
                            break;
                        case "@ClaveUnidad":
                            $idclaveunidad = \App\Http\Models\Administracion\ClavesUnidades::where('clave_unidad', 'LIKE', $value)->first();
                            if (!is_null($idclaveunidad)) {
                                $detalles[0] = array_merge($detalles[0], ['IdClaveUnidad' => $idclaveunidad->id_clave_unidad]);
                            } else {
                                $detalles[0] = array_merge($detalles[0], ['IdClaveUnidad' => null]);
                            }
                            $detalles[0] = array_merge($detalles[0], [str_replace("@", '', $key) => $value]);
                            break;
                        case "cfdi:Impuestos":
                            foreach ($value['cfdi:Traslados'] as $llave => $valor) {
                                $tasaocuota = isset($valor['@TasaOCuota']) ? $valor['@TasaOCuota'] : null;
                                $idimpuesto = \App\Http\Models\Administracion\Impuestos::where('numero_impuesto', 'LIKE', $valor['@Impuesto'])
                                    ->where('tipo_factor', 'LIKE', $valor['@TipoFactor'])
                                    ->where('tasa_o_cuota', $tasaocuota)
                                    ->first();
                                if (!is_null($idimpuesto)) {
                                    $detalles[0] = array_merge($detalles[0], ['IdImpuesto' => $idimpuesto->id_impuesto]);
                                } else {
                                    $detalles[0] = array_merge($detalles[0], ['IdImpuesto' => null]);
                                }
                                $detalles[0] = array_merge($detalles[0], ['Importe_impuesto' => $valor['@Importe']]);
                            }
                            break;
                        case "@Unidad":
                            //do nothing
                            break;
                        default:
                            $detalles[0] = array_merge($detalles[0], [str_replace("@", '', $key) => $value]);
                            break;
                    }
                }
            } else {
                return response()->json([
                    'estatus' => -2,
                    'resultado' => 'Este CFDI no contiene productos',
                ]);
            }
        }
    }else if($version == "3.2"){//Método de pago en 3.2 es Forma de pago y viceversa
        if (empty($arrayData['Comprobante']['@fecha'])) {
            $mensaje .= "\n-Verifica que el CFDI tenga una fecha";
        }
        if (is_null(MetodosPago::whereRaw('to_ascii(descripcion) ILIKE to_ascii(\''.$arrayData['Comprobante']['@formaDePago'].'\')')->first())) {
            $mensaje .= "\n-Verifica que la forma de pago exista";
        }
        if (empty($arrayData['Comprobante']['@noCertificado']) || empty($arrayData['Comprobante']['@certificado'])) {
            $mensaje .= "\n-Verifica que exista un certificado";
        }
        if (is_null(Monedas::whereRaw('to_ascii(moneda) ILIKE to_ascii(\''.$arrayData['Comprobante']['@Moneda'].'\')')->orWhereRaw('to_ascii(descripcion) ILIKE to_ascii(\''.$arrayData['Comprobante']['@Moneda'].'\')')->first())) {
            $mensaje .= "\n-Verifica que exista la moneda utilizada";
        }
        //Para verificar el tipo de comprobante
        if($tipo_comprobante == 'I'){
            $tipo_comprobante = 'ingreso';
        }elseif ($tipo_comprobante == 'E'){
            $tipo_comprobante = 'egreso';
        }
        if ($arrayData['Comprobante']['@tipoDeComprobante'] != $tipo_comprobante || ($tipo_comprobante != 'ingreso' && $tipo_comprobante != 'egreso')) {
            if ($tipo_comprobante == 'egreso') {
                $tipo_comprobante = 'egreso';
            } else if ($tipo_comprobante == "ingreso") {
                $tipo_comprobante = 'ingreso';
            }
            $mensaje .= "\n-Verifica que el tipo de comprobante sea $tipo_comprobante";
        }
        if (is_null(FormasPago::whereRaw('to_ascii(forma_pago) ILIKE to_ascii(\''.$arrayData['Comprobante']['@metodoDePago'].'\')')->first())) {
            $mensaje .= "\n-Verifica que la forma de pago exista";
        }
        if (!isset($arrayData['Comprobante']['@sello'])) {
            $mensaje .= "\n-No se encontró el sello en la factura";
        }
//        if($arrayData['Comprobante']['cfdi:Emisor']['@rfc'] != $rfc_emisor){
//            $mensaje .= "\n-Por favor verifica que el proveedor seleccionado sea correcto";
//        }
//        if(!isset($arrayData['Comprobante']['cfdi:Emisor']['cfdi:RegimenFiscal']['@Regimen'])){
//            $mensaje .= "\n-No hay un régimen fiscal del emisor";
//        }
//        if($arrayData['Comprobante']['cfdi:Receptor']['@rfc'] != $rfc_receptor){
//            $mensaje .= "\n-Por favor verifica que la empresa activa sea la misma del XML";
//        }
        if (!isset($arrayData['Comprobante']['Complemento']['TimbreFiscalDigital']) && !isset($arrayData['Comprobante']['cfdi:Complemento']['tfd:TimbreFiscalDigital'])) {
            $mensaje .= "\n-No se encontró el timbre en la factura";
        } else {
            $uuid = $arrayData['Comprobante']['cfdi:Complemento']['tfd:TimbreFiscalDigital']['@UUID'] ?? $arrayData['Comprobante']['Complemento']['TimbreFiscalDigital']['@UUID'] ;
        }

        if (!empty($mensaje)) {
            return response()->json([
                'estatus' => -2,
                'resultado' => $mensaje
            ]);
        }else{
            $collection = collect($arrayData);
            //Esta comprobación se hace debido a la forma en que la función xmlToArray trabaja
            //Si tiene muchos conceptos
            if (isset($collection['Comprobante']['cfdi:Conceptos']['cfdi:Concepto'][0]) || isset($collection['Comprobante']['Conceptos']['Concepto'][0])) {
                foreach ($collection['Comprobante']['cfdi:Conceptos']['cfdi:Concepto'] ?? $collection['Comprobante']['Conceptos']['Concepto'] as $concepto) {
                    $detalles[] = [
                        'Cantidad' => $concepto['@cantidad'],
                        'Unidad' => $concepto['@unidad'],
                        'Descripcion' => $concepto['@descripcion'],
                        'ValorUnitario' => $concepto['@valorUnitario'],
                        'Importe' => $concepto['@importe'],
                    ];
                }
            } elseif (isset($collection['Comprobante']['cfdi:Conceptos']['cfdi:Concepto']) || isset($collection['Comprobante']['Conceptos']['Concepto'])) {
                $detalles[0] = [
                    'Cantidad' => $collection['Comprobante']['cfdi:Conceptos']['cfdi:Concepto']['@cantidad'] ?? $collection['Comprobante']['Conceptos']['Concepto']['@cantidad'],
                    'Unidad' => $collection['Comprobante']['cfdi:Conceptos']['cfdi:Concepto']['@unidad'] ?? $collection['Comprobante']['Conceptos']['Concepto']['@unidad'],
                    'Descripcion' => $collection['Comprobante']['cfdi:Conceptos']['cfdi:Concepto']['@descripcion'] ?? $collection['Comprobante']['Conceptos']['Concepto']['@descripcion'],
                    'ValorUnitario' => $collection['Comprobante']['cfdi:Conceptos']['cfdi:Concepto']['@valorUnitario'] ?? $collection['Comprobante']['Conceptos']['Concepto']['@valorUnitario'],
                    'Importe' => $collection['Comprobante']['cfdi:Conceptos']['cfdi:Concepto']['@importe'] ?? $collection['Comprobante']['Conceptos']['Concepto']['@importe']
                ];
            } else {
                return response()->json([
                    'estatus' => -2,
                    'resultado' => 'Este CFDI no contiene productos',
                ]);
            }
        }
    }else{
        return response()->json([
            'estatus' => -3,
            'resultado' => "El CFDI tiene una versión inválida",
        ]);
    }
    return response()->json([
        'estatus' => 1,
        'resultado' => $detalles,
        'uuid' => $uuid,
        'version' => $version,
        'relacionados' => $relacionados
    ]);
}