<?php
use Charles\CFDI\CFDI;
use Charles\CFDI\Node\Emisor;
use Charles\CFDI\Node\Receptor;
use Charles\CFDI\Node\Concepto;
use Charles\CFDI\Node\Impuesto\Traslado;
use Charles\CFDI\Node\Impuesto\Retencion;
use App\Http\CFDI\Node\Impuestos;
use App\Http\CFDI\Node\Traslados;
use App\Http\CFDI\Node\Retenciones;
use Charles\CFDI\Node\CuentaPredial;
use Charles\CFDI\Node\InformacionAduanera;
use App\Http\CFDI\Node\Relacionados;
use App\Http\CFDI\Node\Relacionado;

function generarXml($datos = [])
{
    if(!empty($datos)) {

        if(isset($datos['cfdi'])) {
            
            $relacionados = [];
            if(isset($datos['relacionados'])) {
                foreach($datos['relacionados'] as $tipo =>$relaciones)
                {
                    $tiporelacion = new Relacionados(['TipoRelacion'=>$tipo]);
                    foreach($relaciones as $relacion) {
                        $tiporelacion->add(new Relacionado($relacion));
                    }
                    
                    $relacionados[] = $tiporelacion;
                }
            }
            
            if(isset($datos['emisor'])) {
                $emisor = new Emisor($datos['emisor']);
            }
        
            if(isset($datos['receptor'])) {
                $receptor = new Receptor($datos['receptor']);
            }
            
            $subtotal = 0;
            $totalImpuestos = 0;
            $impuestos = [];
            $descuentos = 0;
            if(isset($datos['conceptos'])) {
                foreach ($datos['conceptos'] as $row)
                {
                    $detalle = $row;
                    if(isset($detalle['impuestos'])) {
                        unset($detalle['impuestos']);
                    }
                    
                    $descuentos = $descuentos + ($detalle['Descuento'] ?? 0);
                    
                    $concepto = new Concepto($detalle);
                    
                    $subtotal = $subtotal + $detalle['Importe'];
                    
                    $cImpuestos = $row['impuestos'][0] ?? [];
                    
                    foreach ($cImpuestos as $tipo=>$impuesto)
                    {
                        if($tipo == 'traslado') {
                            $nImpuesto = new Traslado($impuesto);
                        }
                        elseif($tipo == 'retencion') {
                            $nImpuesto = new Retencion($impuesto);
                        }
                        
                        $concepto->add($nImpuesto);
                        
                        $impuestos[$tipo][$impuesto['Impuesto']][$impuesto['TipoFactor']][$impuesto['TasaOCuota']] = $impuesto['Importe'] + 
                            ($impuestos[$tipo][$impuesto['Impuesto']][$impuesto['TipoFactor']][$impuesto['TasaOCuota']] ?? 0);
                        $totalImpuestos = $totalImpuestos + ($impuesto['Importe'] ?? 0);
                    }
                    
                    if(isset($row['cuentapredial']))
                        $concepto->add(new CuentaPredial(['Numero' => $row['cuentapredial']]));
                    
                    if(isset($row['pedimento']))
                        $concepto->add(new InformacionAduanera(['NumeroPedimento' => $row['pedimento']]));
                    
                    $conceptos[] = $concepto;
                }
            }
            
            $addImpuestos = [];
            foreach ($impuestos as $tipo=>$imp) {
                foreach ($imp as $nImpuesto=>$Factor) {
                    foreach ($Factor as $TipoFactor=>$Tasa) {
                        foreach ($Tasa as $TasaOCuota=>$Importe) {
                            $addImpuestos[$tipo] = [
                                'Impuesto' => $nImpuesto,
                                'TipoFactor' => $TipoFactor,
                                'TasaOCuota' => $TasaOCuota,
                                'Importe' => $Importe,
                            ];
                        }
                    }
                }
            }
            
            if(isseT($addImpuestos['traslado'])) {
                $ImpuestosTotales['TotalImpuestosTrasladados'] = $addImpuestos['traslado']['Importe'];
            }
            if(isseT($addImpuestos['retencion'])) {
                $ImpuestosTotales['TotalImpuestosTrasladados'] = $addImpuestos['retencion']['Importe'];
            }
            
            if(isset($ImpuestosTotales))
                $nImpuestos = new Impuestos($ImpuestosTotales);

            if(isset($addImpuestos['traslado'])) {
                $nImpuestos->add(new Traslados($addImpuestos['traslado']));
            }
            if(isset($addImpuestos['retencion'])) {
                $nImpuestos->add(new Retenciones($addImpuestos['retencion']));
            }
            
            if($descuentos != 0)
                $datos['cfdi']['Descuento'] = number_format($descuentos,2);
            
            $datos['cfdi']['SubTotal'] = number_format($subtotal,2);
            $datos['cfdi']['Total'] = number_format($subtotal - $descuentos + $totalImpuestos,2);
            
            $cfdi = new CFDI($datos['cfdi'], $datos['certificado'], $datos['key']);
            
            if(isset($relacionados)) {
                foreach ($relacionados as $node) {
                    $cfdi->add($node);
                }
            }
            
            if(isset($emisor))
                $cfdi->add($emisor);
            
            if(isset($receptor))
                $cfdi->add($receptor);
            
            if(isset($conceptos)) {
                foreach ($conceptos as $node) {
                    $cfdi->add($node);
                }
            }
            
            if(isset($nImpuestos))
                $cfdi->add($nImpuestos);
            
            return $cfdi->getXML();
        }
    }
    return null;
}

function timbrar($xml)
{
    return wsdlService('timbrar',['cfdi'=>$xml],'solucionfactible_timbrado');
}

function cancelar($rfc,$uuid,$cer,$key,$pass,$email)
{
    return wsdlService('cancelarAsincrono',['rfcEmisor'=>$rfc,'uuid'=>$uuid,'csdCer'=>$cer,'csdKey'=>$key,'csdPassword'=>$pass,'emailNotifica'=>$email],'solucionfactible_cancelacion');
}

function confirmar_cancelacion($uuid)
{
    return wsdlService('getStatusCancelacionAsincrona',['transactionId'=>$uuid],'solucionfactible_cancelacion');
}