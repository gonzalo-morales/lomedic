<?php

namespace App\Http\Controllers\Ventas;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Administracion\Impuestos;
use App\Http\Models\Compras\DetalleFacturasProveedores;
use App\Http\Models\Ventas\FacturasClientes;
use App\Http\Models\SociosNegocio\SociosNegocio;
use App\Http\Models\Administracion\Sucursales;
use App\Http\Models\Administracion\Monedas;
use App\Http\Models\Administracion\FormasPago;
use App\Http\Models\Administracion\MetodosPago;
use App\Http\Models\Proyectos\Proyectos;
use App\Http\Models\Administracion\UsosCfdis;
use App\Http\Models\Administracion\Empresas;
use App\Http\Models\Finanzas\CondicionesPago;
use App\Http\Models\Administracion\TiposRelacionesCfdi;
use App\Http\Models\Administracion\RegimenesFiscales;
use App\Http\Models\Administracion\SeriesDocumentos;
use App\Http\Models\Administracion\Municipios;
use App\Http\Models\Administracion\Estados;
use App\Http\Models\Administracion\Paises;
use App\Http\Models\Ventas\FacturasClientesDetalle;
use App\Http\Models\Ventas\NotasCreditoClientes;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use Carbon\Carbon;
use XmlParser;
use DB;
use Charles\CFDI\CFDI;
use Charles\CFDI\Node\Relacionado;
use Charles\CFDI\Node\Emisor;
use Charles\CFDI\Node\Receptor;
use Charles\CFDI\Node\Concepto;
use Charles\CFDI\Node\Impuesto\Retencion;

class NotasCreditoClientesController extends ControllerBase
{
    public function __construct(NotasCreditoClientes $entity)
	{
		$this->entity = $entity;
	}

