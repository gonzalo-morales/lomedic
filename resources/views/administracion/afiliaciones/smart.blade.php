@extends(smart())
@section('content-width', 's12')


@if (Route::currentRouteNamed(currentRouteName('index')))
    @section('form-title', 'Afliados')
@elseif(Route::currentRouteNamed(currentRouteName('create')))
    @section('form-title', 'Nuevo afiliado')
@elseif(Route::currentRouteNamed(currentRouteName('edit')))
    @section('form-title', 'Editar afiliado')
@elseif(Route::currentRouteNamed(currentRouteName('show')))
    @section('form-title', 'Afiliado')
@endif

@section('header-bottom')
    @parent
    @if (!Route::currentRouteNamed(currentRouteName('index')) && !Route::currentRouteNamed(currentRouteName('show')) )
        <script type="text/javascript" src="{{ asset('js/afiliados.js') }}"></script>
    @endif
@endsection

@section('form-content')
    {{ Form::setModel($data) }}
        <div class="row">
            <div class="col-12 mb-3">
                <div class="tab-content">
                    <div class="tab-pane active" role="tabpanel">
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="form-group">
                                    {{ Form::cText('* Nombre', 'nombre',['class'=>'form-control'])}}
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    {{ Form::cText('* Apellido paterno', 'paterno',['class'=>'form-control'])}}
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    {{ Form::cText('* Apellido materno', 'materno',['class'=>'form-control']) }}
                                </div>
                            </div>
                            <div class="col-sm-1">
                                <div class="form-group">
                                    {{Form::cRadio('* Genero','genero',['M'=>'Masculino','F'=>'Femenino'],['class'=>'form-control'])}}
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    {{Form::cText('* Fecha de nacimiento','fecha_nacimiento',['class'=>'datepicker'])}}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="form-group">
                                    {{ Form::cText('* Numero de paciente', 'id_afiliacion',['class'=>'form-control'])}}
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    {{ Form::cSelect('* Parentesco', 'fk_id_parentesco', $parentescos ?? [],['class'=>'select2']) }}
                                </div>
                            </div>
                            @if (!Route::currentRouteNamed(currentRouteName('show')))
                            <div class="col-sm-3">
                                <div class="form-group">
                                    {{ Form::cSelect('* Paciente', 'fk_id_afiliacion', $afiliados ?? [],['class'=>'select2','data-url'=>companyRoute('getDependientes'),'disabled']) }}
                                </div>
                            </div>
                            @endif
                            <div class="col-sm-4">
                                <div class="form-group">
                                    {{ Form::cSelect('* Empresa cliente', 'fk_id_cliente', $clientes ?? [],['class'=>'select2']) }}
                                </div>
                            </div>
                            <input type="hidden" name="id_dependiente" id="id_dependiente" value="0">
                        </div>
                    </div>
                </div>
            </div><!--/row-->
        </div>
@endsection

