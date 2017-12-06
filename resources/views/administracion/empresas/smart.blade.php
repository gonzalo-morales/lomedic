@section('content-width', 's12')
@section('header-bottom')
	@parent
	@if(!Route::currentRouteNamed(currentRouteName('index')))
    	<script type="text/javascript">
        	var estados_js = '{{ $js_estados ?? '' }}';
        	var municipios_js = '{{ $js_municipios ?? '' }}';
        </script>
    	{{ HTML::script(asset('js/administracion/empresas.js')) }}
    @endif
@endsection

@section('form-content')
{{ Form::setModel($data) }}
<div class="row">
	<div class="col-sm-9 row">
    	<div class="form-group col-md-8 col-xs-12">
    		{{ Form::cText('* Nombre Comercial', 'nombre_comercial') }}
    	</div>
    	<div class="form-group col-md-4 col-xs-12">
    		{{ Form::cSelect('Conexion', 'conexion',  [null=>'Sin conexion'] + array_combine(array_keys(config('database.connections')), array_keys(config('database.connections'))) ) }}
    	</div>
    	<div class="form-group col-md-2 col-xs-12">
    		{{ Form::cCheckboxBtn('Es empresa','Si','empresa', $data['empresa'] ?? null, 'No') }}
    	</div>
    	
    	<div  class="text-center col-md-5 col-xs-12 row">
    		<div class="col-md-6">
    			<label class="w-100">Icono</label>
    			{{ HTML::image(asset("img/logotipos/".($data['icono'] ?? "")), null, ['height'=>'60px','class'=>'mb-3']) }}
    		</div>
    		<div class="col-sm-6">
            	{{ Form::cFile('Cambiar Icono', 'f-icono',['accept'=>'image/*']) }}
            </div>
    	</div>
    	<div  class="text-center col-md-5 col-xs-12 row mt-4">
    		<div class="col-sm-6">
    			<label class="w-100">Logotipo</label>
    			{{ HTML::image(asset("img/logotipos/".($data['logotipo'] ?? "")), null, ['height'=>'60px','class'=>'mb-3']) }}
    		</div>
    		<div class="col-sm-6">
    			{{ Form::cFile('Cambiar Logotipo', 'f-logotipo',['accept'=>'image/*']) }}
    		</div>
    	</div>
    </div>
    
    <div class="col-sm-3">
		<div  class="col-md-12 text-center">
    		<div class="alert alert-warning" role="alert">
                Recuerda que al no estar <b>activo</b>, este <b>dato</b> no se mostrara en los modulos correspondientes que se requieran.
            </div>
            {{ Form::cCheckboxBtn('Estatus','Activo','activo', $data['activo'] ?? null, 'Inactivo') }}
    	</div>
    </div>
</div>

