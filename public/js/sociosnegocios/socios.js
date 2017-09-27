// $(document.querySelector('table')).dataTable();
// $(document.querySelector('table')).dataTable();
var socioTemplate = {
    'activo' : '',
    'razon_social' : '',
    'rfc' : '',
    'nombre_corto' : '',
    'ejecutivo_venta' : '',
    'telefono' : '',
    'sitio_web' : '',
    'ramo' : '',
    'pais_origen' : '',
    'tipo_socio' : '',
    'moneda' : '',
    'empresas' : [{}],
    'condiciones_pago' : {
        'monto_credito' : '',
        'dias_credito' : '',
        'forma_pago' : '',
        'cuentas' : [{}],
    },
    'info_entrega' : {
        'tipo_entrega' : '',
        'sucursal' : '',
        'monto_minimo_facturacion' : '',
        'correos' : [{}],
    },
    'contactos' : {
        'contactos' : [{}]
    },
    'direcciones' : {
        'direcciones' : [{}]
    },
    'licencias':{
        'sanitaria' : [{}],
        'avisoFuncionamiento' : [{}],
        'avisoResponsable' : [{}]
    }
    // '' : '',
};

// ****** Declaración de variables globales *******
var objectSocio = socioTemplate;
var arrayCondicionesPago = [];
var elementSelected = null;
var contactoSelected = null;
var arrayTableCuentas = [];
var idTableCuentasEdit = 0;
var idTableCuentas = 0;


var arrayCorreos = [];


var arrayTableContactos = [];
var arrayContactos = [];
var correosContacto = [];
// var idTableCuentas = null;
var idTableContactos = 0;
var idTableContactosEdit = 0;

var arrayTableDirecciones = [];
var idTableDirecciones = 0;
var direccionSelected = null;
var idTableDireccionesEdit = 0;

var arrayTableSanitarias = [];
var idTableSanitaria = 0;
var sanitariaSelected = null;
var idTableSanitariaEdit = 0;

var arrayTableAvisoFuncionamiento = [];
var idTableAvisoFunc = 0;
var avisoFuncSelected = null;
var idTableAvisoFuncEdit = 0;

var arrayTableAvisoResponsable = [];
var idTableAvisoResp = 0;
var avisoRespSelected = null;
var idTableAvisoRespEdit = 0;


// ****** Declaración de variables globales *******

