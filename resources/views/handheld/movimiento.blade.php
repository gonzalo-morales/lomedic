@extends('handheld.layout')

@section('title', 'Handheld - Stock')

@section('content')
{!! Form::open(['url' => companyRoute('handheld.stock-movimiento-detalle-store',['movimiento' => $movimiento->getKey()]),'method'=>'post','enctype' => 'multipart/form-data','id'=>'form']) !!}
{{--  {{dump($movimiento)}}  --}}
    <input type="hidden" name="fk_id_stock" value="{{ $movimiento->id_stock }}">
    <input type="hidden" name="fk_id_sku" value="{{ $movimiento->fk_id_sku }}">
    <input type="hidden" name="fk_id_sucursal" value="{{ $fk_id_sucursal }}">
    <input type="hidden" name="fk_id_almacen" value="{{ $fk_id_almacen }}">
    <input type="hidden" name="fk_id_usuario" value="{{ $fk_id_usuario }}">
    <input type="hidden" name="fecha_operacion" value="{{ $fecha_operacion }}">
    <input type="hidden" name="costo" value="{{ $movimiento->costo }}">
    <input type="hidden" name="fk_id_documento_entrada" value="{{ $movimiento->fk_id_documento }}">
    <input type="hidden" name="total_productos" value="1">
    <div>
        <table class="table-columns">
            <tr>
                <td class="column right">
                    <label>SKU: <b>{{ $movimiento->sku->sku }}</b></label>
                </td>
                <td class="column left">
                    <label>Cad.: <b><span id="fecha_caducidad">{{ $movimiento->fecha_caducidad }}</span></b></label>
                     <input type="hidden" name="fecha_caducidad" value="{{ $movimiento->fecha_caducidad }}">
                </td>
            </tr>
            <tr>
                <td class="column right">
                    <label>*UPC</label>
                </td>
                <td class="column left" style="position: relative;">
                    <div id="loadingupc" class="h-100 text-center text-white align-middle loadingData display-none" style="height: 30px">
                        Validando...
                    </div>
                    <input id="scan" class="form-control" type="text" value="">
                    <input id="upc" type="hidden" name="fk_id_upc" value="0">
                    <span id="upc_verificado" class="display-none" style="color:green;">Verificado</span>
                </td>
            </tr>
            <tr>
                <td class="column right">
                    <label for="lote_nuevo">*Nuevo Lote:</label>
                    <br><span id="lote_actual" class="text-description">Lote actual: <b>{{ $movimiento->lote }}</b></span>
                </td>
                <td class="column left">
                    <input id="lote_nuevo" class="form-control readonly" name="lote" type="text" value="{{ $movimiento->lote }}" disabled="true">
                </td>
            </tr>
            <tr>
                <td class="column right">
                    <label for="no_lote">*Nueva ubicación</label>
                    <br><span class="text-description">Ubi. Actual: {{ $movimiento->ubicacion->ubicacion }}<input id="ubicacion_actual" name="fk_id_ubicacion_anterior"  type="hidden" class="readonly" style="width: 35px;" value="{{ $movimiento->ubicacion->id_ubicacion }}"></span>
                </td>
                <td class="column left">
                      {{ Form::cSelect('','fk_id_ubicacion', $ubicacion ?? []) }}
                </td>
            </tr>
            <tr>
                <td class="column right">
                    <label for="stock_nuevo">*Stock a mover:</label>
                    <br><span class="text-description">Stock actual: <input id="stock_actual" disabled="true" class="readonly" style="width: 35px;" value="{{ $movimiento->stock }}"></span>
                </td>
                <td class="column left">
                    <input id="stock_nuevo" class="form-control readonly" name="stock" type="text" value="{{ $movimiento->stock }}" disabled="true">
                </td>
            </tr>
{{--             {{dump($upc)}} --}}
        </table>
        <div class="wrapper margin-top">
            {{ link_to(companyRoute('handheld.movimientos', [
                'id' => $previous,
            ]), 'Regresar', ['class'=>'square actionBtn green']) }}
            <button id="finalizar" type="submit" class="square blue actionBtn" disabled="true">Finalizar</button>
            {{ link_to(companyAction('HomeController@index'), 'Cancelar', ['class'=>'square red actionBtn']) }}
        </div>
    </div>
{!! Form::close() !!}