	public function getDataView($entity = null)
    {

//        dd(FacturasClientesDetalle::where('fk_id_factura',1)->join('fac_opr_facturas_clientes as fc','fc.id_factura','=','fac_det_facturas_clientes.fk_id_factura')->get());

        return [
            'empresas' => Empresas::where('activo',1)->where('eliminar',0)->orderBy('razon_social')->pluck('razon_social','id_empresa')->prepend('Selecciona una opcion...',''),
            'js_empresa' => Crypt::encryptString('"conditions": [{"where": ["id_empresa",$id_empresa]}, {"where": ["eliminar",0]}], "limit": "1"'),
            'regimens' => RegimenesFiscales::select('regimen_fiscal','id_regimen_fiscal')->where('activo',1)->where('eliminar',0)->orderBy('regimen_fiscal')->pluck('regimen_fiscal','id_regimen_fiscal')->prepend('...',''),
            'series' => SeriesDocumentos::select('prefijo','id_serie')->where('activo',1)->where('fk_id_tipo_documento',4)->pluck('prefijo','id_serie'),
            'js_series' => Crypt::encryptString('"conditions": [{"where": ["fk_id_empresa",$id_empresa]}, {"where": ["activo",1]}]'),
            
            'municipios' => Municipios::select('municipio','id_municipio')->where('activo',1)->where('eliminar',0)->pluck('municipio','id_municipio')->prepend('...',''),
            'estados' => Estados::select('estado','id_estado')->where('activo',1)->where('eliminar',0)->pluck('estado','id_estado')->prepend('...',''),
            'paises' => Paises::select('pais','id_pais')->where('activo',1)->where('eliminar',0)->pluck('pais','id_pais')->prepend('...',''),
            
            'js_clientes' => Crypt::encryptString('"select": ["razon_social", "id_socio_negocio"], "conditions": [{"where": ["activo",1]}, {"where": ["eliminar",0]}, {"where": ["fk_id_tipo_socio_venta",1]}], "whereHas":[{"empresas":{"where":["id_empresa","$id_empresa"]}}]'),
            'clientes' => empty($entity) ? [] : SociosNegocio::where('fk_id_tipo_socio_venta',1)
            ->whereHas('empresas', function ($query) use($entity) {
                $query->where('id_empresa','=',$entity->fk_id_empresa);
            })->orderBy('nombre_comercial')->pluck('nombre_comercial','id_socio_negocio')->prepend('Selecciona una opcion...',''),
            'js_cliente' => Crypt::encryptString('"conditions": [{"where": ["id_socio_negocio",$id_socio_negocio]}, {"where": ["eliminar",0]}], "limit": "1"'),
            
            'js_proyectos' => Crypt::encryptString('"select": ["proyecto", "id_proyecto"], "conditions": [{"where": ["fk_id_estatus",1]}, {"where": ["eliminar",0]}, {"where": ["fk_id_cliente","$fk_id_cliente"]}], "orderBy": [["proyecto", "ASC"]]'),
            'proyectos' => empty($entity) ? [] : Proyectos::where('id_proyecto',$entity->fk_id_proyecto)->pluck('proyecto','id_proyecto')->prepend('Selecciona una opcion...',''),
            'js_sucursales' => Crypt::encryptString('"select": ["sucursal", "id_sucursal"], "conditions": [{"where": ["activo",1]}, {"where": ["eliminar",0]}, {"where": ["fk_id_cliente","$fk_id_cliente"]}], "orderBy": [["sucursal", "ASC"]]'),
            'sucursales' => Sucursales::where('activo',1)->orderBy('sucursal')->pluck('sucursal','id_sucursal')->prepend('Selecciona una opcion...',''),
            'monedas' => Monedas::selectRaw("CONCAT(descripcion,' (',moneda,')') as moneda, id_moneda")->where('activo','1')->where('eliminar','0')->orderBy('moneda')->pluck('moneda','id_moneda')->prepend('Selecciona una opcion...',''),
            'metodospago' => MetodosPago::selectRaw("CONCAT(metodo_pago,' - ',descripcion) as metodo_pago, id_metodo_pago")->where('activo','1')->where('eliminar','0')->orderBy('metodo_pago')->pluck('metodo_pago','id_metodo_pago')->prepend('Selecciona una opcion...',''),
            'formaspago' => FormasPago::selectRaw("CONCAT(forma_pago,' - ',descripcion) as forma_pago, id_forma_pago")->where('activo','1')->where('eliminar','0')->orderBy('forma_pago')->pluck('forma_pago','id_forma_pago')->prepend('Selecciona una opcion...',''),
            'condicionespago' => CondicionesPago::select('condicion_pago','id_condicion_pago')->where('activo','1')->where('eliminar','0')->orderBy('condicion_pago')->pluck('condicion_pago','id_condicion_pago')->prepend('Selecciona una opcion...',''),
            'usoscfdi' => UsosCfdis::selectRaw("CONCAT(uso_cfdi,' - ',descripcion) as uso_cfdi, id_uso_cfdi")->where('activo','1')->where('eliminar','0')->orderBy('uso_cfdi')->pluck('uso_cfdi','id_uso_cfdi')->prepend('Selecciona una opcion...',''),
            'tiposrelacion' => TiposRelacionesCfdi::selectRaw("CONCAT(tipo_relacion,' - ',descripcion) as tipo_relacion, id_sat_tipo_relacion")->where('activo',1)->where('eliminar',0)->where('nota_credito',1)->orderBy('tipo_relacion')->pluck('tipo_relacion','id_sat_tipo_relacion')->prepend('Selecciona una opcion...',''),
            'facturasrelacionadas' =>FacturasClientes::selectRaw("CONCAT(serie,'-',folio,'  [',uuid,']') as factura, id_factura")->whereNotNull('uuid')->orderBy('factura')->pluck('factura','id_factura')->prepend('Selecciona una opcion...',''),
            'js_productos_facturas' => Crypt::encryptString('
                "select": ["sku.id_sku","cc.id_clave_cliente_producto","sku.fk_id_unidad_medida","fk_id_factura","cc.fk_id_clave_producto_servicio",
                            "id_factura_detalle","fc.serie","fc.folio","cc.clave_producto_cliente","sku.sku","upc.id_upc","upc.descripcion",
                            "cps.clave_producto_servicio","um.nombre as unidad_medida"
                ],
                "conditions":[
                    {"whereIn":["fk_id_factura",$fk_id_factura]},
                    {"where":["fac_det_facturas_clientes.eliminar",0]}],
                "joins":[
                    {"join":["fac_opr_facturas_clientes as fc","fc.id_factura","=","fac_det_facturas_clientes.fk_id_factura"]},
                    {"join":["pry_cat_clave_cliente_productos as cc","cc.id_clave_cliente_producto","=","fac_det_facturas_clientes.fk_id_clave_cliente"]},
                    {"join":["inv_cat_skus as sku","sku.id_sku","=","cc.fk_id_sku"]},
                    {"join":["maestro.inv_cat_upcs as upc","upc.id_upc","=","cc.fk_id_upc"]},
                    {"join":["maestro.sat_cat_claves_productos_servicios as cps","cps.id_clave_producto_servicio","=","cc.fk_id_clave_producto_servicio"]},
                    {"join":["maestro.gen_cat_unidades_medidas as um","um.id_unidad_medida","=","cc.fk_id_unidad_medida"]}
                ]'),
            'js_impuestos' => Crypt::encryptString('"select":["id_impuesto","impuesto","tasa_o_cuota","porcentaje"],"conditions":[{"where":["activo",1]},{"whereNotNull":["tasa_o_cuota"]}]')
        ];
    }
    
    
    public function generarXml($company,Request $request)
    {
        if(!empty($entity)) {
            
             $cfdi = new CFDI([
             'Serie' => $entity->serie,
             'Folio' => $entity->folio,
             'Fecha' => str_replace(' ','T',$entity->fecha_timbrado),
             'FormaPago' => $entity->formapago->forma_pago,
             'NoCertificado' => $entity->certificado->no_certificado,
             'CondicionesDePago' => $entity->condicionpago->condicion_pago,
             'Subtotal' => $entity->subtotal,
             'Descuento' => $entity->descuento,
             'Moneda' => $entity->moneda->moneda,
             'TipoCambio' => $entity->tipo_cambio,
             'Total' => $entity->total,
             'TipoDeComprobante' => $entity->tipocomprobante->tipo_comprobante,
             'MetodoPago' => $entity->metodopago->metodo_pago,
             'LugarExpedicion' => '64000',
             ], $entity->certificado->cadena_cer, $entity->certificado->cadena_key);
             
             $cfdi->add(new Emisor([
             'Rfc' => $entity->empresa->rfc,
             'Nombre' => $entity->empresa->razon_social,
             'RegimenFiscal' => $entity->empresa->fk_id_regimen_fiscal,
             ]));
             
             $cfdi->add(new Receptor([
             'Rfc' =>  $entity->cliente->rfc,
             'Nombre' => $entity->cliente->razon_social,
             'ResidenciaFiscal' => 'MXN',
             'NumRegIdTrib' => '121585958',
             'UsoCFDI' => $entity->usocfdi->uso_cfdi,
             ]));
             
             foreach ($entity->detalle as $row) {
             $concepto = new Concepto([
             'ClaveProdServ' => $row->claveproducto->clave_producto_servicio,
             'NoIdentificacion' => $row->clavecliente->clave_producto_cliente,
             'Cantidad' => $row->cantidad,
             'ClaveUnidad' => $row->unidadmedida->clave_unidad,
             'Unidad' => $row->unidadmedida->descripcion,
             'Descripcion' => $row->descripcion,
             'ValorUnitario' => $row->precio_unitario,
             'Importe' => $row->importe,
             'Descuento' => $row->descuento,
             ]);
             
             $concepto->add(new Retencion([
             'Impuesto' => $row->impuestos->numero_impuesto,
             'Importe' => $row->impuesto,
             ]));
             
             $cfdi->add($concepto);
             }
             
             #file_put_contents(base_path().'/prueba.xml',$cfdi->getXML());
             
             dump($cfdi->getXML());
             dd();
        }
    }

    /*
    public function parseXML($company,Request $request)
    {
        try{
            $xml = simplexml_load_file($request->file('file')->getRealPath());
            $arrayData = xmlToArray($xml);
            return validarRequerimientosCFDI($arrayData, $request->fk_id_socio_negocio, $company, "I");
        }catch (\Exception $e){
            return response()->json([
                'estatus' => -2,
                'resultado' => "No se pudo leer el XML porque tiene un formato incorrecto",
            ]);
        }
    }
    */
}