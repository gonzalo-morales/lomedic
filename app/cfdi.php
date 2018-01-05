<?php
use Charles\CFDI\CFDI;
use Charles\CFDI\Node\Relacionado;
use Charles\CFDI\Node\Emisor;
use Charles\CFDI\Node\Receptor;
use Charles\CFDI\Node\Concepto;
use Charles\CFDI\Node\Impuesto\Traslado;
use Charles\CFDI\Node\Impuesto\Retencion;
use App\Http\CFDI\Node\Impuestos;
use App\Http\CFDI\Node\Traslados;
use App\Http\CFDI\Node\Retenciones;

function generarXml($datos = [])
{
    if(!empty($datos)) {

        if(isset($datos['cfdi'])) {
            if(isset($datos['emisor'])) {
                $emisor = new Emisor($datos['emisor']);
            }
        
            if(isset($datos['receptor'])) {
                $receptor = new Receptor($datos['receptor']);
            }
            
            $subtotal = 0;
            $totalImpuestos = 0;
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
            
            $nImpuestos = new Impuestos($ImpuestosTotales);

            if(isseT($addImpuestos['traslado'])) {
                $nImpuestos->add(new Traslados($addImpuestos['traslado']));
            }
            if(isseT($addImpuestos['retencion'])) {
                $nImpuestos->add(new Retenciones($addImpuestos['retencion']));
            }
            
            $datos['cfdi']['SubTotal'] = $subtotal;
            $datos['cfdi']['Total'] = $subtotal - ($datos['cfdi']['Descuento'] ?? 0) + $totalImpuestos;
            
            $cfdi = new CFDI($datos['cfdi'], $datos['certificado'], $datos['key']);
            
            if(isset($emisor))
                $cfdi->add($emisor);
            
            if(isset($receptor))
                $cfdi->add($receptor);
            
            foreach ($conceptos as $node) {
                $cfdi->add($node);
            }
            
            $cfdi->add($nImpuestos);
            
            return $cfdi->getXML();
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