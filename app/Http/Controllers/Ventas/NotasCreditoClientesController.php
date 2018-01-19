<?php

namespace App\Http\Controllers\Ventas;

use App\Http\Controllers\ControllerBase;
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
use App\Http\Models\Ventas\NotasCargoClientes;
use App\Http\Models\Ventas\NotasCreditoClientes;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Request;
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

//        dd(FacturasClientes::selectRaw("CONCAT(serie,'-',folio,'  [',uuid,']') as factura, id_factura")->whereNotNull('uuid')->orderBy('factura')->pluck('factura','id_factura'));

        return [
            'empresas' => Empresas::where('activo',1)->where('eliminar',0)->orderBy('razon_social')->pluck('razon_social','id_empresa')->prepend('Selecciona una opcion...',''),
            'js_empresa' => Crypt::encryptString('"conditions": [{"where": ["id_empresa",$id_empresa]}, {"where": ["eliminar",0]}], "limit": "1"'),
            'regimens' => RegimenesFiscales::select('regimen_fiscal','id_regimen_fiscal')->where('activo',1)->where('eliminar',0)->orderBy('regimen_fiscal')->pluck('regimen_fiscal','id_regimen_fiscal')->prepend('...',''),
            'series' => SeriesDocumentos::select('prefijo','id_serie')->where('activo',1)->where('fk_id_tipo_documento',5)->pluck('prefijo','id_serie'),
            'js_series' => Crypt::encryptString('"conditions": [{"where": ["fk_id_empresa",$id_empresa]}, {"where": ["activo",1]},{"where":["fk_id_tipo_documento",5]}]'),
            'js_serie'=> Crypt::encryptString('"select":["prefijo","sufijo","siguiente_numero"],"conditions":[{"where":["id_serie",$id_serie]},{"whereRaw":["(siguiente_numero <= coalesce(ultimo_numero,0) OR ultimo_numero IS NULL)"]}]'),
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
            'facturasrelacionadas' =>FacturasClientes::selectRaw("CONCAT(serie,'-',folio,'  [',uuid,']') as factura, id_documento")->whereNotNull('uuid')->orderBy('factura')->pluck('factura','id_documento')->prepend('Selecciona una opcion...','0'),
            'notascargorelacionadas'=>NotasCargoClientes::selectRaw("CONCAT(serie,'-',folio,'  [',uuid,']') as notacargo, id_documento")->whereNotNull('uuid')->orderBy('notacargo')->pluck('notacargo','id_documento')->prepend('Selecciona una opcion...','0'),
            'js_productos_facturas'=>Crypt::encryptString('"relations":[{"id_documento":[{"toArrayWithDetails":$fk_id_documento}]}]'),
//            'js_productos_facturas'=>Crypt::encryptString('
//                "select": ["serie", "folio"],
//                "conditions": [{
//                    "whereIn": [
//                        "id_factura", $fk_id_factura
//                    ]
//                }],
//                "relations": [{
//                    "detalle": [{
//                        "relations": [{
//                            "claveproducto": [],
//                            "clavecliente": [],
//                            "unidadmedida": []
//                        }]
//                    }]
//                }]'),
//            'js_productos_facturas' => Crypt::encryptString('
//                "select": ["sku.id_sku","cc.id_clave_cliente_producto","fac_det_facturas_clientes.fk_id_unidad_medida","fk_id_factura","cc.fk_id_clave_producto_servicio",
//                            "id_factura_detalle","fc.serie","fc.folio","cc.clave_producto_cliente","sku.sku","upc.id_upc","upc.descripcion",
//                            "cps.clave_producto_servicio","unidad as unidad_medida ","fac_det_facturas_clientes.fk_id_tipo_documento"
//                ],
//                "conditions":[
//                    {"whereIn":["fk_id_factura",$fk_id_factura]},
//                    {"where":["fac_det_facturas_clientes.eliminar",0]},
//                    {"whereNotNull":["fc.uuid"]}
//                ],
//                "joins":[
//                    {"join":["fac_opr_facturas_clientes as fc","fc.id_factura","=","fac_det_facturas_clientes.fk_id_factura"]},
//                    {"join":["pry_cat_clave_cliente_productos as cc","cc.id_clave_cliente_producto","=","fac_det_facturas_clientes.fk_id_clave_cliente"]},
//                    {"join":["inv_cat_skus as sku","sku.id_sku","=","cc.fk_id_sku"]},
//                    {"join":["maestro.inv_cat_upcs as upc","upc.id_upc","=","cc.fk_id_upc"]},
//                    {"join":["maestro.sat_cat_claves_productos_servicios as cps","cps.id_clave_producto_servicio","=","cc.fk_id_clave_producto_servicio"]},
//                    {"join":["maestro.sat_cat_claves_unidades as um","um.id_clave_unidad","=","cc.fk_id_unidad_medida"]}
//                ]'),
            'js_productos_notascargo' => Crypt::encryptString('
                "select": ["sku.id_sku","cc.id_clave_cliente_producto","sku.fk_id_unidad_medida","fk_id_nota_cargo","cc.fk_id_clave_producto_servicio",
                            "id_nota_cargo_detalle","fc.serie","fc.folio","cc.clave_producto_cliente","sku.sku","upc.id_upc","upc.descripcion",
                            "cps.clave_producto_servicio","um.nombre as unidad_medida","fac_det_notas_cargo_clientes.fk_id_tipo_documento"
                ],
                "distinct":[],
                "conditions":[
                    {"whereIn":["fk_id_nota_cargo",$fk_id_nota_cargo]},
                    {"where":["fac_det_notas_cargo_clientes.eliminar",0]}],
                    {"whereNotNull":["fc.uuid"]}
                "joins":[
                    {"join":["fac_opr_notas_cargo_clientes as fc","fc.id_nota_cargo","=","fac_det_notas_cargo_clientes.fk_id_nota_cargo"]},
                    {"join":["pry_cat_clave_cliente_productos as cc","cc.id_clave_cliente_producto","=","fac_det_notas_cargo_clientes.fk_id_clave_cliente"]},
                    {"join":["inv_cat_skus as sku","sku.id_sku","=","cc.fk_id_sku"]},
                    {"join":["maestro.inv_cat_upcs as upc","upc.id_upc","=","cc.fk_id_upc"]},
                    {"join":["maestro.sat_cat_claves_productos_servicios as cps","cps.id_clave_producto_servicio","=","cc.fk_id_clave_producto_servicio"]},
                    {"join":["maestro.gen_cat_unidades_medidas as um","um.id_unidad_medida","=","cc.fk_id_unidad_medida"]}
                ]'),
            'js_impuestos' => Crypt::encryptString('
            "select":["id_impuesto","impuesto","tasa_o_cuota","porcentaje","descripcion"],
            "conditions":[{"where":["activo",1]},
            {"whereNotNull":["tasa_o_cuota"]}]')
        ];
    }
    
    public function store(Request $request, $company, $compact = false)
    {
        foreach ($request->relations['has']['relaciones'] as $row =>$detalle){
            $arreglo = $request->relations;
            $arreglo['has']['relaciones'][$row]['fk_id_tipo_documento'] = 5;
            $request->merge(['relations'=>$arreglo]);
        }

        $return = parent::store($request, $company, true);

        $datos = $return["entity"];

        if($datos) {

            $xml = generarXml($this->datos_cfdi($datos->id_nota_credito));

            if(!empty($xml)) {
                $request->request->add(['xml_original'=>$xml]);
            }

            if($request->timbrar == true && !empty($xml))
                $timbrado = timbrar($xml);

            if(isset($timbrado) && $timbrado->status == '200') {
                if(in_array($timbrado->resultados->status,['200','307'])) {
                    $request->request->add([
                        'cadena_original'=>$timbrado->resultados->cadenaOriginal,
                        'certificado_sat'=>$timbrado->resultados->certificadoSAT,
                        'xml_timbrado'=>$timbrado->resultados->cfdiTimbrado,
                        'fecha_timbrado'=>str_replace('T',' ',substr($timbrado->resultados->fechaTimbrado,0,19)),
                        'sello_sat'=>$timbrado->resultados->selloSAT,
                        'uuid'=>$timbrado->resultados->uuid,
                        'version_tfd'=>$timbrado->resultados->versionTFD,
                        'codigo_qr'=>base64_encode($timbrado->resultados->qrCode),
                    ]);
                }
            }
            $request->request->set('save',true);
            $id = $datos->id_nota_credito;
            $return = parent::update($request, $company, $id, true);
        }
        return $return["redirect"];
    }

    public function update(Request $request, $company, $id, $compact = true)
    {
        $return = parent::update($request, $company, $id, $compact);

        $datos = $return["entity"];

        if($datos && $request->save !== true)
        {
            $xml = generarXml($this->datos_cfdi($datos->id_nota_credito));

            if(!empty($xml)) {
                $request->request->add(['xml_original'=>$xml]);
            }

            #dd($xml['xml']);
            $timbrado = null;
            if($request->timbrar == true && !empty($xml))
                $timbrado = timbrar($xml);

            if(isset($timbrado) && $timbrado->status == '200') {
                if(in_array($timbrado->resultados->status,['200','307'])) {
                    $request->request->add([
                        'cadena_original'=>$timbrado->resultados->cadenaOriginal,
                        'certificado_sat'=>$timbrado->resultados->certificadoSAT,
                        'xml_timbrado'=>$timbrado->resultados->cfdiTimbrado,
                        'fecha_timbrado'=>str_replace('T',' ',substr($timbrado->resultados->fechaTimbrado,0,19)),
                        'sello_sat'=>$timbrado->resultados->selloSAT,
                        'uuid'=>$timbrado->resultados->uuid,
                        'version_tfd'=>$timbrado->resultados->versionTFD,
                        'codigo_qr'=>base64_encode($timbrado->resultados->qrCode),
                    ]);
                }
                else
                    dd($timbrado);
            }
            else{
                dd($timbrado);
            }
            $request->request->set('save',true);
            $return = parent::update($request, $company, $id, $compact);
        }
        return $return["redirect"];
    }

    protected function datos_cfdi($id)
    {
        $return = [];
        $entity = $this->entity->find($id);

        if(!empty($entity))
        {
            $return['certificado'] = $entity->certificado->cadena_cer;
            $return['key'] = $entity->certificado->cadena_key;
            $return['cfdi'] = [
                'Version'=>'3.3',
                'Serie' => $entity->serie,
                'Folio' => $entity->folio,
                'Fecha' => str_replace(' ','T',substr($entity->fecha_creacion,0,19)),
                'FormaPago' => $entity->formapago->forma_pago,
                'NoCertificado' => $entity->certificado->no_certificado,
                'CondicionesDePago' => $entity->condicionpago->condicion_pago,
                'Moneda' => $entity->moneda->moneda,
                'TipoCambio' => round($entity->tipo_cambio,4),
                'TipoDeComprobante' => $entity->tipocomprobante->tipo_comprobante,
                'MetodoPago' => $entity->metodopago->metodo_pago,
                'LugarExpedicion' => '64000',
            ];

            foreach ($entity->relaciones as $i=>$row)
            {
                $return['relacionados'][$row->tiporelacion->tipo_relacion][] = ['UUID'=>$row->documento->uuid];
            }

            $return['emisor'] = [
                'Rfc' => $entity->empresa->rfc,
                'Nombre' => $entity->empresa->razon_social,
                'RegimenFiscal' => $entity->empresa->fk_id_regimen_fiscal,
            ];

            $return['receptor'] = [
                'Rfc' =>  $entity->cliente->rfc,
                'Nombre' => $entity->cliente->razon_social,
                #'ResidenciaFiscal' => 'MXN',
                #'NumRegIdTrib' => '121585958',
                'UsoCFDI' => $entity->usocfdi->uso_cfdi,
            ];

            foreach ($entity->detalle as $i=>$row)
            {
                $impuesto = [];
                if($row->impuestos->retencion) {
                    $impuesto['retencion'] = [
                        'Impuesto' => $row->impuestos->numero_impuesto,
                        'TipoFactor' => $row->impuestos->tipo_factor,
                        'TasaOCuota' => $row->impuestos->tasa_o_cuota,
                        'Importe' => number_format($row->impuesto,2,'.',''),
                        'Base' => number_format(($row->importe),2,'.',''),
                    ];
                }
                else {
                    $impuesto['traslado'] = [
                        'Impuesto' => $row->impuestos->numero_impuesto,
                        'TipoFactor' => $row->impuestos->tipo_factor,
                        'TasaOCuota' => $row->impuestos->tasa_o_cuota,
                        'Importe' => number_format($row->impuesto,2,'.',''),
                        'Base' => number_format(($row->importe),2,'.','') - number_format(($row->descuento),2,'.',''),
                    ];
                }

                $concepto = [
                    'ClaveProdServ' => $row->claveproducto->clave_producto_servicio,
                    'NoIdentificacion' => $row->clavecliente->clave_producto_cliente,
                    'Cantidad' => $row->cantidad,
                    'ClaveUnidad' => $row->unidadmedida->clave_unidad,
                    'Unidad' => $row->unidadmedida->descripcion,
                    'Descripcion' => $row->descripcion,
                    'ValorUnitario' => number_format($row->precio_unitario,2,'.',''),
                    'Importe' => ($row->cantidad * number_format($row->precio_unitario,2,'.','')),
                    'impuestos' => [$impuesto]
                ];
                if($row->descuento > 0)
                    $concepto['Descuento'] = number_format($row->descuento,2,'.','');

                if(!empty($row->cuenta_predial))
                    $concepto['cuentapredial'] = $row->cuenta_predial;

                if(!empty($row->pedimento))
                    $concepto['pedimento'] = $row->pedimento;

                $return['conceptos'][] = $concepto;
            }
        }
        return $return;
    }
}