$(document).ready(function(){

    $("#guardarSocio").click(function(e){
        e.preventDefault();
        console.log("<<<-----");
        if($("#activo").prop('checked')){
            objectSocio.activo          = "true";
        }else{
            objectSocio.activo          = "false";
        }

        objectSocio.razon_social    = $("#razon_social").val();
        objectSocio.rfc             = $("#rfc").val();
        objectSocio.nombre_corto    = $("#nombre_corto").val();
        objectSocio.ejecutivo_venta = $("#ejecutivo_venta").val();
        objectSocio.telefono        = $("#telefono").val();
        objectSocio.sitio_web       = $("#sitio_web").val();
        objectSocio.ramo            = $("#ramo").val();
        objectSocio.pais_origen     = $("#pais_origen").val();
        // console.log($("#tipo_socio").val());
        objectSocio.tipo_socio      = $("#tipo_socio").val();
        objectSocio.moneda          = $("#moneda").val();


        var inputs = $("#empresas tbody tr td input");
        var empresas = [];
        console.log(inputs.length);
        $("#empresas tbody tr").find('td').each(function(i,e){
            $(this).find('input').each(function(){
                // console.log(this.checked);
                empresas.push({"checked": this.checked, "id": $(this).attr('id')});
                // console.log($(this).data('name'));
            });
        });

        objectSocio.empresas = empresas;

        // ****************************************************
        //  CONDICIONES DE PAGO
        // ****************************************************
        objectSocio.condiciones_pago.monto_credito  = $("#monto_credito").val();
        objectSocio.condiciones_pago.dias_credito   = $("#dias_credito").val();
        objectSocio.condiciones_pago.forma_pago     = $("#forma_pago").val();
        objectSocio.condiciones_pago.cuentas        = arrayTableCuentas;
        // ****************************************************
        // ****************************************************


        // ****************************************************
        //  INFORMACION DE ENTREGA
        // ****************************************************
        // console.log("TIPOS ENTREGA: " + $("input:radio[name='tipos_entrega']:checked").attr('id'));
        // objectSocio.info_entrega.tipo_entrega               = $("input:radio[name='tipos_entrega']:checked").attr('id');
        if($("input:radio[name='tipos_entrega']:checked").data('idtipoentrega') === 'undefined'){
            objectSocio.info_entrega.tipos_entrega = 'null';
        }else{
            objectSocio.info_entrega.tipos_entrega = $("input:radio[name='tipos_entrega']:checked").data('idtipoentrega');
        }
        objectSocio.info_entrega.sucursal                   = $("#sucursalNombre").val();
        objectSocio.info_entrega.monto_minimo_facturacion   = $("#monto_minimo_facturacion").val();
        if(arrayCorreos.length >= 1){
            objectSocio.info_entrega.correos                    = arrayCorreos;
        }else {
            objectSocio.info_entrega.correos                    = '';
        }
        // ****************************************************
        // ****************************************************


        // ****************************************************
        //  CONTACTOS
        // ****************************************************
        if(arrayTableContactos.length >= 1){
            objectSocio.contactos.contactos = arrayTableContactos;
        }else {
            objectSocio.contactos.contactos = 'null';
        }

        // ****************************************************
        // ****************************************************


        // ****************************************************
        // DIRECCIONES
        // ****************************************************
        objectSocio.direcciones.direcciones = arrayTableDirecciones;

        // ****************************************************
        // ****************************************************


        // ****************************************************
        // LICENCIAS
        // ****************************************************
        // objectSocio.licencias.sanitaria             = arrayTableSanitarias;
        // objectSocio.licencias.avisoFuncionamiento   = arrayTableAvisoFuncionamiento;
        // objectSocio.licencias.avisoResponsable      = arrayTableAvisoResponsable;

        // ****************************************************
        // ****************************************************




        // ****************************************************
        // PETICION AJAX PARA GUARDAR
        // ****************************************************
        var formData = new FormData();
        $.each(arrayTableSanitarias, function(key, value){
            console.log(value['archivo']['fileObject']);
            formData.append('fsanitaria[]', value['archivo']['fileObject'],value['archivo']['nombreArchivo']);
        });
        // formData.append("tipo_entrega", objectSocio.info_entrega.tipos_entrega);
        // formData.append("sucursal", objectSocio.info_entrega.sucursal);
        // formData.append("monto_minimo_facturacion", objectSocio.info_entrega.monto_minimo_facturacion);
        //
        // $.map(objectSocio.info_entrega.correos, function(value, index){
        //     formData.append("correos[]", JSON.stringify(value));
        //     console.log(JSON.stringify(value));
        //     console.log(value);
        // });
        // formData.append("tipo_entrega", objectSocio.info_entrega.tipo_entrega);

        // var datos = $("#form-socios").serializeArray();
        // formData.append('objectSocio', objectSocio);
        // $.each(datos,function(key,input){
        //     formData.append(input.name,input.value);
        // });
        // formData.submit();
        // console.log($('meta[name=csrf-token]').attr('content'));

        $.ajaxSetup({
            headers: { 'X-CSRF-Token' : $('meta[name=csrf-token]').attr('content') }
        });
        // objectSocio = JSON.parse(objectSocio);
        // console.log(objectSocio);

        // console.log($("#form-socios").serialize());

        $.ajax({
            url: "http://localhost/abisa/sociosnegocio/store",
            type: 'POST',
            data: {objectSocio:JSON.stringify(objectSocio)},
            cache: false,
            dataType: 'json',
            // processData: false,
            // contentType: false,
            success: function(data){
                console.log(data);
            }
        });

        // $.ajax({
        //     url: "http://localhost/abisa/sociosnegocio/store",
        //     type: 'POST',
        //     data: formData,
        //     cache: false,
        //     dataType: 'json',
        //     processData: false, // Don't process the files
        //     contentType: false, // Set content type to false as jQuery will tell the server its a query string request
        //     success: function(data){
        //         console.log(data);
        //     }
        // });
        // ****************************************************
        // ****************************************************




        // console.log(JSON.stringify(objectSocio));
        // console.log(objectSocio);
        // console.log("----->>>");
    });

    $("#addLicenciaSanitaria").click(function(event){
        event.preventDefault();
    /**
     * UPLOAD A FILE
     *
        console.log("Add Licencia Sanitaria");

        var nombreArchivo = $("#filesSanitarias").val();
        console.log(nombreArchivo);
        console.log($("#filesSanitarias").val());
        console.log($("#filesSanitarias").prop('files')[0]);

        if ($(this).data('action') == 'add') {
            idTableSanitaria++;
            arrayTableSanitarias.push({'idTable':idTableSanitaria,' nombreArchivo': nombreArchivo,'id_licencia':'','estatus':'new'});
            addLicencia(idTableSanitaria);
        }else{
            updateLicencia(idTableSanitariaEdit,licencias);
        }
        console.log($("#filesSanitarias").val());
        console.log($("#filesSanitarias").prop('files')[0]);

        var files = $('#filesSanitarias').prop("files");
        var names = $.map(files, function(val) { return val; });
        console.log(names);

        var formDataFiles = new FormData();
        var arrayFiles = [];
        for(var i=0; i< names.length; i++){
           var file = names[i];
           arrayFiles[i] = file;
           console.log("FILE: "+names[i]);
           name = file.name.toLowerCase();
           size = file.size;
           type = file.type;
           formDataFiles.append('files[]', file, name);
        }
        console.log("--------------------------------");
        console.log(formDataFiles);
        console.log("--------------------------------");
        console.log("********************************");
        console.log(arrayFiles);
        console.log("********************************");

        let url_licencias = $("#filesSanitarias").data('url');
        console.log(url_licencias);

        jQuery.ajax({
            url: url_licencias,
            data: formDataFiles,
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function(data){
                console.log(data);
            }
        });
    */

        var files = $('#filesSanitarias').prop("files");
        var names = $.map(files, function(val) { return val; });
        console.log(names);

        var arrayFiles = [];
        for(var i=0; i< names.length; i++){
           var formDataFiles = new FormData();
           var file = names[i];
           arrayFiles[i] = file;
           console.log("FILE: "+names[i]);
           nombreArchivo = file.name.toLowerCase();
           size = file.size;
           type = file.type;
           formDataFiles.append('files[]', file, nombreArchivo);

           if ($(this).data('action') == 'add') {
               idTableSanitaria++;
               arrayTableSanitarias.push({'idTable':idTableSanitaria,'id_sanitaria':'','archivo':{ 'nombreArchivo': nombreArchivo,"fileObject": file},'estatus':'new'});
               addLicencia(idTableSanitaria);
               console.log("ADD LICENCIA -->"+nombreArchivo);
           }
        }

        console.log("#############################################################");
        console.log(arrayTableSanitarias);
        console.log("#############################################################");

    });

    $("#addAvisoFuncionamiento").click(function(event){
        event.preventDefault();
        var files = $('#filesAvisoFuncionamiento').prop("files");
        var names = $.map(files, function(val) { return val; });
        console.log(names);

        var formDataFiles = new FormData();
        var arrayFiles = [];
        for(var i=0; i< names.length; i++){
           var file = names[i];
           arrayFiles[i] = file;
           console.log("FILE: "+names[i]);
           nombreArchivo = file.name.toLowerCase();
           size = file.size;
           type = file.type;
           formDataFiles.append('files[]', file, nombreArchivo);

           if ($(this).data('action') == 'add') {
               idTableAvisoFunc++;
               arrayTableAvisoFuncionamiento.push({'idTable':idTableAvisoFunc,'id_aviso_funcionamiento':'','archivo':{ 'nombreArchivo': nombreArchivo,"fileObject": file},'estatus':'new'});
               addAvisoFuncionamiento(idTableAvisoFunc);
               console.log("ADD LICENCIA -->"+nombreArchivo);
           }
        }

        console.log("#############################################################");
        console.log(arrayTableAvisoFuncionamiento);
        console.log("#############################################################");

    });


    $("#addAvisoResponsable").click(function(event){
        event.preventDefault();
        var files = $('#filesAvisoResponsable').prop("files");
        var names = $.map(files, function(val) { return val; });
        console.log(names);

        var formDataFiles = new FormData();
        var arrayFiles = [];
        for(var i=0; i< names.length; i++){
           var file = names[i];
           arrayFiles[i] = file;
           console.log("FILE: "+names[i]);
           nombreArchivo = file.name.toLowerCase();
           size = file.size;
           type = file.type;
           formDataFiles.append('files[]', file, nombreArchivo);

           if ($(this).data('action') == 'add') {
               idTableAvisoResp++;
               arrayTableAvisoResponsable.push({'idTable':idTableAvisoResp,'id_aviso_responsable':'','archivo':{ 'nombreArchivo': nombreArchivo,"fileObject": file},'estatus':'new'});
               addAvisoResponsable(idTableAvisoResp);
               console.log("ADD LICENCIA -->"+nombreArchivo);
           }
        }

        console.log("#############################################################");
        console.log(arrayTableAvisoResponsable);
        console.log("#############################################################");

    });


    $("#pais").on('change',function(event){
        var url_estados = $("#pais option:selected").data('url');
        console.log(url_estados);
        loadEstados(url_estados);
    });

    $("#estado").on('change',function(event){
        let url_municipio = $("#estado").data('url');
        url_municipio = url_municipio.replace('?id',$("#estado option:selected").val());
        loadMunicipios(url_municipio);
    });

    $('#colonia').autocomplete({ //Iniciamos el autocomplete
      data: { //agregamos data de ejemplo, el null es para decir que no llevará una imagen o ícono a un lado del nombre...
        "Colonia 1": null,
        "Colonia 2": null,
        "Colonia 3": null,
      },
      limit: 5, // The max amount of results that can be shown at once. Default: Infinity.
      onAutocomplete: function(val) {
      // Callback function when value is autcompleted.
      console.log("VALOR: "+val);
      },
      minLength: 1, // The minimum length of the input for the autocomplete to start. Default: 1.
    });

    $("#agregarDireccion").click(function(event){
        event.preventDefault();

        var tipo_direccion = $("input:radio[name='tipo_direccion']:checked").siblings('label').text();
        var id_tipo_direccion = $("input:radio[name='tipo_direccion']:checked").attr('id');
        var calle = $("#calle").val();
        var num_exterior = $("#num_exterior").val();
        var num_interior = $("#num_interior").val();
        var cp = $("#cp").val();
        var pais = $("#pais option:selected").text();
        var id_pais = $("#pais").val();
        var estado = $("#estado option:selected").text();
        var id_estado = $("#estado").val();
        var municipio = $("#municipio option:selected").text();
        var id_municipio = $("#municipio").val();
        var colonia = $("#colonia").val();

        console.log('tipo_direccion'+tipo_direccion);
        console.log('calle'+calle);
        console.log('num_exterior'+num_exterior);
        console.log('num_interior'+num_interior);
        console.log('cp'+cp);
        console.log('pais'+pais);
        console.log('id_pais'+id_pais);
        console.log('estado'+estado);
        console.log('id_estado'+id_estado);
        console.log('municipio'+municipio);
        console.log('id_municipio'+id_municipio);
        console.log('colonia'+colonia);

        resetDireccion();
        if ($(this).data('action') == 'add') {
            idTableDirecciones++;
        //     console.log("====ID===="+idTableCuentas);
            arrayTableDirecciones.push({'idTable':idTableDirecciones,'tipo_direccion':tipo_direccion,'id_tipo_direccion':id_tipo_direccion,'calle':calle, 'num_exterior':num_exterior,
            'num_interior':num_interior,'cp':cp,'pais':pais,'id_pais':id_pais,'estado':estado,'id_estado':id_estado,'municipio':municipio,
            'id_municipio':id_municipio,'colonia':colonia,'estatus':'new'});
            addDireccion(idTableDirecciones);
        }else{
            updateDireccion(idTableDireccionesEdit,tipo_direccion,id_tipo_direccion,calle,num_exterior,num_interior,cp,pais,id_pais,estado,id_estado,municipio,id_municipio,colonia);
        }
    });

    $("#agregarCuenta").click(function(event){
        event.preventDefault();

        var banco = $("#banco option:selected:not([disabled])").text();
        var indexBanco = $("#banco option:selected").val();
        var no_cuenta = $("#no_cuenta").val();

        resetCuentaBancaria();
        if ($(this).data('action') == 'add') {
            idTableCuentas++;
            console.log("====ID===="+idTableCuentas);
            arrayTableCuentas.push({'idTable':idTableCuentas,'banco':banco,'indexBanco':indexBanco, 'no_cuenta':no_cuenta,'estatus':'new'});
            addCuenta(idTableCuentas);
        }else{
            updateCuenta(idTableCuentasEdit,banco,indexBanco,no_cuenta);
        }
    });

    $("#xx").click(function(){
        console.log("==========LOG==============");
        console.log("ID-TABLE:"+idTableCuentas);
        console.log(arrayTableCuentas);
        console.log(JSON.stringify(arrayTableCuentas));
        console.log("ID-TABLE:"+idTableCuentas);
        console.log("==========LOG==============");
    });




    $("#yy").click(function(){
        console.log("====ARRAY-CORREOS-LOADED====");
        loadArrayCorreos();
        console.log(arrayCorreos);
        console.log("============================");
    });

    $("input[name=tipos_entrega]").click(function(){
        switch($("input[name=tipos_entrega]:checked").data('idtipoentrega')){
            case 1:
                console.log("--->>");
                $("#sucursalBlock").show("slow");
                break;
            default:
                console.log("default");
                console.log($("input[name=tipos_entrega]:checked").data('idtipoentrega'));
                $("#sucursalBlock").hide('slow');
                $("#sucursalNombre").val('');
                break;
        }
    });

    // $('#correos').material_chip({  // precargar el componente con los datos de edición
    //     data: [
    //       {
    //           tag: 'Maria Luisa',
    //           id: '111',
    //           estatus: 'loaded'
    //       },
    //       {
    //           tag: 'Angela Mendoza',
    //           id: '222',
    //           estatus: 'loaded'
    //       },
    //     ],
    // });


    $("#correos").keyup(function(e){
        var code = e.which;
        if(code==13){
            console.log("===========CORREOS=========");
               var correos = $(this).material_chip('data');
               console.log(JSON.stringify(correos));
            console.log("===========================");
        }
    });

    $('#correos').on('chip.add', function(e, chip){
        chip.id = 'none';
        chip.estatus = 'new';
        arrayCorreos.push({'correo':chip.tag, 'id':chip.id, 'estatus':'new'});
        console.log("========ARRAY=CORREOS======");
        console.log(JSON.stringify(arrayCorreos));
        console.log("===========================");
    });

    $('#correos').on('chip.delete', function(e, chip){
        if (chip.estatus == 'loaded') {
            console.log("LOADED-DELETED!");
            chip.estatus = 'deleted';
            for (var i = 0; i < arrayCorreos.length; i++) {
                if(arrayCorreos[i]['id'] == chip.id){
                    console.log(arrayCorreos[i] = {'correo':chip.tag, 'id':chip.id, 'estatus':'deleted'});
                    console.log("[i]=>"+i);
                }
            }
            console.log(JSON.stringify(arrayCorreos));
            console.log("[id]=>"+chip.id);
            console.log(e+"DELETE"+chip.tag+"<==>"+chip.id);
        }else if(chip.estatus == 'new'){
            console.log("NEW-DELETED!");
            for (var i = 0; i < arrayCorreos.length; i++) {
                if(arrayCorreos[i]['correo'] == chip.tag){
                    console.log(arrayCorreos.splice(i,1));
                }
            }
            console.log(JSON.stringify(arrayCorreos));
        }

    });

    $('#correos').on('chip.select', function(e, chip){
        console.log(e+"SELECTED"+chip.tag+"<==>"+chip.id);
    });



    $("#zz").click(function(){
        console.log("====ARRAY-CORREOS-LOADED====");
        console.log(arrayCorreosContacto);
        console.log("============================");
        console.log("====ARRAY-CORREOS-LOADED====");
        console.log(arrayTableContactos);
        console.log("============================");
    });

    $("#agregarContacto").click(function(event){
        event.preventDefault();

        var tipoContacto = $("#tipo_contacto option:selected:not([disabled])").text();
        var tipoContactoIndex = $("#tipo_contacto option:selected").val();
        var nombreContacto = $("#nombre_contacto").val();
        var puesto = $("#puesto").val();
        var celular = $("#celular").val();
        var telefonoOficina = $("#telefono_oficina").val();
        var extensionOficina = $("#extension_oficina").val();
        // var arrayCorreosContacto = $("#correos_contacto").material_chip('data');



        console.log($(this).data('action'));

        resetContacto();
        if ($(this).data('action') == 'add') {
            idTableContactos++;
            console.log("====ID===="+idTableContactos);
            arrayTableContactos.push({'idTableContactos':idTableContactos,'tipoContacto':tipoContacto, 'tipoContactoIndex':tipoContactoIndex,'nombreContacto':nombreContacto,'puesto':puesto,'celular':celular,'telefonoOficina':telefonoOficina,
                                            'extensionOficina':extensionOficina,'arrayCorreosContacto':correosContacto.slice(),'estatus':'new'});
            addContacto(idTableContactos);
            correosContacto = [];
        }else{
            updateContacto(idTableContactosEdit,tipoContacto,tipoContactoIndex,nombreContacto,puesto,celular,telefonoOficina,extensionOficina,correosContacto.slice());
            correosContacto = [];
        }

    });



    $("#correos_contacto").keyup(function(e){
        var code = e.which;
        if(code==13){
            console.log("===========CORREOS=========");
               var correos = $(this).material_chip('data');
               console.log(JSON.stringify(correos));
            console.log("===========================");
        }
    });

    $('#correos_contacto').on('chip.add', function(e, chip){
        chip.id = 'none';
        chip.estatus = 'new';
        correosContacto.push({'tag':chip.tag, 'id':chip.id, 'estatus':'new'});
        console.log("========ARRAY=CORREOS======");
        console.log(JSON.stringify(correosContacto));
        console.log("===========================");
    });

    $('#correos_contacto').on('chip.delete', function(e, chip){
        if (chip.estatus == 'loaded') {
            console.log("LOADED-DELETED!");
            chip.estatus = 'deleted';
            for (var i = 0; i < correosContacto.length; i++) {
                if(correosContacto[i]['id'] == chip.id){
                    console.log(correosContacto[i] = {'tag':chip.tag, 'id':chip.id, 'estatus':'deleted'});
                    console.log("[i]=>"+i);
                }
            }
            console.log(JSON.stringify(correosContacto));
            console.log("[id]=>"+chip.id);
            console.log(e+"DELETE"+chip.tag+"<==>"+chip.id);
        }else if(chip.estatus == 'new'){
            console.log("NEW-DELETED!");
            for (var i = 0; i < correosContacto.length; i++) {
                if(correosContacto[i]['tag'] == chip.tag){
                    console.log(correosContacto.splice(i,1));
                }
            }
            console.log(JSON.stringify(correosContacto));
        }

    });

});

