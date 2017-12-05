<?php

/**
 * Obtenemos arreglo accion de ruta personalizada
 * @param  string $action - Acción por la que reemplazar
 * @return array
 */
function routeActionReplace($action = '')
{
	# RouteAction actual, ej: HomeController@index
	$current_action = str_replace('App\Http\Controllers\\', '', Route::currentRouteAction());
	#
	return array_map(function($current, $expected) {
		return $expected === '' ? $current : $expected;
	}, explode('@', $current_action), array_pad(explode('@', $action, 2), -2, '') );
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
	if (in_array($expected_action[1], ['show', 'edit', 'update', 'destroy'])) {
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
	if (in_array(last($expected_action), ['show', 'edit', 'update', 'destroy'])) {
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
    return collect(array(
        $xml->getName() => $propertiesArray
    ));
}