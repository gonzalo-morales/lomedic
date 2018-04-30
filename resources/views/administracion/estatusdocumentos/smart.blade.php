@extends(smart())
@section('header-bottom')
    @parent
        @notroute(['index'])
            <script src="{{asset('js/administracion/estatusdocumentos.js')}}"></script>
        @endif
@endsection
@section('form-content')

    <script type="text/javascript">
        @isset($data->tiposdocumentos_count)
            var cantidad = '{{$data->withCount('tiposdocumentos')->find($data->id_estatus)->tiposdocumentos_count ?? 0}}';
        @endif
    </script>
	{{ Form::setModel($data) }}
    <div class="row">
        <div class="form-group col-md-6 col-xs-6">
            {{ Form::cText('* Nombe de Estatus','estatus') }}
            {!! Form::cText('* Clase','class',['maxlength'=>30]) !!}
            <div align="center">
                {{ Form::cCheckboxBtn('CFDI','Sí','cfdi', $data['cfdi'] ?? null, 'No') }}
            </div>
            <div  class="col-md-12 text-center mt-4">
                <div class="alert alert-warning" role="alert">
                    Recuerda que al no estar <b>activo</b>, este <b>dato</b> no se mostrara en los modulos correspondientes que se requieran.
                </div>
                {{ Form::cCheckboxBtn('Estatus','Activo','activo', $data['activo'] ?? null, 'Inactivo') }}
            </div>
        </div>
        <div class="form-group col-md-6 col-xs-6">
            {!! Form::cSelect('* Tipos de Documento','tiposdocumentos[]',$tiposdocumentos ?? null,['id'=>'tiposdocumentos','multiple'=>'multiple','class'=>'select2']) !!}
            <div align="center">
                {!! Form::cCheckbox('Seleccionar todos','todos') !!}
            </div>
        </div>

    </div>
@endsection