function addDireccion(idTable){
    for (var i = 0; i < arrayTableDirecciones.length; i++) {
        if(arrayTableDirecciones[i]['idTable'] ==  idTable){
            console.log(arrayTableDirecciones[i]['idTable']);
            console.log(arrayTableDirecciones[i]['tipo_direccion']);
            console.log(arrayTableDirecciones[i]['calle']);
            console.log(arrayTableDirecciones[i]['num_exterior']);
            console.log(arrayTableDirecciones[i]['num_interior']);
            console.log(arrayTableDirecciones[i]['cp']);
            console.log(arrayTableDirecciones[i]['pais']);
            console.log(arrayTableDirecciones[i]['id_pais']);
            console.log("ID===>"+idTable);

            let ext = arrayTableDirecciones[i]['num_exterior']=="" ? '' : " #"+arrayTableDirecciones[i]['num_exterior'];
            let int = arrayTableDirecciones[i]['num_interior']=="" ? '' : " INT. "+arrayTableDirecciones[i]['num_interior'];
            $("#tableDirecciones > tbody ").eq(0)
                .append("<tr>"+
                "<td>"+arrayTableDirecciones[i]['tipo_direccion']+"</td>"+
                "<td>"+arrayTableDirecciones[i]['calle']+ext+int+"</td>"+
                "<td><a class=\"editar btn btn_tables waves-effect btn-flat\" onclick='editRowDireccion(this,"+idTable+");'><i class=\"material-icons\">edit</i></a>"+
                "<a class=\"eliminar btn btn_tables waves-effect btn-flat\" onclick='deleteRowDireccion(this,"+idTable+");'><i class=\"material-icons\">delete</i></a></td>"+
                "</tr>");
        }
        console.log("============ADD===============");
        console.log("ID-TABLE: "+idTable);
        console.log(arrayTableDirecciones);
        console.log("==============================");
    }
}

