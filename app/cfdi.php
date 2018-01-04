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
        
            if(isset($datos['conceptos'])) {
                foreach ($datos['conceptos'] as $row) {
                    $detalle = $row;
                    if(isset($detalle['impuestos'])) {
                        unset($detalle['impuestos']);
                    }
                    
                    $concepto = new Concepto($detalle);
                    
                    $impuestos = $row['impuestos'] ?? [];
                    foreach ($impuestos as $tipo=>$impuesto) {
                        if($tipo == 'traslado') {
                            $concepto->add(new Traslado($impuesto[$tipo]));
                        }
                        elseif($tipo == 'retencion') {
                            $concepto->add(new Retencion($impuesto[$tipo]));
                        }
                    }

                    $cfdi->add($concepto);
                }
            }
            
            
            /*$TotalTrasladados = $entity->impuestos;
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
            */
            
            return collect(['xml' => $cfdi->getXML(), 'sello'=>$cfdi->getSello()]);
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