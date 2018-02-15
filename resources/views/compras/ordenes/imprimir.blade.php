<html>
<body>
<div style="">
    <div style="width: 100%">
        <?php $logo = $orden->empresa->logotipo_completo?>
            {!!HTML::image(asset("img/$logo"),'',['height'=>'56'])!!}
            <div style="width: 60.5%; float: left">
                <h4 align="center">Orden de compra</h4>
                <h6 align="center">
                    {{$orden->empresa->razon_social}}
                    <br><b>Dirección</b>
                    <br><b>Municipio, Est.</b>
                    <br><b>Tel:</b>
                    <br><b>RFC:</b>{{$orden->empresa->rfc}}
                </h6>
            </div>
            <div style="width: 20%; float: left">
                <table width="100%" style="">
                    <tr align="center">
                        <td style="font-size: 15px;">
                            <h4>No. orden  <b>{{$orden->id_documento}}</b></h4>
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
                                <b>Fecha creación: {{$orden->fecha_creacion}}</b>
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
                Proveedor: {{$orden->proveedor->nombre_corto}}
                &nbsp;
            </div>
            <div style="float: left;">
                Sucursal: {{$orden->sucursales->nombre}}
                &nbsp;
            </div>
            <div style="float: left;">
                Estatus: {{$orden->estatus->estatus}}
                &nbsp;
            </div>
        </h6>
    </div>
    <div style="width: 100%;clear: both">
        <table style="width: 100%;" class="detalle">
            <tr>
                <th><h5>SKU</h5></th>
                <th><h5>UPC</h5></th>
                <th><h5>Fecha límite</h5></th>
                <th><h5>Cliente</h5></th>
                <th><h5>Proyecto</h5></th>
                <th width="6%"><h5>Cantidad</h5></th>
                <th><h5>Precio unitario</h5></th>
                <th><h5>Tipo de impuesto</h5></th>
                <th><h5>Total</h5></th>
            </tr>
            {{--For each detalle--}}
            @foreach($orden->detalle()->where('cerrado','f')->get() as $detalle)
            <tr>
                <td>{{$detalle->sku->sku}}</td>
                <td>{{isset($detalle->upc) ? $detalle->upc->upc : 'UPC no seleccionado'}}</td>
                <td>{{isset($detalle->fecha_necesario) ? $detalle->fecha_necesario : 's.f.'}}</td>
                <td>{{isset($detalle->cliente) ? $detalle->cliente->nombre_corto : 'Sin cliente'}}</td>
                <td>{{isset($detalle->proyecto) ? $detalle->proyecto->proyecto : 'sin proyecto'}}</td>
                <td>{{$detalle->cantidad}}</td>
                <td>${{number_format($detalle->precio_unitario,2,'.',',')}}</td>
                <td>{{$detalle->impuesto->impuesto}}</td>
                <td>${{number_format($detalle->total,2,'.',',')}}</td>
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
                <td>Subtotal:</td>
                <td>${{$subtotal}}</td>
            </tr>
            <tr style="border: hidden">
                <td style="border: hidden"></td>
                <td style="border: hidden"></td>
                <td style="border: hidden"></td>
                <td style="border: hidden"></td>
                <td style="border: hidden"></td>
                <td style="border: hidden"></td>
                <td style="border: hidden"></td>
                <td>IVA:</td>
                <td>${{$iva}}</td>
            </tr>
            <tr style="border: hidden">
                <td colspan="7"><h3>*** {{$total_letra}} ***</h3></td>
                <td>Total</td>
                <td>${{$total}}</td>
            </tr>
        </table>
    </div>
    {{--<div style="width: 100%;clear: both">--}}
        {{--<div style="float: left">--}}
            {{--<p style="font-size: 12px; border-top:1px solid #000; margin-top: 80px; padding: 0px 10px;">--}}
                {{--{{$orden->empleado->nombre--}}
                {{--.' '.$orden->empleado->apellido_paterno--}}
                {{--.' '.$orden->empleado->apellido_materno}}--}}
            {{--</p>--}}
        {{--</div>--}}
    {{--</div>--}}
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