function editRowDireccion(obj,idTable){
    for (var i = 0; i < arrayTableDirecciones.length; i++) {
        if(arrayTableDirecciones[i]['idTable'] ==  idTable){
            // console.log("ID-TABLE: "+idTable);
            idTableDireccionesEdit = idTable;
            direccionSelected = $(obj).parent().parent().find('td');
            $("#agregarDireccion").data('action','update');


            $("#"+arrayTableDirecciones[i]['id_tipo_direccion']).attr('checked','checked');
            $("#"+arrayTableDirecciones[i]['id_tipo_direccion']).siblings('label').click();
            $("#calle").val(arrayTableDirecciones[i]['calle']);
            $("#num_exterior").val(arrayTableDirecciones[i]['num_exterior']);
            $("#num_interior").val(arrayTableDirecciones[i]['num_interior']);
            $("#cp").val(arrayTableDirecciones[i]['cp']);
            $("#pais").val(arrayTableDirecciones[i]['id_pais']);
            $("#pais").material_select();
            $("#estado").removeAttr('selected');
            $("#estado").val(arrayTableDirecciones[i]['id_estado']);
            $("#estado").material_select();

            let url_municipio = $("#estado").data('url');
            url_municipio = url_municipio.replace('?id',$("#estado option:selected").val());
            console.log("editRow: "+url_municipio);
            loadMunicipios(url_municipio, arrayTableDirecciones[i]['id_municipio']);

            // $("#municipio").removeAttr('selected');
            // $("#municipio").material_select();
            // $("#municipio").material_select();
            // $("#municipio").val(arrayTableDirecciones[i]['id_municipio']);
            // console.log($("#municipio option:selected").val());
            console.log(arrayTableDirecciones[i]['id_municipio']);
            $("#colonia").val(arrayTableDirecciones[i]['colonia']);
        }
    }
}

