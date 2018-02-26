@extends(smart())
@section('content-width')

@section('form-content')
    {{ Form::setModel($data) }}
    <div class="row">
        <div class="col-md-7 col-sm-12">
            <div class="row">
                <div class="col-sm-12 col-md-7">
                    <div class="form-group">
                        {{ Form::cText('* Nombre Perfil','nombre_perfil') }}
                    </div>
                </div>
                <div class="col-sm-12 col-md-5">
                    <div class="form-group">
                        {{ Form::cText('* Descripción','descripcion') }}
                    </div>
                </div>
            </div>
            <div  class="col-md-12 text-center mt-4">
                <div class="alert alert-warning" role="alert">
                    Recuerda que al no estar <b>activo</b>, este <b>dato</b> no se mostrara en los modulos correspondientes que se requieran.
                </div>
                {{ Form::cCheckboxBtn('Estatus','Activo','activo', $data['activo'] ?? null, 'Inactivo') }}
            </div>
        </div>
        @if(isset($usuarios))
            <div class="col-md-5 col-sm-12">
                <div class="card z-depth-1-half" style="max-height: 260px;">
                    <div class="card-header">
                        <h5>* Usuarios</h5>
                    </div>
                    <div class="card-body" style="overflow: auto;">
                        <ul class="list-group">
                            @foreach ($usuarios as $row)
                            <li class="list-group-item form-group row">
                                {{ Form::cCheckbox($row['usuario'], 'usuarios['.$row['id_usuario'].']',['class'=>'socio-empresa'], (in_array($row['id_usuario'], $usuarios)?1:0) ) }}
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif
    </div>
    @if(isset($companies))
    <div class="row mb-3 mt-3">
        <div class="col-md-12">
            <div class="card z-depth-1-half">
                <h4 class="card-header">* Módulos y permisos</h4>
                <div class="card-body">
                    <p>Aquí se muestran las empresas que <b>cuentan con sus respectivos módulos y permisos</b></p>
                    <ul class="nav nav-tabs" role="tablist">
                        @foreach($companies as $data_company)
                            @if($data_company->modulos_empresa != '[]')
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#empresa_{{$data_company->id_empresa}}" role="tab">{{$data_company->nombre_comercial}}</a>
                                </li>
                            @endif
                        @endforeach

                    </ul>
                    <div class="tab-content">
                        @foreach($companies as $data_company)
                            <div class="tab-pane " id="empresa_{{$data_company->id_empresa}}" role="tabpanel">
                                <table class="table table-hover table-responsive-sm border-0">
                                    <thead>
                                    <tr>
                                        <th class="border-top-0">Modulo</th>
                                        <th class="border-top-0">Acciones</th>
                                        <th class="border-top-0"></th>
                                        <th class="border-top-0"></th>
                                        <th class="border-top-0"></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($data_company->modulos_empresa->unique() as $row_modul)
                                        <tr>
                                            <td>
                                                {{$row_modul->nombre}}
                                            </td>
                                            @foreach($data_company->accion_empresa($row_modul->id_modulo) as $row_accion)
                                                <td>
                                                    {{Form::checkbox('accion_modulo[]',$row_accion->id_modulo_accion,false,array('id'=>'accion_'.$row_accion->id_modulo_accion))}}
                                                    {{Form::label('accion_modulo[]',$row_accion->nombre,array('for'=>'activo'))}}
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endforeach
                    </div>
                </div>{{-- /cardbody --}}
            </div>
        </div>
    </div>
    @endif
@endsection

{{--  @section('header-bottom')
    @parent
    @inroute(['create','edit'])
        <script type="text/javascript">

        </script>
    @endif
@endsection  --}}