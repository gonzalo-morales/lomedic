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
        font-size:16px;
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
    td{
        border: 1px solid #ccc;
        border-collapse: collapse;
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
        font-size: 100%;
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
        font-size: 20px;
        text-align:left;
        margin-top:30px;
        color:#a94442;
    }
    .farmacies{
        font-size: 20px;

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
<body style="font-size: 12px">
{{--@for($i = 1 ; $i <= 4 ; $i++)--}}

   <br>
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
       <div class="width-25 float-left text-center panel border-with-radius" style="height:90px;">
           <div class="panel-heading" style="background-color: #eb742f;">
               <span style="color:white;"><b>FOLIO:</b></span>
           </div>
           <img src="img/b.png" />
           <span id="folio"><b>asdf</b></span>
       </div>
   </div>
   <br>
   <div class="row margin-bottom" style="margin-top:40px;">
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
        <div class="width-10 float-left text-center form-control" id="fecha" style="width:120px;">12/12/2018</div>
   </div>
   <br>
   <div class="row margin-bottom">
        <div class="float-left text-center input-group-addon" style="width: 150px;">Nombre del paciente</div>
        <div class="width-50 float-left text-center form-control" id="paciente">HERNANDO FERNANDO HERNÁNDEZ FERNÁNDEZ QUINTO DE LA NOVENA DINASTÍA</div>
        <table class="float-left width-50">
            <tbody>
                <tr>
                    <td>
                    <div class="align-center" style="background-color: #eb742f;">
                        <span style="color:white;"><b>Edad:</b></span>
                    </div>
                        Códigos
                    </td>
                    <td>
                        <div class="align-center" style="background-color: #eb742f;">
                            <span style="color:white;"><b>Patente:</b></span>
                        </div>
                        Cantidad
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="align-center" style="background-color: #eb742f;">
                            <span style="color:white;"><b>Sexo:</b></span>
                        </div>
                        Códigos
                    </td>
                    <td>
                        <div class="align-center" style="background-color: #eb742f;">
                            <span style="color:white;"><b>Tipo de usuario:</b></span>
                        </div>
                        Cantidad
                    </td>
                </tr>
            </tbody>
        </table>
    </div><br>
    <div class="row margin-bottom" style="margin-top:50px;">
        <div class="float-left text-center input-group-addon" style="width: 150px;">Nombre del titular</div>
        <div class="width-50 float-left text-center form-control" id="titular">FERNANDO HERNANDO HERNÁNDEZ FERNÁNDEZ QUINTO DE LA DÉCIMA DINASTÍA</div>
    </div><br>
    <div class="row margin-bottom">
        <div class="float-left text-center input-group-addon" style="width: 150px;">Nombre del médico</div>
        <div class="width-50 float-left text-center form-control" id="medico">Brhadaranyakopanishadvivekachudamani Erreh Muñoz Castillo</div>
    </div><br>
    <div class="row margin-bottom">
        <div class="float-left text-center input-group-addon" style="width: 150px;">Diagnóstico</div>
        <div class="width-50 float-left text-center form-control" id="diagnostico">Espina bífida cervical sin hidrocéfalo Q05.6 Espina bífida torácica sin hidrocéfalo</div>
    </div><br>
   <div class="row margin-bottom">
       <div class="width-100 float-left m-1">
           <table>
               <thead>
               <tr>
                   <th>Código</th>
                   <th>Cantidad</th>
                   <th>Descripción</th>
               </tr>
               </thead>
               <tbody>
                   <tr>
                       <th>Códigos</th>
                       <td>Cantidad</td>
                       <td>asdfasfdasdf</td>
                   </tr>
                   <tr>
                       <th></th>
                       <td></td>
                       <td>
                           <div class="firma">
                               <div id="firma" style="height:80px;"></div>
                               Firma y sello de autorización
                           </div>
                       </td>
                   </tr>
               </tbody>
           </table>
       </div>
    </div><br><br><br><br><br><br>
    <div class="row margin-bottom">
        <div class="float-left text-center input-group-addon" style="width: 150px;">Observaciones</div>
        <div class="width-100 float-left text-center form-control" id="observaciones">El aspecto de la cara se encuentra determinado por las modificaciones que en ella imprime las enfermedades. Puede reflejarse reacciones de miedo o estados de ánimo: alegría, tristeza, dolor. Tipos de facies: hipocrática, ictérica, anémica, tiroidea, cushingoide, acromegalia, mixedema.</div>
    </div>
    <div class="row margin-bottom">
        <div class="float-left text-center input-group-addon" style="width: 250px;">Firma del responsable de la farmacia</div>
        <div class="width-100 float-left text-center form-control"></div><br><br>
        <p class="float-left">Nota: La vigencia de este vale es de 5 días naturales a partir de la fecha de expedición.</p>
    </div><br><br>
    <div class="row margin-bottom">
        <div class="float-left text-center input-group-addon" style="width: 250px;">Firma del responsable del paciente</div>
        <div class="width-100 float-left text-center form-control"></div><br><br>
        <p class="float-left">Original: IPEJAL, Copia rosa: ABISA, Copia amarilla: Proveedor, Copia verde: Paciente</p>
   </div>

   {{--  @if($i != 4)
       <div class="breakNow"></div>
   @endif  --}}


{{--@endfor--}}
</body>
</html>