function updateDireccion(idTable,tipo_direccion,id_tipo_direccion,calle,num_exterior,num_interior,cp,pais,id_pais,estado,id_estado,municipio,id_municipio,colonia){
    for (var i = 0; i < arrayTableDirecciones.length; i++) {
        if(arrayTableDirecciones[i]['idTable'] ==  idTable){
            $(direccionSelected[2]).children().remove();
            $(direccionSelected[2]).html("<a class=\"editar btn btn_tables waves-effect btn-flat\" onclick='editRowDireccion(this,"+idTable+");'><i class=\"material-icons\">edit</i></a>"+
            "<a class=\"eliminar btn btn_tables waves-effect btn-flat\" onclick='deleteRowDireccion(this,"+idTable+");'><i class=\"material-icons\">delete</i></a>");

            let ext = num_exterior=="" ? '' : " #"+num_exterior;
            let int = num_interior=="" ? '' : " INT. "+num_interior;

            $(direccionSelected[0]).text(tipo_direccion);
            $(direccionSelected[1]).text(calle+ext+int);

            console.log("=========UPDATE===============");
            console.log("ID-TABLE: "+idTable);
            console.log(arrayTableDirecciones);

            console.log(arrayTableDirecciones[i] = {'idTable':idTable,'tipo_direccion':tipo_direccion,'id_tipo_direccion':id_tipo_direccion,'calle':calle, 'num_exterior':num_exterior,
                                                    'num_interior':num_interior,'cp':cp,'pais':pais,'id_pais':id_pais,'estado':estado,'id_estado':id_estado,'municipio':municipio,
                                                    'id_municipio':id_municipio,'colonia':colonia,'estatus':'new'});
            console.log("[i]=>"+i);

            console.log(arrayTableDirecciones);
            console.log("==============================");
            $("#agregarDireccion").data('action',"add");
        }
    }
}

function deleteRowDireccion(obj,idTable){
    $(obj).parent().parent().hide('slow', function(){ this.remove(); });
    resetDireccion();
    console.log("==========DELETE==============");
    console.log("ID-DELETE:"+idTable);
    for (var i = 0; i < arrayTableDirecciones.length; i++) {
        // if(arrayTableDirecciones[i]['idTable'] == idTable){
            // console.log("LOADED-DELETED!");
            // console.log(arrayTableDirecciones[i]);
        //     console.log(arrayTableDirecciones[i] = {'idTable':idTable,'banco':arrayTableDirecciones[i]['banco'],'no_cuenta':arrayTableDirecciones[i]['no_cuenta'],'estatus':'deleted'});
        //     console.log("[i]=>"+i);
        // }
        if(arrayTableDirecciones[i]['idTable'] == idTable && arrayTableDirecciones[i]['estatus'] == 'new'){
            console.log("NEW-DELETED!");
            console.log(arrayTableDirecciones.splice(i,1));
            console.log(JSON.stringify(arrayTableDirecciones));
        }
    }
    console.log("==============================");
}

function updateCuenta(idTable,banco,indexBanco,no_cuenta){
    for (var i = 0; i < arrayTableCuentas.length; i++) {
        if(arrayTableCuentas[i]['idTable'] ==  idTable){
            $(elementSelected[2]).children().remove();
            $(elementSelected[2]).html("<a class=\"editar btn btn_tables waves-effect btn-flat\" onclick='editRowCuenta(this,"+idTable+");'><i class=\"material-icons\">edit</i></a>"+
            "<a class=\"eliminar btn btn_tables waves-effect btn-flat\" onclick='deleteRowCuenta(this,"+idTable+");'><i class=\"material-icons\">delete</i></a>");
            $(elementSelected[0]).text(banco);
            $(elementSelected[1]).text(no_cuenta);

            console.log("=========UPDATE===============");
            console.log("ID-TABLE: "+idTable);
            console.log(arrayTableCuentas);

            console.log(arrayTableCuentas[i] = {'idTable':idTable,'banco':banco,'no_cuenta':no_cuenta,'indexBanco':indexBanco,'estatus':'new'});
            console.log("[i]=>"+i);

            console.log(arrayTableCuentas);
            console.log("==============================");
            $("#agregarCuenta").data('action',"add");
        }
    }
}


function editRowCuenta(obj,idTable){
    for (var i = 0; i < arrayTableCuentas.length; i++) {
        if(arrayTableCuentas[i]['idTable'] ==  idTable){
            console.log("ID-TABLE: "+idTable);
            idTableCuentasEdit = idTable;
            elementSelected = $(obj).parent().parent().find('td');
            // var update="update";
            $("#agregarCuenta").data('action','update');
            $("#no_cuenta").val(arrayTableCuentas[i]['no_cuenta']);
            $("#banco").val(arrayTableCuentas[i]['indexBanco']);
            $("#banco").material_select();
        }
    }
}

function addCuenta(idTable){
        for (var i = 0; i < arrayTableCuentas.length; i++) {
            if(arrayTableCuentas[i]['idTable'] ==  idTable){
                console.log(arrayTableCuentas[i]['idTable']);
                console.log(arrayTableCuentas[i]['banco']);
                console.log(arrayTableCuentas[i]['indexBanco']);
                console.log("ID===>"+idTable);
                $("#tableCuentas > tbody ").eq(0)
                    .append("<tr>"+
                    "<td>"+arrayTableCuentas[i]['banco']+"</td>"+
                    "<td>"+arrayTableCuentas[i]['no_cuenta']+"</td>"+
                    "<td><a class=\"editar btn btn_tables waves-effect btn-flat\" onclick='editRowCuenta(this,"+idTable+");'><i class=\"material-icons\">edit</i></a>"+
                    "<a class=\"eliminar btn btn_tables waves-effect btn-flat\" onclick='deleteRowCuenta(this,"+idTable+");'><i class=\"material-icons\">delete</i></a></td>"+
                    "</tr>");
            }
            console.log("============ADD===============");
            console.log("ID-TABLE: "+idTable);
            console.log(arrayTableCuentas);
            console.log("==============================");
        }
}

