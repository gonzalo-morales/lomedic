<html>
<body>
<div style="">
    <div style="width: 100%">
        {{HTML::image(asset("img/logotipos/$empresa->logotipo"),'',['height'=>'56'])}}
        <div style="width: 60.5%; float: left">
            <h4 align="center">Solicitud de compra</h4>
            <h6 align="center">
                {{$empresa->razon_social}}
                <br><b>Calle Chicle 234, Col. El coli industrial</b>
                <br><b>Zapopan, Jalisco, CP 45069</b>
                <br><b>Tel: (33) 1002-1130</b>
                <br><b>RFC: AIS091015H50</b>{{$empresa->rfc}}
            </h6>
        </div>
        <div style="width: 20%; float: left">
            <table width="100%" style="">
                <tr align="center">
                    <td style="font-size: 15px;">
                        <h4>No. Solicitud  <b>{{$solicitud->id_documento}}</b></h4>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: center">
                        {{--                            <h6 align="center"><img src="{{$barcode}}"></h6>--}}
                        <img src="data:image/png;charset=binary;base64,{{$qr}}" style="float:none;width: 50px;" />
                    </td>
                </tr>
                <tr>
                    <td style="font-size: 15px;">
                        <h6 align="center">
                            <b>Fecha creación: {{$solicitud->fecha_creacion}}</b>
                        </h6>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: center">
                        <img src="data:image/png;charset=binary;base64,{{$barcode}}" style="float:none;" />
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <div style="width: 100%;clear: both;">
        <h6>
            <div style="float: left;">
                Solicitante: {{$solicitud->usuario->empleado->nombre
                .' '.$solicitud->usuario->empleado->apellido_paterno
                .' '.$solicitud->usuario->empleado->apellido_materno}}
                &nbsp;
            </div>
            {{--  {{dd($solicitud)}}  --}}
            <div style="float: left;">
                Sucursal: {{$solicitud->sucursales->sucursal}}
                &nbsp;
            </div>
            <div style="float: left;">
                Estatus: {{$solicitud->estatus->estatus}}
                &nbsp;
            </div>
        </h6>
    </div>
    <div style="width: 100%;clear: both">
        <table style="width: 100%;" class="detalle">
            <tr>
                <th><h5>Código de barras</h5></th>
                <th><h5>Producto</h5></th>
                <th><h5>Proveedor</h5></th>
                <th><h5>Fecha necesario</h5></th>
                <th><h5>Proyecto</h5></th>
                <th width="6%"><h5>Cantidad</h5></th>
                <th><h5>Unidad de medida</h5></th>
                <th><h5>Tipo de impuesto</h5></th>
                <th><h5>Precio unitario</h5></th>
                <th><h5>Total</h5></th>
            </tr>
            {{--For each detalle--}}
            @foreach($solicitud->detalle->where('eliminar',0) as $detalle)
            <tr>
                <td>{{$detalle->upc->upc ?? ''}}</td>
                <td>{{$detalle->upc->descripcion}}</td>
                <td>{{$detalle->proveedor->nombre_comercial ?? ''}}</td>
                <td>{{$detalle->fecha_necesario}}</td>
                <td>{{isset($detalle->fk_id_proyecto) ? $detalle->proyecto->proyecto : ''}}</td>
                <td>{{$detalle->cantidad}}</td>
                <td>{{$detalle->unidad_medida->nombre}}</td>
                <td>{{$detalle->impuesto->impuesto}}</td>
                <td>${{number_format($detalle->precio_unitario,2,'.',',')}}</td>
                <td>${{number_format($detalle->importe,2,'.',',')}}</td>
            </tr>
            @endforeach
            {{--End for each detalle--}}
            <tr><td style="border: hidden">&nbsp;</td></tr>
            <tr style="border: hidden">
                <td style="border: hidden"></td>
                <td style="border: hidden"></td>
                <td style="border: hidden"></td>
                <td style="border: hidden"></td>
                <td style="border: hidden"></td>
                <td style="border: hidden"></td>
                <td style="border: hidden"></td>
                <td style="border: hidden"></td>
                <td style="border: hidden"></td>
                <td>Subtotal:</td>
                <td>${{number_format($solicitud->total_subtotal,2,'.',',')}}</td>
            </tr>
            <tr style="border: hidden">
                <td style="border: hidden"></td>
                <td style="border: hidden"></td>
                <td style="border: hidden"></td>
                <td style="border: hidden"></td>
                <td style="border: hidden"></td>
                <td style="border: hidden"></td>
                <td style="border: hidden"></td>
                <td style="border: hidden"></td>
                <td style="border: hidden"></td>
                <td>IVA:</td>
                <td>${{number_format($solicitud->total_impuesto,2,'.',',')}}</td>
            </tr>
            <tr style="border: hidden">
                <td colspan="9"><h3>*** {{num2letras($solicitud->total_solicitud)}} ***</h3></td>
                <td>Total</td>
                <td>${{number_format($solicitud->total_solicitud,2,'.',',')}}</td>
            </tr>
        </table>
    </div>
    <div style="width: 100%;clear: both">
        <div style="float: left">
            <p style="font-size: 12px; border-top:1px solid #000; margin-top: 80px; padding: 0px 10px;">
                {{$solicitud->usuario->empleado->nombre
                .' '.$solicitud->usuario->empleado->apellido_paterno
                .' '.$solicitud->usuario->empleado->apellido_materno}}
            </p>
        </div>
    </div>
</div>
</body>
</html>

<style type="text/css">
    h1,h2,h3,h4,h5,h6{
        margin: 0;
    }
    img {
        margin:0;
        padding: 0;
        float: left;
        border:0
    }
    * {
        font-family: Arial,Helvetica Neue,Helvetica,sans-serif;
        /*font-size: 11px;*/
    }
    body {
        /*border: 1px solid #000000;*/
        margin: 0.2em 0.3em;
    }
    table {
        border-spacing: 0;
        border-collapse: collapsed;
    }
    td{
        border: 1px solid black;
        border-collapse: collapse;
        font-size: 11px;
    }
    th{
        border: 1px solid black;
        border-collapse: collapse;
    }
</style>