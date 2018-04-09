<?php

function activacion_medicamentos($Farmacia, $FechaInicio, $FechaFin, $Clave, $Pagina)
{
    return wsdlService('activacion_medicamentos',[
        'Farmacia'      => $Farmacia,
        'FechaInicio'   => $FechaInicio,
        'FechaFin'      => $FechaFin,
        'Clave'         => $Clave,
        'Pagina'        => $Pagina
    ], 'ipejal');
}

function afectacion_almacen($Accion, $TipoMovimiento, $Folio, $Farmacia, $Fecha, $Concepto, $FolioUnico, $xmlDetalle)
{
    return wsdlService('afectacion_almacen',[
        'Accion'            => $Accion,
        'TipoMovimiento'    => $TipoMovimiento,
        'Folio'             => $Folio,
        'Farmacia'          => $Farmacia,
        'Fecha'             => $Fecha,
        'Concepto'          => $Concepto,
        'FolioUnico'        => $FolioUnico,
        'xmlDetalle'        => $xmlDetalle
    ], 'ipejal');
}

function cancelacion($TipoMovimiento, $FolioUnico, $Farmacia, $Fecha)
{
    return wsdlService('cancelacion',[
        'TipoMovimiento'=> $TipoMovimiento,
        'FolioUnico'    => $FolioUnico,
        'Farmacia'      => $Farmacia,
        'Fecha'         => $Fecha
    ], 'ipejal');
}

function consulta_folio($farmacia, $folio, $sufijo)
{
    return wsdlService('consulta_folio',[
        'farmacia' => $farmacia,
        'folio'    => $folio,
        'sufijo'   => $sufijo
    ], 'ipejal');
}

function consulta_paciente($farmacia, $patente, $numeroExpediente, $Pagina)
{
    return wsdlService('consulta_paciente',[
        'farmacia'           => $farmacia,
        'patente'            => $patente,
        'numeroExpediente'   => $numeroExpediente,
        'Pagina'             => $Pagina
    ], 'ipejal');
}

function consulta_periodo($farmacia, $fechaInicio, $fechaFin, $Pagina)
{
    return wsdlService('consulta_periodo',[
        'farmacia'           => $farmacia,
        'fechaInicio'        => $fechaInicio,
        'fechaFin'           => $fechaFin,
        'Pagina'             => $Pagina
    ], 'ipejal');
}

function getBitacora()
{
    return wsdlService('getBitacora',[], 'ipejal');
}

function surtido_clave($Accion, $FolioReceta, $Sufijo, $Farmacia, $FechaSurtido, $FolioSalida, $xmlClaveReceta)
{
    return wsdlService('surtido_clave',[
        'Accion'            => $Accion,
        'FolioReceta'       => $FolioReceta,
        'Sufijo'            => $Sufijo,
        'Farmacia'          => $Farmacia,
        'FechaSurtido'      => $FechaSurtido,
        'FolioSalida'       => $FolioSalida,
        'xmlClaveReceta'    => $xmlClaveReceta
    ], 'ipejal');
}