function updateCuenta(idTable,banco,indexBanco,no_cuenta){
    for (var i = 0; i < arrayTableCuentas.length; i++) {
        if(arrayTableCuentas[i]['idTable'] ==  idTable){
            $(elementSelected[2]).children().remove();
            $(elementSelected[2]).html("<a class=\"editar btn btn_tables waves-effect btn-flat\" onclick='editRowCuenta(this,"+idTable+");'><i class=\"material-icons\">edit</i></a>"+
            "<a class=\"eliminar btn btn_tables waves-effect btn-flat\" onclick='deleteRowCuenta(this,"+idTable+");'><i class=\"material-icons\">delete</i></a>");
            $(elementSelected[0]).text(banco);
            $(elementSelected[1]).text(no_cuenta);

            console.log("=========UPDATE===============");
            console.log("ID-TABLE: "+idTable);
            console.log(arrayTableCuentas);

            console.log(arrayTableCuentas[i] = {'idTable':idTable,'banco':banco,'no_cuenta':no_cuenta,'indexBanco':indexBanco,'estatus':'new'});
            console.log("[i]=>"+i);

            console.log(arrayTableCuentas);
            console.log("==============================");
            $("#agregarCuenta").data('action',"add");
        }
    }
}

function deleteRowCuenta(obj,idTable){
    $(obj).parent().parent().hide('slow', function(){ this.remove(); });
    resetCuentaBancaria();
    console.log("==========DELETE==============");
    console.log("ID-DELETE:"+idTable);
    for (var i = 0; i < arrayTableCuentas.length; i++) {
        // if(arrayTableCuentas[i]['idTable'] == idTable){
            // console.log("LOADED-DELETED!");
            // console.log(arrayTableCuentas[i]);
        //     console.log(arrayTableCuentas[i] = {'idTable':idTable,'banco':arrayTableCuentas[i]['banco'],'no_cuenta':arrayTableCuentas[i]['no_cuenta'],'estatus':'deleted'});
        //     console.log("[i]=>"+i);
        // }
        if(arrayTableCuentas[i]['idTable'] == idTable && arrayTableCuentas[i]['estatus'] == 'new'){
            console.log("NEW-DELETED!");
            console.log(arrayTableCuentas.splice(i,1));
            console.log(JSON.stringify(arrayTableCuentas));
        }
    }
    console.log("==============================");
}

function resetCuentaBancaria(){
    $("#no_cuenta").val("");
    $("#banco").prop("selectedIndex",false);
    $("#banco").material_select();
}


function loadArrayCorreos(){
    var correos = $('#correos').material_chip('data');
    arrayCorreos = [];
    $.each(correos,function(elem,value){
        arrayCorreos.push({'correo':value.tag, 'id':value.id, 'estatus':value.estatus});
        console.log(elem+"----"+value.tag);
    });
}


function addContacto(idTableContactos){
        console.log("ID===>"+idTableContactos);
        for (var i = 0; i < arrayTableContactos.length; i++) {
            if(arrayTableContactos[i]['idTableContactos'] ==  idTableContactos){
                $("#tableContactos > tbody ").eq(0)
                    .append("<tr>"+
                    "<td>"+arrayTableContactos[i]['tipoContacto']+"</td>"+
                    "<td>"+arrayTableContactos[i]['nombreContacto']+"</td>"+
                    "<td>"+arrayTableContactos[i]['telefonoOficina']+" +"+arrayTableContactos[i]['extensionOficina']+"</td>"+
                    "<td><a class=\"editar btn btn_tables waves-effect btn-flat\" onclick='editRowContacto(this,"+idTableContactos+");'><i class=\"material-icons\">edit</i></a>"+
                    "<a class=\"eliminar btn btn_tables waves-effect btn-flat\" onclick='deleteRowContacto(this,"+idTableContactos+");'><i class=\"material-icons\">delete</i></a></td>"+
                    "</tr>");
                console.log("=========ADD==CONTACTO========");
                console.log("ID-TABLE: "+idTableContactos);
                console.log(arrayTableContactos);
                console.log("==============================");

            }
        }
}



function updateContacto(idTableContacto,tipoContacto,tipoContactoIndex,nombreContacto,puesto,celular,telefonoOficina,extensionOficina,correos){
    console.log("::::::::::::::::::::::::::::::::::::::::::");
    console.log(correos);
    console.log("::::::::::::::::::::::::::::::::::::::::::");
    for (var i = 0; i < arrayTableContactos.length; i++) {
        if(arrayTableContactos[i]['idTableContactos'] ==  idTableContacto){
            console.log(arrayTableContactos[i]);
            $(contactoSelected[3]).children().remove();
            $(contactoSelected[3]).html("<a class=\"editar btn btn_tables waves-effect btn-flat\" onclick='editRowContacto(this,"+idTableContacto+
                                        ");'><i class=\"material-icons\">edit</i></a>"+
            "<a class=\"eliminar btn btn_tables waves-effect btn-flat\" onclick='deleteRowContacto(this,"+idTableContacto+");'><i class=\"material-icons\">delete</i></a>");
            $(contactoSelected[0]).text(tipoContacto);
            $(contactoSelected[1]).text(nombreContacto);
            $(contactoSelected[2]).text(telefonoOficina+" +"+extensionOficina); // no cuenta
            console.log("=========UPDATE===============");
            console.log("ID-TABLE: "+idTableContacto);
            console.log(arrayTableContactos);

                    console.log("ESTATUS:"+arrayTableContactos[i]['estatus']);
                    if (arrayTableContactos[i]['estatus'] == 'new'){
                        console.log($("#correos_contacto").material_chip('data'));
                        console.log(arrayTableContactos[i] = {'idTableContactos':idTableContacto,'tipoContacto':tipoContacto,'tipoContactoIndex':tipoContactoIndex,'nombreContacto':nombreContacto,'puesto':puesto,
                                            'celular':celular,'telefonoOficina':telefonoOficina,'extensionOficina':extensionOficina,'arrayCorreosContacto':correos,
                                            'estatus':'new'});
                    }else {
                        console.log(arrayTableContactos[i] = {'idTableContactos':idTableContacto,'tipoContacto':tipoContacto,'tipoContactoIndex':tipoContactoIndex,'nombreContacto':nombreContacto,'puesto':puesto,
                                            'celular':celular,'telefonoOficina':telefonoOficina,'extensionOficina':extensionOficina,'arrayCorreosContacto':correos,
                                            'estatus':'edit'});
                    }
                    console.log("[i]=>"+i);

            console.log("ID-CONTACTO-EDIT: "+idTableContacto);
            console.log(arrayTableContactos);
            console.log("==============================");
            $("#agregarContacto").data('action',"add");
        }
    }
}



