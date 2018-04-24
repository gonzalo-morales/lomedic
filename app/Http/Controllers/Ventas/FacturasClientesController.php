<?php
namespace App\Http\Controllers\Ventas;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Inventarios\Cbn;
use App\Http\Models\Inventarios\Productos;
use App\Http\Models\Inventarios\Upcs;
use App\Http\Models\Proyectos\ClaveClienteProductos;
use App\Http\Models\Proyectos\ProyectosProductos;
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
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use File;
use App\Http\Models\Proyectos\ContratosProyectos;
use App\Http\Models\Administracion\Impuestos;

class FacturasClientesController extends ControllerBase
{
    public function __construct()
	{
	    $this->entity = new FacturasClientes;
	}

	public function getDataView($entity = null)
	{
        return [
            'empresas' => Empresas::where('activo',1)->orderBy('razon_social')->pluck('razon_social','id_empresa')->prepend('...',''),
            'js_impuestos' => Crypt::encryptString('"select":["id_impuesto","impuesto","tasa_o_cuota","porcentaje","descripcion"],"conditions":[{"where":["activo",1]},{"whereNotNull":["tasa_o_cuota"]}]'),
            'js_productos' => Crypt::encryptString('"select":["id_clave_cliente_producto as id","clave_producto_cliente as text","*"],"withFunction":[{"proyectoproducto":{"where":{["fk_id_proyecto",$id_proyecto]},"with":["moneda:id_moneda,moneda"]}}],"with":["impuesto:id_impuesto,impuesto","claveproductoservicio:id_clave_producto_servicio,clave_producto_servicio","claveunidad:id_clave_unidad,descripcion,clave_unidad"],"whereHas":[{"proyectos":{"where":["id_proyecto",$id_proyecto]}}]'),
            'js_empresa' => Crypt::encryptString('"conditions": [{"where": ["id_empresa","$id_empresa"]}]'),
            'regimens' => RegimenesFiscales::where('activo',1)->select('regimen_fiscal','id_regimen_fiscal')->orderBy('regimen_fiscal')->pluck('regimen_fiscal','id_regimen_fiscal'),
            'series' => SeriesDocumentos::select('prefijo','id_serie')->where('activo',1)->where('fk_id_tipo_documento',4)->pluck('prefijo','id_serie'),
            'js_series' => Crypt::encryptString('"conditions": [{"where": ["fk_id_empresa",$id_empresa]}, {"where": ["fk_id_tipo_documento",4]}, {"where": ["activo",1]}]'),
            'js_serie'=> Crypt::encryptString('"select":["prefijo","sufijo","siguiente_numero"],"conditions":[{"where":["id_serie",$id_serie]},{"whereRaw":["(siguiente_numero <= coalesce(ultimo_numero,0) OR ultimo_numero IS NULL)"]}]'),
            'municipios' => Municipios::where('activo',1)->select('municipio','id_municipio')->pluck('municipio','id_municipio'),
            'estados' => Estados::where('activo',1)->select('estado','id_estado')->pluck('estado','id_estado'),
            'paises' => Paises::where('activo',1)->select('pais','id_pais')->pluck('pais','id_pais'),
            'js_clientes' => Crypt::encryptString('"select": ["razon_social", "id_socio_negocio"], "conditions": [{"where": ["activo",1]}, {"where": ["fk_id_tipo_socio_venta",1]}], "whereHas":[{"empresas":{"where":["id_empresa","$id_empresa"]}}]'),
            'clientes' => empty($entity) ? [] : SociosNegocio::where('fk_id_tipo_socio_venta',1)->whereHas('empresas', function ($query) use($entity) {
                $query->where('id_empresa','=',$entity->fk_id_empresa);
            })->orderBy('nombre_comercial')->pluck('nombre_comercial','id_socio_negocio'),
            'js_cliente' => Crypt::encryptString('"conditions": [{"where": ["id_socio_negocio",$id_socio_negocio]}, {"where": ["activo",1]}], "limit": "1"'),
            'js_proyectos' => Crypt::encryptString('"select": ["proyecto", "id_proyecto"], "conditions": [{"where": ["fk_id_estatus",1]}, {"where": ["fk_id_cliente","$fk_id_cliente"]}], "orderBy": [["proyecto", "ASC"]]'),
            'proyectos' => empty($entity) ? [] : Proyectos::where('id_proyecto',$entity->fk_id_proyecto)->pluck('proyecto','id_proyecto'),
            'contratos' => empty($entity) ? [] : ContratosProyectos::where('id_contrato',$entity->fk_id_contrato)->pluck('num_contrato','id_contrato'),
            'js_contratos' => Crypt::encryptString('"select":["id_proyecto"], "conditions":[{"where":["id_proyecto","$id_proyecto"]}], "with":["contratos:id_contrato,num_contrato,fk_id_proyecto"]'),
            'js_sucursales' => Crypt::encryptString('"select": ["sucursal", "id_sucursal"], "conditions": [{"where": ["activo",1]}, {"where": ["fk_id_cliente","$fk_id_cliente"]}], "orderBy": [["sucursal", "ASC"]]'),
            'sucursales' => Sucursales::where('activo',1)->orderBy('sucursal')->pluck('sucursal','id_sucursal'),
            'monedas' => Monedas::where('activo',1)->selectRaw("CONCAT(descripcion,' (',moneda,')') as moneda, id_moneda")->orderBy('moneda')->pluck('moneda','id_moneda'),
            'metodospago' => MetodosPago::where('activo',1)->selectRaw("CONCAT(metodo_pago,' - ',descripcion) as metodo_pago, id_metodo_pago")->orderBy('metodo_pago')->pluck('metodo_pago','id_metodo_pago'),
            'formaspago' => FormasPago::where('activo',1)->selectRaw("CONCAT(forma_pago,' - ',descripcion) as forma_pago, id_forma_pago")->orderBy('forma_pago')->pluck('forma_pago','id_forma_pago'),
            'condicionespago' => CondicionesPago::where('activo',1)->select('condicion_pago','id_condicion_pago')->orderBy('condicion_pago')->pluck('condicion_pago','id_condicion_pago'),
            'usoscfdi' => UsosCfdis::where('activo',1)->selectRaw("CONCAT(uso_cfdi,' - ',descripcion) as uso_cfdi, id_uso_cfdi")->orderBy('uso_cfdi')->pluck('uso_cfdi','id_uso_cfdi'),
            'tiposrelacion' => TiposRelacionesCfdi::where('activo',1)->selectRaw("CONCAT(tipo_relacion,' - ',descripcion) as tipo_relacion, id_sat_tipo_relacion")->where('factura',1)->orderBy('tipo_relacion')->pluck('tipo_relacion','id_sat_tipo_relacion'),
            'facturasrelacionadas' =>FacturasClientes::selectRaw("CONCAT(serie,'-',folio,'  [',uuid,']') as factura, id_documento")->where('fk_id_estatus',3)->whereNotNull('uuid')->orderBy('factura')->pluck('factura','id_documento'),
            'js_certificados' => Crypt::encryptString('
                "select": ["id_empresa"],
                "conditions": [{"where": ["id_empresa",$id_empresa]}],
                "with": ["certificados"],
                "withFunction": [{
                "certificados": {
                    "selectRaw": ["id_certificado, no_certificado"],
                    "whereRaw": ["('.Carbon::now().' > fecha_expedicion) AND ('.Carbon::now().' < fecha_vencimiento) AND (activo = 1)"]
                    }
            }]'),
        ];
    }
    
    public function store(Request $request, $company, $compact = true)
    {
        $return = parent::store($request, $company, $compact);
        
        $datos = $return["entity"];

        if($datos) {
            $xml = generarXml($this->datos_cfdi($datos->id_documento));

            if(!empty($xml)) {
                $request->request->add(['xml_original'=>$xml]);
            }

            if($request->timbrar == true && !empty($xml))
                $timbrado = timbrar($xml);

            if(isset($timbrado) && $timbrado->return->status == '200') {
                if(in_array($timbrado->return->resultados->status,['200','307'])) {
                    $request->request->add([
                        'cadena_original'=>$timbrado->return->resultados->cadenaOriginal,
                        'certificado_sat'=>$timbrado->return->resultados->certificadoSAT,
                        'xml_timbrado'=>$timbrado->return->resultados->cfdiTimbrado,
                        'fecha_timbrado'=>str_replace('T',' ',substr($timbrado->return->resultados->fechaTimbrado,0,19)),
                        'sello_sat'=>$timbrado->return->resultados->selloSAT,
                        'uuid'=>$timbrado->return->resultados->uuid,
                        'version_tfd'=>$timbrado->return->resultados->versionTFD,
                        'codigo_qr'=>base64_encode($timbrado->return->resultados->qrCode),
                        'fk_id_estatus_cfdi' => 2
                    ]);
                }
                else
                    return parent::redirect('error_timbre');
            }
            else
                return parent::redirect('error_timbre');
            if(isset($request->relations))
                unset($request['relations']);

            $request->request->set('save',true);
            $id = $datos->id_documento;
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
            $xml = generarXml($this->datos_cfdi($datos->id_documento));

            if(!empty($xml)) {
                $request->request->add(['xml_original'=>$xml]);
            }

            #dd($xml['xml']);
            $timbrado = null;
            if($request->timbrar == true && !empty($xml))
                $timbrado = timbrar($xml);

            if(isset($timbrado) && $timbrado->return->status == '200') {
                if(in_array($timbrado->return->resultados->status,['200','307'])) {
                    $request->request->add([
                        'cadena_original'=>$timbrado->return->resultados->cadenaOriginal,
                        'certificado_sat'=>$timbrado->return->resultados->certificadoSAT,
                        'xml_timbrado'=>$timbrado->return->resultados->cfdiTimbrado,
                        'fecha_timbrado'=>str_replace('T',' ',substr($timbrado->resultados->fechaTimbrado,0,19)),
                        'sello_sat'=>$timbrado->return->resultados->selloSAT,
                        'uuid'=>$timbrado->return->resultados->uuid,
                        'version_tfd'=>$timbrado->return->resultados->versionTFD,
                        'codigo_qr'=>base64_encode($timbrado->return->resultados->qrCode),
                        'fk_id_estatus_cfdi' => 2
                    ]);
                }
                else
                    return parent::redirect('error_timbre');
            }
            else{
                return parent::redirect('error_timbre');
            }
            $request->request->set('save',true);
            $return = parent::update($request, $company, $id, $compact);
        }
        return $return["redirect"];
    }
    
    public function destroy(Request $request, $company, $idOrIds, $attributes = ['fk_id_estatus'=>3])
    {
        $ids = !is_array($idOrIds) ? [$idOrIds] : $idOrIds;
        
        foreach ($ids as $id)
        {
            $entity = $this->entity->where('fk_id_estatus','<>',3)->find($id);
            
            if(!empty($entity)) {
                $rfc = $entity->empresa->rfc;
                $uuid = $entity->uuid;
                $cer = $this->getfile($entity->empresa->conexion,$entity->certificado->certificado);
                $key = $this->getfile($entity->empresa->conexion,$entity->certificado->key);
                $pass = decrypt($entity->certificado->password);
                $email = $entity->empresa->email;
                
                $estatusCancelacion = confirmar_cancelacion($uuid);
                if($estatusCancelacion->status == 200) {
                    $entity->update($attributes);
                }
                
                $cancelacion = cancelar($rfc,$uuid,$cer,$key,$pass,$email);
                
                if($cancelacion->status == 200)
                {
                    $estatusCancelacion = confirmar_cancelacion($uuid);
                    if($estatusCancelacion->status == 200)
                        $entity->update($attributes);
                    else
                        dd($cancelacion);
                }
                else
                    dd($cancelacion);
            }
        }
        
        return parent::destroy($request, $company, $idOrIds, $attributes);
    }
    
    protected function getfile($empresa,$archivo)
    {
        $return = null;
        $file = Storage::disk('certificados')->getDriver()->getAdapter()->getPathPrefix().$empresa.'/'.$archivo;
        if (File::exists($file)) {
            $return = file_get_contents($file);
        }
        return $return;
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
                    'Importe' => number_format($row->cantidad * $row->precio_unitario,2,'.',''),
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

    public function descripciones()
    {
        $fk_id_cliente = \request()->fk_id_cliente;
        $id_clave_cliente_producto = \request()->id_clave_cliente_producto;

        $fk_id_sku = ClaveClienteProductos::find($id_clave_cliente_producto)->fk_id_sku;

        $sku_descripcion = Productos::select('descripcion')->where('id_sku',$fk_id_sku)->whereNotNull('descripcion');
        $sku_descripcion_cenefas = Productos::select('descripcion_cenefas')->where('id_sku',$fk_id_sku)->whereNotNull('descripcion_cenefas');
        $sku_descripcion_ticket = Productos::select('descripcion_ticket')->where('id_sku',$fk_id_sku)->whereNotNull('descripcion_ticket');
        $sku_descripcion_rack = Productos::select('descripcion_rack')->where('id_sku',$fk_id_sku)->whereNotNull('descripcion_rack');
        $sku_descripcion_cbn = Productos::select('descripcion_cbn')->where('id_sku',$fk_id_sku)->whereNotNull('descripcion_cbn');
        $upc_descripcion = Upcs::select('descripcion')->whereHas('skus',function ($query) use($fk_id_sku){
            $query->where('id_sku',$fk_id_sku);
        })->whereNotNull('descripcion');
        $cbn_descripcion = Cbn::select('descripcion')->whereHas('skus',function ($query) use ($fk_id_sku){
            $query->where('id_sku',$fk_id_sku);
        })->whereNotNull('descripcion');
        $clave_cliente = ClaveClienteProductos::
        select('descripcion')
            ->where('fk_id_cliente',$fk_id_cliente)
            ->where('fk_id_sku',$fk_id_sku)
            ->union($sku_descripcion)
            ->union($sku_descripcion_cenefas)
            ->union($sku_descripcion_ticket)
            ->union($sku_descripcion_rack)
            ->union($sku_descripcion_cbn)
            ->union($upc_descripcion)
            ->union($cbn_descripcion)
            ->get();
        return $clave_cliente;
    }
}