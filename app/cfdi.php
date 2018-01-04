<?php
use Charles\CFDI\CFDI;
use Charles\CFDI\Node\Relacionado;
use Charles\CFDI\Node\Emisor;
use Charles\CFDI\Node\Receptor;
use Charles\CFDI\Node\Concepto;
use Charles\CFDI\Node\Impuestos;
use Charles\CFDI\Node\Traslados;
use Charles\CFDI\Node\Impuesto\Traslado;
use Charles\CFDI\Node\Impuesto\Retencion;
use Illuminate\Support\Collection;
use Charles\CFDI\Node\Retenciones;

function generarXml($datos = [])
{
    if(!empty($datos)) {

        if(isset($datos['cfdi'])) {
            $cfdi = new CFDI($datos['cfdi'], $datos['certificado'], $datos['key']);
        
            if(isset($datos['emisor'])) {
                $cfdi->add(new Emisor($datos['emisor']));
            }
        
            if(isset($datos['receptor'])) {
                $cfdi->add(new Receptor($datos['receptor']));
            }
        
            
            $subtotal = 0;
            $descuentos = 0;
            $totalImpuestos = 0;
            $traslado = 0;
            $retencion = 0;
            $impuestos = [];
            if(isset($datos['conceptos'])) {
                foreach ($datos['conceptos'] as $row)
                {
                    $detalle = $row;
                    if(isset($detalle['impuestos'])) {
                        unset($detalle['impuestos']);
                    }
                    
                    $concepto = new Concepto($detalle);
                    
                    $subtotal = $subtotal + $detalle['Importe'];
                    $descuentos = $descuentos + ($detalle['Descuento'] ?? 0);
                    
                    $cImpuestos = $row['impuestos'][0] ?? [];
                    
                    foreach ($cImpuestos as $tipo=>$impuesto)
                    {
                        if($tipo == 'traslado') {
                            $nImpuesto = new Traslado($impuesto);
                            $traslado = $traslado + ($impuesto['Importe'] ?? 0);
                        }
                        elseif($tipo == 'retencion') {
                            $nImpuesto = new Retencion($impuesto);
                            $retencion = $retencion + ($impuesto['Importe'] ?? 0);
                        }
                        
                        $concepto->add($nImpuesto);
                        
                        $impuestos[$tipo][$impuesto['Impuesto']][$impuesto['TipoFactor']][$impuesto['TasaOCuota']] = $impuesto['Importe'] + 
                            ($impuestos[$tipo][$impuesto['Impuesto']][$impuesto['TipoFactor']][$impuesto['TasaOCuota']] ?? 0);
                        $totalImpuestos = $totalImpuestos + ($impuesto['Importe'] ?? 0);
                    }

                    $cfdi->add($concepto);
                }
            }
            
            $ImpuestosTotales = [];
            if(array_key_exists('traslado', $impuestos)) {
                $ImpuestosTotales['TotalImpuestosTrasladados'] = $traslado;
            }
            if(array_key_exists('retencion', $impuestos)) {
                $ImpuestosTotales['TotalImpuestosRetenidos'] = $retencion;
            }
            
            $nImpuestos = new Impuestos($ImpuestosTotales);
            
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
                $nImpuestos->add(new Traslados($addImpuestos['traslado']));
            }
            if(isseT($addImpuestos['retencion'])) {
                $nImpuestos->add(new Retenciones($addImpuestos['retencion']));
            }
            
            $cfdi->add($nImpuestos);
            
            $dattr = [
                'SubTotal' => $subtotal,
                'Total' => $subtotal - ($datos['cfdi']['Descuento'] ?? 0) + $totalImpuestos,
            ];
            
            if(isset($datos['cfdi']['Descuento']))
                $dattr['Descuento'] = $datos['cfdi']['Descuento'];
            
            $cfdi->putAtributes($dattr);
            
            return ['xml' => $cfdi->getXML(), 'sello'=>$cfdi->getSello()];
        }
    }
    return null;
}

function timbrar($xml)
{
    return wsdlService('timbrar',['cfdi'=>$xml],'solucionfactible');
}

function cancelar($uuid,$certificado)
{
    return wsdlService('cancelar',['uuid'=>$uuid,'cer'=>$certificado],'solucionfactible');
}