function editRowContacto(obj,idTableContactos){
    for (var i = 0; i < arrayTableContactos.length; i++) {
        if(arrayTableContactos[i]['idTableContactos'] ==  idTableContactos){

            console.log("ID: "+idTableContactos);
            console.log("ID-TABLE: "+idTableContactos);
            idTableContactosEdit = idTableContactos;
            correosContacto = arrayTableContactos[i]['arrayCorreosContacto'];
            contactoSelected = $(obj).parent().parent().find('td');
            var update="update";
            $("#agregarContacto").data('action',update);
            $("#tipo_contacto").val(arrayTableContactos[i]['tipoContactoIndex']);
            $("#tipo_contacto").material_select();
            $("#nombre_contacto").val(arrayTableContactos[i]['nombreContacto']);
            $("#puesto").val(arrayTableContactos[i]['puesto']);
            $("#celular").val(arrayTableContactos[i]['celular']);
            $("#telefono_oficina").val(arrayTableContactos[i]['telefonoOficina']);
            $("#extension_oficina").val(arrayTableContactos[i]['extensionOficina']);
            // $("#correos_contacto").val();
            // $("#correos_contacto").material_select();
            // console.log(arrayTableContactos[i]['correosContacto']);
            // var correos = '';
            // for (var j = 0; j < arrayTableContactos[i]['arrayCorreosContacto'].length; j++) {
            //     console.log(arrayTableContactos[i]['arrayCorreosContacto']);
            // }
            var scorreos = {};
            scorreos.data = arrayTableContactos[i]['arrayCorreosContacto'];
            console.log(JSON.stringify(scorreos));
            var x = JSON.stringify(scorreos);
            console.log(JSON.parse(x));
            $("#correos_contacto").material_chip(JSON.parse(x));


        }
    }
}

function deleteRowContacto(obj,idTable){
    $(obj).parent().parent().hide('slow', function(){ this.remove(); });
    resetContacto();
    console.log("==========DELETE==============");
    console.log("ID-DELETE:"+idTable);
    for (var i = 0; i < arrayTableContactos.length; i++) {
        // if(arrayTableContactos[i]['idTable'] == idTable){
            // console.log("LOADED-DELETED!");
            // console.log(arrayTableContactos[i]);
        //     console.log(arrayTableContactos[i] = {'idTable':idTable,'banco':arrayTableContactos[i]['banco'],'no_cuenta':arrayTableContactos[i]['no_cuenta'],'estatus':'deleted'});
        //     console.log("[i]=>"+i);
        // }
        if(arrayTableContactos[i]['idTableContactos'] == idTable && arrayTableContactos[i]['estatus'] == 'new'){
            console.log("NEW-DELETED!");
            console.log(arrayTableContactos.splice(i,1));
            console.log(JSON.stringify(arrayTableContactos));
        }
    }
    console.log("==============================");
}


function resetContacto(){
    $("#tipo_contacto").prop("selectedIndex",false);
    $("#tipo_contacto").material_select();
    $("#nombre_contacto").val("");
    $("#puesto").val("");
    $("#celular").val("");
    $("#telefono_oficina").val("");
    $("#extension_oficina").val("");
    // $("#correos_contacto").val("");
    $("#correos_contacto").material_chip("");
}


function resetDireccion(){
    $("input:radio[name='tipo_direccion']:checked").removeAttr('checked');
    $("#calle").val("");
    $("#num_exterior").val("");
    $("#num_interior").val("");
    $("#cp").val("");
    $("#pais").prop("selectedIndex",false);
    $("#pais").material_select();
    $("#estado").prop("selectedIndex",false);
    $("#estado").material_select();
    $("#municipio").prop("selectedIndex",false);
    $("#municipio").material_select();
    $("#colonia").val("");
}



function loadEstados(url_estados){
    $.ajax({
        type:'POST',
        url: url_estados,
        dataType: 'json',
        success: function (data) {
            $('#estado').html('');
            console.log("loadEstados"+url_estados);
            let option_estado = $('<option/>');
            option_estado.val(null);
            option_estado.attr('disabled','disabled');
            option_estado.attr('selected','selected');
            option_estado.text('Selecciona...');
            let option_municipio = $('<option/>');
            option_municipio.val(null);
            option_municipio.attr('disabled','disabled');
            option_municipio.attr('selected','selected');
            option_municipio.text('Selecciona...');
            if(data.cantidad === 0){
                $('#municipio').html(option_municipio);
                $('#municipio').prop('disabled',true);
                $('#estado').html(option_estado);
                $('#estado').prop('disabled',true);
            }else{
                $('#estado').prop('disabled',false);
                let option = $('<option/>');
                option.attr('disabled','disabled');
                option.attr('selected','selected');
                option.text('Selecciona...');
                $('#estado').append(option);
                $.each(data, function (key, estado) {
                    let option = $('<option/>');
                    option.val(estado.id_estado);
                    option.text(estado.estado);
                    $('#estado').append(option);
                });
            }
            $('#estado').material_select();
            $('#municipio').material_select();
        },
        error: function () {
            // alert('error');
            Materialize.toast('<span><i class="material-icons">priority_high</i> No se pudieron cargar los estados</span>', 3000,'m_error');
        }
    });
}




function loadMunicipios(url_municipio, id_municipio ){
    $.ajax({
        type:'POST',
        url: url_municipio,
        dataType: 'json',
        success: function (data) {
            $('#municipio').html('');
            let option = $('<option/>');
            console.log("loadMunicipios: " + url_municipio);
            option.val(null);
            option.attr('disabled','disabled');
            option.attr('selected','selected');
            option.text('Selecciona...');
            if(data.cantidad === 0){
                $('#municipio').html(option);
                $('#municipio').prop('disabled',true);
            }else{
                $('#municipio').prop('disabled',false);
                let option = $('<option/>');
                option.attr('disabled','disabled');
                option.attr('selected','selected');
                option.text('Selecciona...');
                $('#municipio').append(option);
                $.each(data, function (key, municipio) {
                    let option = $('<option/>');
                    option.val(municipio.id_municipio);
                    option.text(municipio.municipio);
                    $('#municipio').append(option);
                });
            }

            console.log(id_municipio)

            if (id_municipio !== undefined) {
                $("#municipio").val(id_municipio);
            }

            $('#municipio').material_select();
        },
        error: function () {
            // alert('error');
              Materialize.toast('<span><i class="material-icons">priority_high</i> No se pudieron cargar los municipios</span>', 3000,'m_error');
        }
    });
}





