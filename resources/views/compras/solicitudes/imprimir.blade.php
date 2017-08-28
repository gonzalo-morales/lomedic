<html>
<body>
<div style="">
    <div style="width: 100%">
            {{HTML::image(asset("img/$empresa->logotipo_completo"),'',['height'=>'56'])}}
            <div style="width: 56.5%; float: left">
                <h4 align="center">Solicitud de compra</h4>
                <h6 align="center">
                    {{$empresa->razon_social}}
                    <br><b>Dirección</b>
                    <br><b>Municipio, Est.</b>
                    <br><b>Tel:</b>
                    <br><b>RFC:</b>{{$empresa->rfc}}
                </h6>
            </div>
            <div style="width: 23.8%; float: left">
                <table width="100%" style="">
                    <tr align="center">
                        <td style="">
                            <h6>Orden de compra</h6>
                        </td>
                    </tr>
                    <tr align="center">
                        <td style="">
                            <h6>No° 1</h6>
                        </td>
                    </tr>
                    <tr>
                        <td style="">
                            <h6>Código de barras</h6>
                        </td>
                    </tr>
                    <tr>
                        <td style="">
                            <h6><b>Fecha/hora: </b>
                                <br><b>Fecha necesidad:</b>
                            </h6>
                        </td>
                    </tr>
                </table>
            </div>
    </div>
    <div style="width: 100%;clear: both;">
        <h6>
            <div style="float: left;">
                Solicitante:
            </div>
            <div style="float: left;">
                Sucursal:
            </div>
            <div style="float: left;">
                Estatus:
            </div>
        </h6>
    </div>
    <div style="width: 100%;clear: both">
        <table style="width: 100%;" class="detalle">
            <tr>
                <th><h5>SKU</h5></th>
                <th><h5>Código de barras</h5></th>
                <th><h5>Proveedor</h5></th>
                <th><h5>Fecha necesidad</h5></th>
                <th><h5>Proyecto</h5></th>
                <th width="6%"><h5>Cantidad</h5></th>
                <th><h5>Unidad de medida</h5></th>
                <th><h5>Tipo de impuesto</h5></th>
                <th><h5>Precio unitario</h5></th>
                <th><h5>Total</h5></th>
            </tr>
            {{--For each detalle--}}
            <tr>
                <td><h6>sku</h6></td>
                <td><h6>código</h6></td>
                <td><h6>proveedor</h6></td>
                <td><h6>fecha necesidad</h6></td>
                <td><h6>proyecto</h6></td>
                <td><h6>cantidad</h6></td>
                <td><h6>unidad de medida</h6></td>
                <td><h6>tipo de impuesto</h6></td>
                <td><h6>precio</h6></td>
                <td><h6>total</h6></td>
            </tr>
            {{--End for each detalle--}}
            <tr style="border: hidden">
                <td style="border: hidden"></td>
                <td style="border: hidden"></td>
                <td style="border: hidden"></td>
                <td style="border: hidden"></td>
                <td style="border: hidden"></td>
                <td style="border: hidden"></td>
                <td style="border: hidden"></td>
                <td style="border: hidden"></td>
                <td>Subtotal</td>
                <td>0.00</td>
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
                <td>IVA</td>
                <td>0.00</td>
            </tr>
            <tr style="border: hidden">
                <td colspan="8">*** PESOS 00/100 MXN ***</td>
                <td>Total</td>
                <td>0.00</td>
            </tr>
        </table>
    </div>
    <div style="width: 100%;clear: both">

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
    }
    body {
        border: 1px solid #000;
        margin: 0.2em 0.3em;
    }
    table {
        border-spacing: 0;
        border-collapse: collapsed;
    }
    td{
        border: 1px solid black;
        border-collapse: collapse;
    }
    th{
        border: 1px solid black;
        border-collapse: collapse;
    }
</style>