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
use Charles\CFDI\Node\Impuesto\Traslado;
use Charles\CFDI\Node\Impuestos;
use Charles\CFDI\Node\Traslados;

class FacturasClientesController extends ControllerBase
{
    public function __construct(FacturasClientes $entity)
	{
		$this->entity = $entity;
	}

	public function getDataView($entity = null)
    {
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
            'tiposrelacion' => TiposRelacionesCfdi::selectRaw("CONCAT(tipo_relacion,' - ',descripcion) as tipo_relacion, id_sat_tipo_relacion")->where('activo',1)->where('eliminar',0)->where('factura',1)->orderBy('tipo_relacion')->pluck('tipo_relacion','id_sat_tipo_relacion')->prepend('Selecciona una opcion...',''),
            'facturasrelacionadas' =>FacturasClientes::selectRaw("CONCAT(serie,'-',folio,'  [',uuid,']') as factura, id_factura")->whereNotNull('uuid')->orderBy('factura')->pluck('factura','id_factura')->prepend('Selecciona una opcion...',''),
        ];
    }
    
    public function store(Request $request, $company)
    {
        $return = parent::store($request, $company);
        
        $datos = $return["entity"];
        
        if($datos)
            $xml = $this->generarXml($datos->id_factura);
        
        return $return["redirect"];
    }
    
    public function update(Request $request, $company, $id)
    {
        $return = parent::update($request, $company, $id);
        
        $datos = $return["entity"];
        
        if($datos && $request->save !== true)
        {
            $xml = $this->generarXml($datos->id_factura);
            if($request->timbrar == true && !empty($xml))
                $this->timbrar($xml,$id);
        }
            
        return $return["redirect"];
    }
    
    public function generarXml($id)
    {
        $entity = $this->entity->findOrFail($id);
        
        if(!empty($entity)) {
            
             $cfdi = new CFDI([
                 'Version'=>'3.3',
                 'Serie' => $entity->serie,
                 'Folio' => $entity->folio,
                 'Fecha' => str_replace(' ','T',substr($entity->fecha_creacion,0,19)),
                 'FormaPago' => $entity->formapago->forma_pago,
                 'NoCertificado' => $entity->certificado->no_certificado,
                 'CondicionesDePago' => $entity->condicionpago->condicion_pago,
                 'SubTotal' => number_format($entity->subtotal,2,'.',''),
                 #'Descuento' => number_format($entity->descuento,2,'.',''),
                 'Moneda' => $entity->moneda->moneda,
                 'TipoCambio' => round($entity->tipo_cambio,4),
                 'Total' => number_format($entity->total,2,'.',''),
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
                 #'ResidenciaFiscal' => 'MXN',
                 #'NumRegIdTrib' => '121585958',
                 'UsoCFDI' => $entity->usocfdi->uso_cfdi,
             ]));
             
             $Impuestos = [];
             foreach ($entity->detalle as $row) {
                 $concepto = new Concepto([
                     'ClaveProdServ' => $row->claveproducto->clave_producto_servicio,
                     'NoIdentificacion' => $row->clavecliente->clave_producto_cliente,
                     'Cantidad' => $row->cantidad,
                     'ClaveUnidad' => $row->unidadmedida->clave_unidad,
                     'Unidad' => $row->unidadmedida->descripcion,
                     'Descripcion' => $row->descripcion,
                     'ValorUnitario' => number_format($row->precio_unitario,2,'.',''),
                     'Importe' => number_format($row->importe,2,'.',''),
                     #'Descuento' => number_format($row->descuento,2,'.',''),
                 ]);
                 
                 $impuesto = $concepto->add(new Traslado([
                     'Impuesto' => $row->impuestos->numero_impuesto,
                     'TipoFactor' => $row->impuestos->tipo_factor,
                     'TasaOCuota' => $row->impuestos->tasa_o_cuota,
                     'Importe' => number_format($row->impuesto,2,'.',''),
                     'Base' => number_format(($row->importe - $row->descuento),2,'.',''),
                 ]));
                 
                 $Impuestos[$row->impuestos->numero_impuesto][$row->impuestos->tipo_factor][$row->impuestos->tasa_o_cuota] = 
                 ($Impuestos[$row->impuestos->numero_impuesto][$row->impuestos->tipo_factor][$row->impuestos->tasa_o_cuota] ?? 0) + round($row->impuesto,2);
                 
                 $cfdi->add($concepto);
             }
             
             $TotalTrasladados = $entity->impuestos;
             $impuestos = new Impuestos(['TotalImpuestosTrasladados' => number_format($entity->impuestos,2,'.','')]);
             
             foreach ($Impuestos as $numero=>$Impuesto) {
                 foreach ($Impuesto as $tipo => $TImpuesto) {
                     foreach ($TImpuesto as $tasa => $importe) {
                         $impuestos->add(new Traslados([
                             'Impuesto' => $numero,
                             'TipoFactor' => $tipo,
                             'TasaOCuota' => $tasa,
                             'Importe' => number_format($importe,2,'.',''),
                         ]));
                         $TotalTrasladados = $TotalTrasladados + $importe;
                     }
                 }
             }
             
             $cfdi->add($impuestos);
             
             $request = request();
             
             $request->request->set('xml_original',$cfdi->getXML());
             $request->request->set('sello_cdfi',$cfdi->getSello());
             $request->request->set('save',true);
             
             $this->update($request, request()->company, $id);
             
             return $cfdi->getXML();
        }
        return null;
    }
    
    public function timbrar($xml,$id)
    {
        $return = timbrar(['cfdi'=>$xml]);
        
        if($return->status == '200') {
            if(in_array($return->resultados->status,['200','307'])){
                $request = request();
                $request->request->set('cadena_original',$return->resultados->cadenaOriginal);
                $request->request->set('certificado_sat',$return->resultados->certificadoSAT);
                $request->request->set('xml_timbrado',$return->resultados->cfdiTimbrado);
                $request->request->set('fecha_timbrado',str_replace('T',' ',substr($return->resultados->fechaTimbrado,0,19)));
                
                $request->request->set('sello_sat',$return->resultados->selloSAT);
                $request->request->set('uuid',$return->resultados->uuid);
                $request->request->set('version_tfd',$return->resultados->versionTFD);
                $request->request->set('codigo_qr',base64_encode($return->resultados->qrCode));
                $request->request->set('save',true);
                
                $this->update($request, request()->company, $id);
            }
            else
                dd($return->resultados->mensaje);
        }
        else
            dd($return);

        
        return $return;
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