<div class="row mb-3 card z-depth-1-half">
	<div class="card-header mb-0 pb-0">
		<ul class="nav nav-pills nav-justified">
			<li class="nav-item"><a class="nav-link active"  role="tab" data-toggle="tab"  href="#datos">Datos Fiscales</a></li>
			<li class="nav-item"><a class="nav-link" role="tab" data-toggle="tab"  href="#certificados">Certificados</a></li>
			<li class="nav-item"><a class="nav-link" role="tab" data-toggle="tab"  href="#series">Series</a></li>
		</ul>
	</div>
	<div class="card-body tab-content">
		<div  class="tab-pane active" id="datos" role="tabpanel">
			<div class="row">
				<div class="form-group col-md-6 col-xs-12">
            		{{ Form::cText('* Razon Social', 'razon_social') }}
            	</div>
            	<div class="form-group col-md-3 col-xs-12">
            		{{ Form::cText('* Rfc', 'rfc') }}
            	</div>
            	<div class="form-group col-md-3 col-xs-12">
            		{{ Form::cSelect('* Regimen Fiscal', 'fk_id_regimen_fiscal',  $regimens ?? []) }}
            	</div>
            	
            	<div class="form-group col-md-5 col-xs-12">
            		{{ Form::cText('* Calle', 'calle') }}
            	</div>
            	<div class="form-group col-md-2 col-xs-12">
            		{{ Form::cText('* No. Exterior', 'no_exterior') }}
            	</div>
            	<div class="form-group col-md-2 col-xs-12">
            		{{ Form::cText('No. Interior', 'no_interior') }}
            	</div>
            	<div class="form-group col-md-3 col-xs-12">
            		{{ Form::cNumber('* Codigo Postal', 'cp') }}
            	</div>
            	
            	<div class="form-group col-sm-6 col-md-3">
            		{{ Form::cText('* Colonia', 'colonia') }}
            	</div>
            	<div class="form-group col-sm-6 col-md-3">
            		{{ Form::cSelect('* País', 'fk_id_pais', $paises ?? []) }}
            	</div>
            	<div class="form-group col-sm6 col-md-3">
            		{{ Form::cSelect('* Estado', 'fk_id_estado', [], ['data-url'=>companyAction('HomeController@index').'/administracion.estados/api']) }}
            	</div>
            	<div class="form-group col-sm-6 col-md-3">
            		{{ Form::cSelect('* Municipio', 'fk_id_municipio', [], ['data-url'=>companyAction('HomeController@index').'/administracion.municipios/api']) }}
            	</div>
			</div>
		</div><!--/aquí termina el contenido de un tab-->
		
		<div  class="tab-pane" id="certificados" role="tabpanel">
			<div class="row card">
				<div class="card-header">
					<div class="row">
						<div class="form-group col-md-3">
							{{ Form::cFile('* Key', 'file_key',['accept'=>'.pem']) }}
						</div>
						<div class="form-group col-md-3">
							{{ Form::cFile('* Certificado', 'file_certificado',['accept'=>'.pem']) }}
						</div>
						<div class="form-group col-md-3">
							{{ Form::cText('* Fecha expedicion', 'fecha_expedicion') }}
						</div>
						<div class="form-group col-md-3">
							{{ Form::cText('* Fecha vencimiento', 'fecha_vencimiento') }}
						</div>
					</div>
					<div class="form-group col-md-12 my-3">
						<div class="sep sepBtn">
    						<button id="agregar-certificado" class="btn btn-primary btn-large btn-circle" data-placement="bottom" data-delay="100" data-tooltip="Agregar" data-toggle="tooltip" data-action="add" title="Agregar" type="button"><i class="material-icons">add</i></button>
    					</div>
					</div>
				</div>
				<div class="card-body">
					<table class="table responsive-table highlight" id="tCertificados">
						<thead>
							<tr>
								<th>Key</th>
								<th>Certificado</th>
								<th>Fecha expedicion</th>
								<th>Fecha vencimiento</th>
								<th>Acción</th>
							</tr>
						</thead>
						<tbody>
						@if(isset($data->certificados)) 
							@foreach($data->certificados->where('eliminar',0) as $key=>$detalle)
							<tr>
								<td>
									{!! Form::hidden('certificados['.$key.'][id_certificado]',$detalle->id_certificado,['class'=>'id_certificado']) !!}
									<a class="btn is-icon text-primary bg-white" href="{{companyAction('descargar', ['id' => $detalle->id_certificado,'archivo'=>'key'])}}" title="Descargar Key">
										{{$detalle->key}}
									</a>
								</td>
								<td>
									<a class="btn is-icon text-primary bg-white" href="{{companyAction('descargar', ['id' => $detalle->id_certificado,'archivo'=>'certificado'])}}" title="Descargar Certificado">
										{{$detalle->certificado}}
									</a>
								</td>
								<td>
									{{$detalle->fecha_expedicion}}
								</td>
								<td>
									{{$detalle->fecha_vencimiento}}
								</td>
								<td>
    								<button class="btn is-icon text-primary bg-white" type="button" data-delay="50" onclick="borrarCertificado(this)"> <i class="material-icons">delete</i></button>
    							</td>
							</tr>
							@endforeach
						@endif
						</tbody>
					</table>
				</div>
			</div><!--/Here ends card-->
		</div><!--/aquí termina el contenido de un tab-->
		
		<div  class="tab-pane" id="series" role="tabpanel">
			<div class="row">
				
			</div>
		</div><!--/aquí termina el contenido de un tab-->
	</div>
</div>
@endsection
{{-- DONT DELETE --}}
@if (Route::currentRouteNamed(currentRouteName('index')))
	@include('layouts.smart.index')
@endif

@if (Route::currentRouteNamed(currentRouteName('create')))
	@include('layouts.smart.create')
@endif

@if (Route::currentRouteNamed(currentRouteName('edit')))
	@include('layouts.smart.edit')
@endif

@if (Route::currentRouteNamed(currentRouteName('show')))
	@include('layouts.smart.show')
@endif

@if (Route::currentRouteNamed(currentRouteName('export')))
	@include('layouts.smart.export')
@endif