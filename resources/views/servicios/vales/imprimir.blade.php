<html>
<style type="text/css">
    h1,h2,h3,h4,h5,h6,div   {
        margin: 0;
    }
    img {
        margin:0;
        padding: 0;
        float: left;
        border:0;
        width:150px;
    }
    * {
        font-family: Arial,Helvetica Neue,Helvetica,sans-serif;
        font-size:12px;
    }
    thead{
        background:#eb742f;
        color:white;
    }
    table {
        border-spacing: 0;
        border-collapse: collapsed;
        width: 100%;
    }
    table>tbody>tr>th, td{
        border: 1px solid #ccc;
        border-collapse: collapse;
        text-align: left;
    }
    .panel-heading {
        padding: 10px 15px;
        border-bottom: 1px solid #919191;
        border-top-left-radius: 20px;
        border-top-right-radius: 20px;
    }
    .panel {
        margin-bottom: 20px;
        background-color: #fff;
        border: 1px solid #919191;
        border-radius: 20px;
    }
    .panel-body {
        padding: 8px 10px;
    }
    .row {
        margin-right: -15px;
        margin-left: -20px;
        width: 90%;
        display: block;
        /*overflow: hidden;*/
        height: 30px;
    }
    .float-left{
        float:left;
    }
    .float-right{
        float: right;
    }
    .margin-bottom{
        margin-bottom: 1em;
    }
    .width-10{
        width: 10%;
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
    .width-75{
        width: 65%;
        position: relative;
        min-height: 1px;
        padding-right: 5px;
        padding-left: 5px;
    }
    .width-100{
        width: 90%;
        position: relative;
        min-height: 1px;
        padding-right: 5px;
        padding-left: 5px;
    }
    .m-1{
        margin-bottom:0.5em;
    }
    .input-group {
        display: block;
        position: relative;
        border-collapse: separate;
        float:left;
        border-radius: 4px;
        padding-left:0;
        padding-right:0;
        text-align:center;
    }
    .input-group-addon {
        padding: 6px 12px;
        font-weight: 400;
        position:relative;
        float:left;
        line-height: 1;
        color: #fff;
        text-align: center;
        background-color: #eb742f;
        border: 1px solid #ccc;
        border-top-left-radius: 10px;
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
        border-bottom-left-radius: 10px;
        white-space: nowrap;
    }
    .input-group-addon-2 {
        padding: 6px 12px;
        font-weight: 400;
        position:relative;
        float:left;
        line-height: 1;
        color: #fff;
        text-align: center;
        background-color: #eb742f;
        border: 1px solid #ccc;
        border-top-left-radius: 0;
        border-top-right-radius: 10px;
        border-bottom-right-radius: 10px;
        border-bottom-left-radius: 0;
        white-space: nowrap;
    }
    .input-group .form-control {
        position: relative;
        z-index: 2;
        float: left;
        width: 100%;
        margin-bottom: 0;
        border: 1px solid #ccc;
        border-top-left-radius: 0;
        border-top-right-radius: 10px;
        border-bottom-right-radius: 10px;
        border-bottom-left-radius: 0;
        padding:5px;
        font-weight: 600;
        white-space: normal;
        text-align: left;
        word-break: break-all;
    }
    .border-with-radius{
        padding-left:0;
        padding-right:0;
        text-align:center;
    }
    .border-with-radius img{
        float:left;
        width:30px;
        margin-bottom: 0.5em;
        margin-top:0.5em;
        margin-left:15px;
    }
    #folio{
        font-size: 22px !important;
        text-align:left;
        color:#a94442;
    }
    .farmacies{
        font-size: 18px;

    }
    .farmacies::after{
        content: " ";
        padding-left:10px;
        padding-right:10px;
        padding-top:10px;
        padding-bottom:10px;
        border:1px solid #ccc;
        margin-left:7px;
    }
    .firma{
        text-align: center;
        font-size: 10px;
    }
    .align-center{
        text-align:center;
    }
    /*@import '/receta.css'*/
    /*div.breakNow { page-break-inside:avoid; page-break-after:always; }*/
