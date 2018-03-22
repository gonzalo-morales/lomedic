<html>
<body style="font-size: 12px">
    <div class="panel">
        <div class="panel-heading" style="background-color: #f4f4f4;">
            <span style="text-align: left"><b>RECETA MÉDICA</b></span>
            <div class="float-right">
                <span>Folio:</span>
                <span id="folio"><b>{{$receta->folio}}</b></span>
            </div>
        </div>
    <div class="panel-body">
        {{-- {!!HTML::image(asset("img/logotipos/$logo"),'',['height'=>'56'])!!}  --}}
        <img src="data:image/png;charset=binary;base64,{{$qr}}" style="float:right;width: 70px;margin-bottom: 0.5em;" />
        <div class="row margin-bottom">
            <div class="width-50 float-left text-center">
                <span class="titles">Nombre y clave de la unidad Médica:</span>
                <br><b>{{$receta->getUnidadMedicaAttribute()}}</b>
            </div>
            <div class="width-50 float-left text-center">
                <span class="titles">Médico:</span>
                <br><b>{{$receta->medico->getNombreCompletoAttribute()}}</b>
            </div>
        </div><br>
        <div class="row margin-bottom">
            <div class="width-25 float-left text-center">
                <span class="titles">Edad:</span>
                <br><b>{{$edad}}</b>
            </div>
            <div class="width-25 float-left">
                <span class="titles">Género:</span>
                <br>{{$receta->afiliacion->genero}}</b>
            </div>
            <div class="width-25 float-left text-center">
                <span class="titles">No. expediente y de afiliación:</span>
                <br><b>{{$receta->afiliacion->id_afiliacion}}, {{$receta->afiliacion->id_dependiente}}</b>
            </div>
            <div class="width-25 float-left text-center">
                <span class="titles">Nombre Paciente:</span>
                <br><b>{{$receta->afiliacion->id_afiliacion != null ? $receta->afiliacion->getFullNameAttribute() : $receta->nombre_paciente_no_afiliado}}</b>
            </div>
        </div><br>
        <div class="row margin-bottom">
            <div class="width-50 float-left text-center">
                <span class="titles">Fecha y hora de elaboración:</span>
                <br><b>{{$receta->fecha}}</b>
            </div>
            <div class="width-50 float-left text-center">
                <span class="titles">Clave y nombre del servicio:</span>
                <br><b>{{$receta->programa->nombre_programa}}</b>
            </div>
        </div><br><br>
        <hr style="color:#d8d8d8; margin-top:15px; margin-bottom: 15px;">
        <table>
            <thead>
            <tr>
                <th> </th>
                <th> </th>
            </tr>
            </thead>
            <tbody>
                @foreach($receta->detalles()->where('eliminar',0)->get() as $detalle)--}}
                <tr>
                    <th style="padding: 5px 10px;">{{$detalle->claveClienteProducto->sku->sku}}</th>
                    <td style="padding: 0px 5px;">
                        <p>{{$detalle->claveClienteProducto->sku->descripcion}}</p>
                        <ul>
                            <li>{{$detalle->dosis}}</li>
                            <li>{{isset($detalle->en_caso_presentar) ? $detalle->en_caso_presentar : '-----'}}</li>
                            <li>{{isset($detalle->recurrente)?'Recoger '.$detalle->cantidad_pedida.' cada '.$detalle->recurrente/24 .' días':''}}</li>
                        </ul>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

    </div><!--/panel-body-->
</div><!--/panel-->

</body>
</html>

<style type="text/css">
    h1,h2,h3,h4,h5,h6,div   {
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
        margin: 0.2em 0.3em;
    }
    table {
        border-spacing: 0;
        border-collapse: collapsed;
    }
    td{
        border: none;
        border-collapse: collapse;
    }
    table>tbody>tr>th{
        border-right: 3px solid #007bff;
        border-collapse: collapse;
    }
    ul li {
    color: black;
    list-style-type: none;
    }
    ul li::before{
        content: '\2022 ';
        color:#007bff;
    }
    .panel-heading {
        padding: 10px 15px;
        border-bottom: 1px solid #d8d8d8;
        border-top-left-radius: 3px;
        border-top-right-radius: 3px;
    }
    .panel {
        margin-bottom: 20px;
        background-color: #fff;
        border: 1px solid #d8d8d8;
        border-radius: 4px;
    }
    .panel-body {
        padding: 8px 10px;
    }
    .titles{
        color:#3f3f3f;
        font-weight: 300;
    }
    .row {
        margin-right: -15px;
        margin-left: -15px;
        width: 100%;
        display: table;
    }
    .float-left{
        float:left;
    }
    .float-right{
        float: right;
    }
    /*.text-center{
      text-align: center;
    }*/
    .margin-bottom{
        margin-bottom: 2.5em;
    }
    .width-16{
        width: 16.66666667%;
        position: relative;
        min-height: 1px;
        padding-right: 5px;
        padding-left: 5px;
    }
    .width-50{
        width: 47%;
        position: relative;
        min-height: 1px;
        padding-right: 5px;
        padding-left: 5px;
    }
    .width-25{
        width: 21%;
        position: relative;
        min-height: 1px;
        padding-right: 5px;
        padding-left: 5px;
    }
</style>
</body>
</html>