<script type="text/javascript">

    // function GetDescriptionFor(e) {
    //     var result, code, key;
    //     if (e.charCode && e.keyCode == 0) {
    //         result = "charCode: " + e.charCode;
    //         code = e.charCode;
    //     } else {
    //         result = "keyCode: " + e.keyCode;
    //         code = e.keyCode;
    //     }

    //     if (code == 8) key = "BKSP";
    //     else if (code == 9) key = "TAB";
    //     else if (code == 46) key = "DEL";
    //     else if (code >= 41 && code <= 126) key = String.fromCharCode(code);

    //     // if (e.shiftKey) result += " shift";
    //     // if (e.ctrlKey) result += " ctrl";
    //     // if (e.altKey) result += " alt";

    //     return {keycode: code, key: key};
    // }

    $(function(){
        // Init
        $('#scan').focus();
        $('#fk_id_ubicacion').attr('disabled',true);
        //Con esto evitamos que haga submit
        $(document).on("keypress", "#form", function(event) {
            return event.keyCode != 13;
        });

        //Evento para el campo del scan
        $("#scan").on("keydown", function(event){

            if (event.keyCode == 13) {
                $('#loadingupc').show();
                $('#scan').attr('disabled', true);
                //Hacemos una petición para validar el UPC
                $.get('{{ companyRoute('api.index', ['entity' => 'inventarios.upcs'], false) }}', {
                    'param_js': '{{$codigo_barras_js ?? ''}}',
                    '$upc': event.target.value
                    // conditions: [{'where': ['upc', e.target.value]}],
                    // only: ['descripcion']
                }, function(response){
                    if (response.length > 0) {
                        $('#scan').attr('style','border:1px solid green');
                        $('#loadingupc').hide();
                        $('#scan').attr('disabled', false);
                        $('#scan').val(response[0].upc)
                        $('#upc').val(response[0].id_upc)
                        $('#upc_verificado').show()
                        $('#lote_nuevo').attr('disabled', false).removeClass('readonly');
                        $('#ubicacion_nueva').attr('disabled', false).removeClass('readonly');
                        $('#stock_nuevo').attr('disabled', false).removeClass('readonly');
                        $('#fk_id_ubicacion').attr('disabled',false)
                        $('#finalizar').attr('disabled',false);
                    } else {
                        alert('El UPC que tratas de escanear no es válido, verifica que sea el mismo y tenga existencias');
                        $('#loadingupc').hide();
                        $('#scan').val('').attr('disabled', false).focus();
                        $('#scan').attr('style','border:inherit;');
                        $('#upc_verificado').hide();
                    }
                })
            }

        });

        $('#form').on('submit', function(e){
                // Enviamos formulario
            if($('#fk_id_ubicacion').val() == ''){
                alert('Para finalizar es necesario indicar la nueva ubicación');
                return false;
            }
            if($('#fk_id_ubicacion').val() == $('#ubicacion_actual').val()){
                alert('Recuerda que la nueva ubicación tiene que ser diferente a la actual');
                return false;
            }
            if($('#lote_nuevo').val() == ''){
                alert('Para finalizar es necesario indicar el nuevo Lote');
                return false;
            }
            if($('#stock_nuevo').val() == ''){
                alert('Verifica la cantidad del Stock antes de finalizar');
                return false;
            }
            if(+$('#stock_actual').val() < +$('#stock_nuevo').val()){
                alert('Recuerda que no puede pasar del stock actual');
                return false;
            }

        });

    });
</script>

@endsection