</style>
<body>
    {{--{{$info_vale}}--}}
{{--@for($i = 1 ; $i <= 4 ; $i++)--}}
   <div class="row margin-bottom">
       <div class="width-10 float-left text-center">
           <img src="img/logotipos/ipejal.png" />
       </div>
       <div class="width-10 float-left text-center">
           <img src="img/logotipos/abisa_logo_bg.png" />
       </div>
       <div class="width-10 float-left text-center">
           <img src="img/logotipos/benavides.png" />
       </div>
       <div class="width-25 float-left text-center panel border-with-radius" style="height:70px;">
           <div class="panel-heading" style="background-color: #eb742f;padding:5px 10px;">
               <span style="color:white;"><b>FOLIO:</b></span>
           </div>
           <img src="img/b.png" style="height:20px; width:15px;"/>
           <span id="folio"><b>{{$vale->id_vale}}</b></span>
       </div>
   </div>
   <br>
   <div class="row margin-bottom" style="margin-top:10px;">
        <div class="width-25 float-left text-center">
            <div class="farmacies">Federalismo</div>
        </div>
        <div class="width-25 float-left text-center">
            <div class="farmacies">Javier Mina</div>
        </div>
        <div class="width-25 float-left text-center">
            <div class="farmacies">Pila Seca</div>
        </div>
        <div class="width-25 float-left text-center input-group-addon">Fecha de expedición</div>
        <div class="width-10 float-left text-center form-control" id="fecha" style="width:160px; font-size:18px;padding:2px; border:1px solid #ccc;"><b>{{$vale->fecha_surtido}}</b></div>
   </div>
   <div class="row margin-bottom">
        <table class="float-left width-100">
            <tbody>
                <tr>
                    <td style="width: 150px; border:none;">
                        <div class="text-center input-group-addon" style="width:148px;">Nombre del paciente</div>
                    </td>
                    <td style="padding: 5px 10px;" id="paciente">
                        {{$paciente}}
                    </td>
                </tr>
                <tr>
                    <td style="width: 150px; border:none;">
                        <div class="text-center input-group-addon" style="width:148px;">Nombre del titular</div>
                    </td>
                    <td style="padding: 5px 10px;" id="titular">
                        @if( $titular != '')
                            {{$titular}}
                        @else
                            &nbsp;
                        @endif
                    </td>
                </tr>
                <tr>
                    <td style="width: 160px; border:none;">
                        <div class="text-center input-group-addon" style="width:148px;">Nombre del medico</div>
                    </td>
                    <td style="padding: 5px 10px;" id="medico">
                       {{$receta->medico->NombreCompleto}}
                    </td>
                </tr>
                <tr>
                    <td style="width: 150px; border:none;">
                        <div class="text-center input-group-addon" style="width:148px;">Diagnóstico:</div>
                    </td>
                    <td style="padding: 5px 10px;" id="diagnostico">
                       {{$receta->diagnostico->diagnostico}}
                    </td>
                </tr>
            </tbody>
        </table>
        <table class="width-25 float-left">
            <tbody>
                <tr>
                    <td>
                        <div class="align-center" style="background-color: #eb742f;">
                            <span style="color:white;"><b>Edad:</b></span>
                        </div>
                        @if( $edad != '')
                            {{$edad}}
                        @else
                            &nbsp;
                        @endif

                    </td>
                    <td>
                        <div class="align-center" style="background-color: #eb742f;">
                            <span style="color:white;"><b>Patente:</b></span>
                        </div>
                        @if( $receta->pantente != '')
                            {{ $receta->pantente }}
                        @else
                            &nbsp;
                        @endif

                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="align-center" style="background-color: #eb742f;">
                            <span style="color:white;"><b>Sexo:</b></span>
                        </div>
                        @if( $genero != '')
                            {{$genero}}
                        @else
                            &nbsp;
                        @endif

                    </td>
                    <td>
                        <div class="align-center" style="background-color: #eb742f;">
                            <span style="color:white;"><b>Tipo de usuario:</b></span>
                        </div>
                        @if( $parentesco != '')
                            {{$parentesco}}
                        @else
                            &nbsp;
                        @endif

                    </td>
                </tr>
            </tbody>
        </table>
    </div><br><br><br><br><br>
   <div class="row">
       <div class="float-left m-1" style="width:100%;">
           <table class="text-center">
               <thead>
               <tr>
                   <th class="align-center">Código</th>
                   <th class="align-center">Descripción</th>
                   <th class="align-center">Cantidad</th>
               </tr>
               </thead>
               <tbody>

                    @foreach(@$vale->detalles as $detalle)
                        <tr>
                            <th>{{$detalle->claveClienteProducto->clave_producto_cliente}}</th>
                            <td>{{$detalle->claveClienteProducto->sku['descripcion']}}</td>
                            <td>{{$detalle->cantidad_surtida}}</td>
                        </tr>
                    @endforeach
                    <tr>
                       <th style="border:none;"></th>
                       <td style="border:none;"></td>
                       <td>
                           <div class="firma">
                               <div id="firma" style="height:50px;"></div>
                               Firma y sello de autorización
                           </div>
                       </td>
                   </tr>
               </tbody>
           </table>
       </div>
    </div>
    <br><br><br><br><br><br><br><br>
    <div class="row margin-bottom">
        <table class="float-left" style="width:100%;">
            <tbody>
                <tr>
                    <td style="width: 150px; border:none;">
                        <div class="text-center input-group-addon" style="width:148px;">Observaciones</div>
                    </td>
                    <td style="padding: 5px 10px;" id="observaciones">
                        @if( $vale->observaciones != '')
                            {{$vale->observaciones}}
                        @else
                            &nbsp;
                        @endif
                    </td>
                </tr>
            </tbody>
        </table>
    </div><br>
    <div class="row margin-bottom">
        <table class="float-left width-100">
            <tbody>
                <tr>
                    <td style="width: 150px; border:none;">
                        <div class="text-center input-group-addon">Firma del responsable de la farmacia</div>
                    </td>
                    <td style="padding: 5px 10px; width:235px;">
                        <span style="color:#ebebeb;">FIRMA</span>
                    </td>
                    <td style="padding: 5px 10px; width:235px;">
                        <span style="color:#ebebeb;">FIRMA</span>
                    </td>
                    <td style="width: 150px; border:none;">
                        <div class="text-center input-group-addon-2">Firma del responsable del paciente</div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div>
        <span style="text-align:left;">Nota: La vigencia de este vale es de 5 días naturales a partir de la fecha de expedición.</span>
        <span style="text-align:right;">Original: IPEJAL, Copia rosa: ABISA, Copia amarilla: Proveedor, Copia verde: Paciente</span>
    </div>

   {{--  @if($i != 4)
       <div class="breakNow"></div>
   @endif  --}}


{{--@endfor--}}
</body>
</html>
