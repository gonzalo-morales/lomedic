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
use App\Http\Models\Ventas\FacturasClientesDetalle;
use App\Http\Models\Ventas\NotasCargoClientes;
use App\Http\Models\Ventas\NotasCreditoClientesDetalle;
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
use App\Http\Models\Ventas\NotasCreditoClientes;

class NotasCargoClientesController extends ControllerBase
{
    public function __construct(NotasCargoClientes $entity)
	{
		$this->entity = $entity;
	}

	public function getDataView($entity = null)
    {

//        dd(FacturasClientes::selectRaw("CONCAT(serie,'-',folio,'  [',uuid,']') as factura, id_factura")->whereNotNull('uuid')->orderBy('factura')->pluck('factura','id_factura'));
        return [
            'empresas' => Empresas::where('activo',1)->orderBy('razon_social')->pluck('razon_social','id_empresa')->prepend('...',''),
            'js_empresa' => Crypt::encryptString('"conditions": [{"where": ["id_empresa",$id_empresa]}], "limit": "1"'),
            'regimens' => RegimenesFiscales::select('regimen_fiscal','id_regimen_fiscal')->where('activo',1)->orderBy('regimen_fiscal')->pluck('regimen_fiscal','id_regimen_fiscal')->prepend('...',''),
            'series' => SeriesDocumentos::select('prefijo','id_serie')->where('activo',1)->where('fk_id_tipo_documento',5)->pluck('prefijo','id_serie'),
            'js_series' => Crypt::encryptString('"conditions": [{"where": ["fk_id_empresa",$id_empresa]}, {"where": ["activo",1]},{"where":["fk_id_tipo_documento",6]}]'),
            'js_serie'=> Crypt::encryptString('"select":["prefijo","sufijo","siguiente_numero"],"conditions":[{"where":["id_serie",$id_serie]},{"whereRaw":["(siguiente_numero <= coalesce(ultimo_numero,0) OR ultimo_numero IS NULL)"]}]'),
            'municipios' => Municipios::select('municipio','id_municipio')->where('activo',1)->pluck('municipio','id_municipio')->prepend('...',''),
            'estados' => Estados::select('estado','id_estado')->where('activo',1)->pluck('estado','id_estado')->prepend('...',''),
            'paises' => Paises::select('pais','id_pais')->where('activo',1)->pluck('pais','id_pais')->prepend('...',''),
            'js_clientes' => Crypt::encryptString('"select": ["razon_social", "id_socio_negocio"], "conditions": [{"where": ["activo",1]}, {"where": ["activo",1]}, {"where": ["fk_id_tipo_socio_venta",1]}], "whereHas":[{"empresas":{"where":["id_empresa","$id_empresa"]}}]'),
            'clientes' => empty($entity) ? [] : SociosNegocio::where('fk_id_tipo_socio_venta',1)
            ->whereHas('empresas', function ($query) use($entity) {
                $query->where('id_empresa','=',$entity->fk_id_empresa);
            })->orderBy('nombre_comercial')->pluck('nombre_comercial','id_socio_negocio')->prepend('...',''),
            'js_cliente' => Crypt::encryptString('"conditions": [{"where": ["id_socio_negocio",$id_socio_negocio]}, {"where": ["activo",1]}], "limit": "1"'),
            'js_proyectos' => Crypt::encryptString('"select": ["proyecto", "id_proyecto"], "conditions": [{"where": ["fk_id_estatus",1]}, {"where": ["fk_id_cliente","$fk_id_cliente"]}], "orderBy": [["proyecto", "ASC"]]'),
            'proyectos' => empty($entity) ? [] : Proyectos::where('id_proyecto',$entity->fk_id_proyecto)->pluck('proyecto','id_proyecto')->prepend('...',''),
            'js_sucursales' => Crypt::encryptString('"select": ["sucursal", "id_sucursal"], "conditions": [{"where": ["activo",1]}, {"where": ["fk_id_cliente","$fk_id_cliente"]}], "orderBy": [["sucursal", "ASC"]]'),
            'sucursales' => Sucursales::where('activo',1)->orderBy('sucursal')->pluck('sucursal','id_sucursal')->prepend('...',''),
            'monedas' => Monedas::selectRaw("CONCAT(descripcion,' (',moneda,')') as moneda, id_moneda")->where('activo',1)->orderBy('moneda')->pluck('moneda','id_moneda')->prepend('...',''),
            'metodospago' => MetodosPago::selectRaw("CONCAT(metodo_pago,' - ',descripcion) as metodo_pago, id_metodo_pago")->where('activo',1)->orderBy('metodo_pago')->pluck('metodo_pago','id_metodo_pago')->prepend('...',''),
            'formaspago' => FormasPago::selectRaw("CONCAT(forma_pago,' - ',descripcion) as forma_pago, id_forma_pago")->where('activo',1)->orderBy('forma_pago')->pluck('forma_pago','id_forma_pago')->prepend('...',''),
            'condicionespago' => CondicionesPago::select('condicion_pago','id_condicion_pago')->where('activo',1)->orderBy('condicion_pago')->pluck('condicion_pago','id_condicion_pago')->prepend('...',''),
            'usoscfdi' => UsosCfdis::selectRaw("CONCAT(uso_cfdi,' - ',descripcion) as uso_cfdi, id_uso_cfdi")->where('activo',1)->orderBy('uso_cfdi')->pluck('uso_cfdi','id_uso_cfdi')->prepend('...',''),
            'tiposrelacion' => TiposRelacionesCfdi::selectRaw("CONCAT(tipo_relacion,' - ',descripcion) as tipo_relacion, id_sat_tipo_relacion")->where('activo',1)->where('nota_credito',1)->orderBy('tipo_relacion')->pluck('tipo_relacion','id_sat_tipo_relacion')->prepend('...',''),
            'facturasrelacionadas' =>FacturasClientes::selectRaw("CONCAT(serie,'-',folio,'  [',uuid,']') as factura, id_documento")->whereNotNull('uuid')->orderBy('factura')->pluck('factura','id_documento')->prepend('...','0'),
            'notascreditorelacionadas'=>NotasCreditoClientes::selectRaw("CONCAT(serie,'-',folio,'  [',uuid,']') as notacredito, id_documento")->whereNotNull('uuid')->orderBy('notacredito')->pluck('notacredito','id_documento')->prepend('...','0'),
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

    function getProductosRelacionados($company)
    {
        $detalles = [];
        $facturas = FacturasClientesDetalle::whereIn('fk_id_documento',json_decode(\request()->detallesfacturas))->get();
        foreach ($facturas as $index=>$factura){
            $detalles[]=[
                "id" => $factura->id_documento_detalle,
                "fk_id_documento" => $factura->fk_id_documento,
                "fk_id_tipo_documento" => $factura->fk_id_tipo_documento,
                "fk_id_clave_producto_servicio" => $factura->fk_id_clave_producto_servicio,
                "clave_producto_servicio"=>$factura->claveproducto->clave_producto_servicio,
                "fk_id_sku" => $factura->fk_id_sku,
                "sku"=>$factura->sku->sku,
                "fk_id_upc" => $factura->fk_id_upc,
                "descripcion"=>$factura->upc->upc,
                "fk_id_clave_cliente" => $factura->fk_id_clave_cliente,
                "clave_producto_cliente"=>$factura->clavecliente->clave_producto_cliente,
                "text" => $factura->descripcion,
                "fk_id_unidad_medida" => $factura->fk_id_unidad_medida,
                "unidad_medida" => $factura->unidad,
                "cantidad" => $factura->cantidad,
                "precio_unitario" => $factura->precio_unitario,
                "importe" => $factura->importe,
                "fk_id_moneda" => $factura->fk_id_moneda,
                "moneda"=>$factura->moneda->moneda,
                "fk_id_impuesto" => $factura->fk_id_impuesto,
                "impuesto"=>$factura->impuesto,
                "descuento" => $factura->descuento,
                "pedimento" => $factura->pedimento,
                "cuenta_predial" => $factura->cuenta_predial,
                "serie" => $factura->factura->serie,
                "folio" => $factura->factura->folio,
            ];
        }
        $notas = NotasCreditoClientesDetalle::whereIn('fk_id_documento',json_decode(\request()->detallesnotas))->get();
        foreach ($notas as $index=>$nota){
            $detalles[]=[
                "id" => $nota->id_documento_detalle,
                "fk_id_documento" => $nota->fk_id_documento,
                "fk_id_tipo_documento" => $nota->fk_id_tipo_documento,
                "fk_id_clave_producto_servicio" => $nota->fk_id_clave_producto_servicio,
                "clave_producto_servicio"=>$nota->claveproducto->clave_producto_servicio,
                "fk_id_sku" => $nota->fk_id_sku,
                "sku"=>$nota->sku->sku,
                "fk_id_upc" => $nota->fk_id_upc,
                "descripcion"=>$nota->upc->upc,
                "fk_id_clave_cliente" => $nota->fk_id_clave_cliente,
                "clave_producto_cliente"=>$nota->clavecliente->clave_producto_cliente,
                "text" => $nota->descripcion,
                "fk_id_unidad_medida" => $nota->fk_id_unidad_medida,
                "unidad_medida" => $nota->unidad,
                "cantidad" => $nota->cantidad,
                "precio_unitario" => $nota->precio_unitario,
                "importe" => $nota->importe,
                "fk_id_moneda" => $nota->fk_id_moneda,
                "moneda"=>$nota->moneda->moneda,
                "fk_id_impuesto" => $nota->fk_id_impuesto,
                "impuesto"=>$nota->impuesto,
                "descuento" => $nota->descuento,
                "pedimento" => $nota->pedimento,
                "cuenta_predial" => $nota->cuenta_predial,
                "serie" => $nota->factura->serie,
                "folio" => $nota->factura->folio,
            ];
        }
        return $detalles;
    }
}