function addLicencia(idTable){
    for (var i = 0; i < arrayTableSanitarias.length; i++) {
        if(arrayTableSanitarias[i]['idTable'] ==  idTable){
            console.log(arrayTableSanitarias[i]['idTable']);
            console.log(arrayTableSanitarias[i]['archivo']['nombreArchivo']);
            console.log("ID===>"+idTable);

            $("#tableSanitaria > tbody ").eq(0)
                .append("<tr>"+
                "<td><a ><i class=\"material-icons\">attach_file</i>"+
                arrayTableSanitarias[i]['archivo']['nombreArchivo']+"</a></td>"+
                "<td><a class='eliminar btn btn_tables waves-effect btn-flat' onclick='deleteRowSanitaria(this,"+
                idTable+");'><i class='material-icons'>delete</i></a></td></tr>");
        }
        console.log("============ADD===============");
        console.log("ID-TABLE: "+idTable);
        console.log(arrayTableSanitarias);
        console.log("==============================");
    }
    $("#filesSanitarias").closest('form').trigger('reset');
}


function deleteRowSanitaria(obj,idTable){
    $(obj).parent().parent().hide('slow', function(){ this.remove(); });
    // TODO: reset input files for this field
    $("#filesSanitarias").closest('form').trigger('reset');
    console.log("==========DELETE==============");
    console.log("ID-DELETE:"+idTable);
    for (var i = 0; i < arrayTableSanitarias.length; i++) {
        if(arrayTableSanitarias[i]['idTable'] == idTable && arrayTableSanitarias[i]['estatus'] == 'new'){
            console.log("NEW-DELETED!");
            console.log(arrayTableSanitarias.splice(i,1));
            console.log(JSON.stringify(arrayTableSanitarias));
        }
    }
    console.log("==============================");
}


function addAvisoFuncionamiento(idTable){
    for (var i = 0; i < arrayTableAvisoFuncionamiento.length; i++) {
        if(arrayTableAvisoFuncionamiento[i]['idTable'] ==  idTable){
            console.log(arrayTableAvisoFuncionamiento[i]['idTable']);
            console.log(arrayTableAvisoFuncionamiento[i]['archivo']['nombreArchivo']);
            console.log("ID===>"+idTable);

            $("#tableAvisosFuncionamiento > tbody ").eq(0)
                .append("<tr>"+
                "<td><a ><i class=\"material-icons\">attach_file</i>"+
                arrayTableAvisoFuncionamiento[i]['archivo']['nombreArchivo']+"</a></td>"+
                "<td><a class='eliminar btn btn_tables waves-effect btn-flat' onclick='deleteRowAvisoFuncionamiento(this,"+
                idTable+");'><i class='material-icons'>delete</i></a></td></tr>");
        }
        console.log("============ADD===============");
        console.log("ID-TABLE: "+idTable);
        console.log(arrayTableAvisoFuncionamiento);
        console.log("==============================");
    }
    $("#filesAvisoFuncionamiento").closest('form').trigger('reset');
}


function deleteRowAvisoFuncionamiento(obj,idTable){
    $(obj).parent().parent().hide('slow', function(){ this.remove(); });
    // TODO: reset input fiules for this field
    $("#filesAvisoFuncionamiento").closest('form').trigger('reset');
    console.log("==========DELETE==============");
    console.log("ID-DELETE:"+idTable);
    for (var i = 0; i < arrayTableAvisoFuncionamiento.length; i++) {
        if(arrayTableAvisoFuncionamiento[i]['idTable'] == idTable && arrayTableAvisoFuncionamiento[i]['estatus'] == 'new'){
            console.log("NEW-DELETED!");
            console.log(arrayTableAvisoFuncionamiento.splice(i,1));
            console.log(JSON.stringify(arrayTableAvisoFuncionamiento));
        }
    }
    console.log("==============================");
}

function addAvisoResponsable(idTable){
    for (var i = 0; i < arrayTableAvisoResponsable.length; i++) {
        if(arrayTableAvisoResponsable[i]['idTable'] ==  idTable){
            console.log(arrayTableAvisoResponsable[i]['idTable']);
            console.log(arrayTableAvisoResponsable[i]['archivo']['nombreArchivo']);
            console.log("ID===>"+idTable);

            $("#tableAvisosResponsable > tbody ").eq(0)
                .append("<tr>"+
                "<td><a ><i class=\"material-icons\">attach_file</i>"+
                arrayTableAvisoResponsable[i]['archivo']['nombreArchivo']+"</a></td>"+
                "<td><a class='eliminar btn btn_tables waves-effect btn-flat' onclick='deleteRowAvisoResponsable(this,"+
                idTable+");'><i class='material-icons'>delete</i></a></td></tr>");
        }
        console.log("============ADD===============");
        console.log("ID-TABLE: "+idTable);
        console.log(arrayTableAvisoResponsable);
        console.log("==============================");
    }
    $("#filesAvisoResponsable").closest('form').trigger('reset');
}


function deleteRowAvisoResponsable(obj,idTable){
    $(obj).parent().parent().hide('slow', function(){ this.remove(); });
    // TODO: reset input files for this field
    // $("#filesAvisoResponsable").parent().siblings('.file-path-wrapper').wrap('<form>').closest('form').get(0).reset();
    // $("#filesAvisoResponsable").parent().siblings('form').children().unwrap();
    // var avisoRespToClone = $("#avisoRespToClone");
    $("#filesAvisoResponsable").closest('form').trigger('reset');

    console.log("==========DELETE==============");
    console.log("ID-DELETE:"+idTable);
    for (var i = 0; i < arrayTableAvisoResponsable.length; i++) {
        if(arrayTableAvisoResponsable[i]['idTable'] == idTable && arrayTableAvisoResponsable[i]['estatus'] == 'new'){
            console.log("NEW-DELETED!");
            console.log(arrayTableAvisoResponsable.splice(i,1));
            console.log(JSON.stringify(arrayTableAvisoResponsable));
        }
    }
    console.log("==============================");
}
