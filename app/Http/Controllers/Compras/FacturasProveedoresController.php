<?php

namespace App\Http\Controllers\Compras;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Administracion\Empresas;
use App\Http\Models\Administracion\Sucursales;
use App\Http\Models\Compras\FacturasProveedores;
use App\Http\Models\SociosNegocio\SociosNegocio;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Crypt;
use XmlParser;

class FacturasProveedoresController extends ControllerBase
{
	public function __construct(FacturasProveedores $entity)
	{
		$this->entity = $entity;
	}

	public function getDataView($entity = null)
    {
        return [
            'proveedores' => SociosNegocio::where('activo','t')->where('fk_id_tipo_socio_compra',3)->pluck('nombre_comercial','id_socio_negocio'),
            'sucursales' => Sucursales::where('activo','t')->pluck('sucursal','id_sucursal'),
            'js_comprador' => Crypt::encryptString('"select": ["nombre","apellido_paterno","apellido_materno"], "conditions": [{"where": ["activo","1"]}], "whereHas": [{"ejecutivocompra":{"where":["id_socio_negocio","$id_socio_negocio"]}}]')
        ];
    }

    public function index($company, $attributes = '')
    {
        return parent::index($company, $attributes);
    }

    public function parseXML($company,Request $request){
	    $xml = simplexml_load_file($request->file('file')->getRealPath());
        $arrayData = xmlToArray($xml);

        //Función comprobar(arrayData,id_socio_negocio,company)
        $rfc_proveedor = SociosNegocio::find($request->fk_id_socio_negocio)->first()->rfc;
        $rfc_empresa = Empresas::where('conexion','LIKE',$company)->first()->rfc;

        $mensaje = '';
        if($arrayData['Comprobante']['cfdi:*items'][0]['Emisor']['@Rfc'] != $rfc_proveedor){
            $mensaje .= "-Por favor verifica que el proveedor seleccionado sea correcto";
        }
        if($arrayData['Comprobante']['cfdi:*items'][1]['Receptor']['@Rfc'] != $rfc_proveedor){
            $mensaje .= "-Por favor verifica que la empresa activa sea la misma del XML";
        }
        echo $mensaje;

//        echo $arrayData->toJson();

//	    print_r($request->file('file')->getRealPath());
//	    return \response()->json();
//	    $xml = XmlParser::load($request->file('file')->getRealPath());
//	    $datos = $xml->getContent();
//	    $datos = $xml->parse([
//            'total' => ['uses' => 'Comprobante::Total'],
//        ]);
//	    print_r($datos);

    }
}