@extends('handheld.layout')

@section('title', 'Handheld - Inventario')

@section('content')
{!! Form::open(['url' => companyRoute('handheld.inventarios-inventario-detalle-store'),'method'=>'post','enctype' => 'multipart/form-data','id'=>'form']) !!}
    <input type="hidden" name="fk_id_inventario" value="{{ $previous->fk_id_inventario }}">
    <input type="hidden" name="fk_id_almacen" value="{{ $previous->fk_id_almacen }}">
    <input type="hidden" name="fk_id_ubicacion" value="{{ $previous->fk_id_ubicacion }}">
    <div>
        <table class="table-columns">
            <tr>
                <td class="column right">
                    <label>Ubicación</label>
                </td>
                <td class="column">
                    <input class="form-control readonly" type="text" value="{{ $previous->nomenclatura }}" readonly>
                </td>
            </tr>
            <tr>
                <td class="column right">
                    <label>Código</label>
                </td>
                <td class="column">
                    <input class="form-control readonly" type="text" name="codigo_barras" value="{{ $previous->codigo_barras }}" readonly>
                </td>
            </tr>
            <tr>
                <td class="column right">
                    <label for="no_lote">No. Lote</label>
                </td>
                <td class="column">
                    <input class="form-control" id="no_lote" name="no_lote" type="text">
                </td>
            </tr>
            <tr>
                <td class="column right">
                    <label for="caducidad">Caducidad</label>
                </td>
                <td class="column">
                    <input class="form-control" id="caducidad" placeholder="AAAA-MM-DD" name="caducidad" type="text">
                </td>
            </tr>
            <tr>
                <td class="column right">
                    <label for="cantidad_toma">Cantidad</label>
                </td>
                <td class="column">
                    <input class="form-control" id="cantidad_toma" name="cantidad_toma" type="text">
                </td>
            </tr>
        </table>
        <div class="wrapper margin-top">
            {{ link_to(companyRoute('handheld.inventarios-inventario', [
                'id' => $previous->fk_id_inventario,
                'fk_id_ubicacion' => $previous->fk_id_ubicacion,
                'codigo_barras' => $previous->codigo_barras
            ]), 'Regresar', ['class'=>'square actionBtn green']) }}
            <button type="submit" class="square blue actionBtn">Finalizar</button>
            {{ link_to(companyRoute('handheld.inventarios'), 'Cancelar', ['class'=>'square red actionBtn']) }}
        </div>
    </div>
{!! Form::close() !!}

<script type="text/javascript">

    function GetDescriptionFor(e) {
        var result, code, key;
        if (e.charCode && e.keyCode == 0) {
            result = "charCode: " + e.charCode;
            code = e.charCode;
        } else {
            result = "keyCode: " + e.keyCode;
            code = e.keyCode;
        }

        if (code == 8) key = "BKSP";
        else if (code == 9) key = "TAB";
        else if (code == 46) key = "DEL";
        else if (code >= 41 && code <= 126) key = String.fromCharCode(code);

        // if (e.shiftKey) result += " shift";
        // if (e.ctrlKey) result += " ctrl";
        // if (e.altKey) result += " alt";

        return {keycode: code, key: key};
    }

    function datemask(v) {
        if (v.match(/^\d{4}$/) !== null) {
            return v = v + '-';
        } else if (v.match(/^\d{4}\-\d{2}$/) !== null) {
            return v = v + '-';
        }
        return v;
    }

    $(function() {
        // alert(2)

        $(document).on("keypress", "#form", function(event) {
            return event.keyCode != 13;
        });

        // Placeholder jquery
        $("input").each(function(){""===$(this).val()&&void 0!==$(this).attr("placeholder")&&""!==$(this).attr("placeholder")&&($(this).val($(this).attr("placeholder")),$(this).addClass("placeholder")),void 0!==$(this).attr("placeholder")&&""!==$(this).attr("placeholder")&&($(this).focus(function(){$(this).val()===$(this).attr("placeholder")&&$(this).removeClass("placeholder").val("")}),$(this).blur(function(){""===$(this).val()&&($(this).val($(this).attr("placeholder")),$(this).addClass("placeholder"))}),$(this).change(function(){""!==$(this).val()&&$(this).removeClass("placeholder")}))}),$("textarea").each(function(){""===$(this).val()&&void 0!==$(this).attr("placeholder")&&""!==$(this).attr("placeholder")&&($(this).val($(this).attr("placeholder")),$(this).addClass("placeholder")),void 0!==$(this).attr("placeholder")&&""!==$(this).attr("placeholder")&&($(this).focus(function(){$(this).val()===$(this).attr("placeholder")&&$(this).removeClass("placeholder").val("")}),$(this).blur(function(){""===$(this).val()&&($(this).val($(this).attr("placeholder")),$(this).addClass("placeholder"))}),$(this).change(function(){""!==$(this).val()&&$(this).removeClass("placeholder")}))}),$("form").submit(function(){$("input").each(function(){$(this).val()===$(this).attr("placeholder")&&$(this).val("")}),$("textarea").each(function(){$(this).val()===$(this).attr("placeholder")&&$(this).val("")})});

        // Validar fecha
        $('#caducidad').on({
            keydown: function(e) {
                if (!new RegExp('^[0-9]|BKSP|TAB+$').test(GetDescriptionFor(e).key) || (!new RegExp('^BKSP|TAB$').test(GetDescriptionFor(e).key) && this.value.match(/^\d{4}\-\d{2}\-\d{2}$/) !== null)) {
                    e.preventDefault();
                }
            },
            keyup: function(e) {
                this.value = datemask(this.value)
            },
            blur: function(e) {
                if (this.value != '' && this.value != $(this).attr('placeholder') && !new RegExp('^([0-9]{4}[-](0[1-9]|1[0-2])[-]([0-2]{1}[0-9]{1}|3[0-1]{1})|([0-2]{1}[0-9]{1}|3[0-1]{1})[-](0[1-9]|1[0-2])[-][0-9]{4})$').test(this.value)) {
                    alert('Fecha invalida')
                    this.value = '';
                    this.focus();
                }
            }
        });

        // Validamos cantidad
        $('#cantidad_toma').on({
            keydown: function(e) {
                if (!new RegExp('^[0-9]|BKSP|TAB+$').test(GetDescriptionFor(e).key)) {
                    e.preventDefault();
                }
            }
        });

        $('#form').on('submit', function(e){
            e.preventDefault();

            if ($('#no_lote').val() == '') {
                alert('No. Lote requerido')
                $('#no_lote').focus()
                return false;
            }

            if ($('#caducidad').val() == '' || $('#caducidad').val() == $('#caducidad').attr('placeholder')) {
                alert('Fecha caducidad requerida')
                $('#caducidad').focus()
                return false;
            }

            if ($('#cantidad_toma').val() == '') {
                alert('Cantidad requerida')
                $('#cantidad_toma').focus()
                return false;
            }

            // Enviamos formulario
            this.submit();

        });

        // Init
        $('#no_lote').focus()

    });
</script>

@endsection