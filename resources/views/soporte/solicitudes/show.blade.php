@extends('layouts.dashboard')

@section('title', 'Ver ticket')

@section('header-top')
@endsection

@section('header-bottom')
	<script type="text/javascript" src="{{ asset('js/seguimiento.js') }}"></script>
@endsection

@section('content')
<div class="col s12 xl8 offset-xl2">
	<div class="row">
		<div class="col s12">
			<p class="right-align">
				<a href="{{ companyRoute('index') }}" class="waves-effect waves-light btn btn-flat teal-text">Regresar</a>
			</p>
		</div>
	</div>
</div>
<div class="row">
	<div class="col s12 m6">
		<h5 class="grey-text text-darken-2">{{$data->empleado->nombre.' '.$data->empleado->apellido_paterno.' '.$data->empleado->apellido_materno}}</h5>
	</div>
	<div class="col s6 m3">
		<h5 class="red-text"><i class="material-icons">priority_high</i>Prioridad alta</h5>
	</div>
	<div class="col s6 m3">
		<h5 class="grey-text text-darken-2 facturas-line">Ticket No. {{$data->id_solicitud}}<br>
			<span><i class="tiny material-icons">today</i> {{$data->fecha_hora_creacion}}</span>
		</h5>
	</div>
</div><!--/row principal data-->
{{--Datos del ticket--}}

<div class="row">
	<form action="{{ companyRoute('update') }}" method="post" class="col s12">
		{{ csrf_field() }}
		{{ method_field('PUT') }}
		<div class="col s12 m4">
			<h5>Datos del ticket</h5>
			<div class="card-panel teal lighten-5">
				<p>Asunto: <b>{{$data->asunto}}</b></p>
				<p>Categoría: <b>{{$data->categoria->categoria}}</b></p>
				<p>Sucategoría: <b>{{$data->subcategoria->subcategoria}}</b></p>
				<p>Acción: <b>{{$data->accion->accion}}</b></p>
				<p>Descripción: {{ $data->descripcion}}</p>
				<ul>
					@foreach($data->archivos_adjuntos as $archivo_adjunto)
						<li>
							<a href="{{companyRoute('descargarArchivosAdjuntos', ['id' => $archivo_adjunto->id_archivo_adjunto])}}">
								<i class="material-icons">attachment</i>{{$archivo_adjunto->nombre_archivo}}
							</a>
						</li>
					@endforeach
				</ul>
			</div>
		</div><!--/col s12 m4-->
			@if($data->fk_id_empleado_tecnico == Auth::id() || $data->fk_id_empleado_tecnico == null) {{--Si es el técnico asignado--}}
			<div class="col s12 m8">
				<h5>Datos adicionales sobre la solución del ticket</h5>
				<div class="col s3">
					<label for="fk_id_empleado_tecnico">Asignado a:</label>
					<select name="fk_id_empleado_tecnico" id="fk_id_empleado_tecnico">
					<option selected disabled>Selecciona un técnico</option>
						@foreach($employees as $empleado)
							@if($empleado->fk_id_departamento == 18) {{--Si pertenece al área de sistemas--}}
								<option value="{{$empleado->id_empleado}}"
								{{$empleado->id_empleado == $data->fk_id_empleado_tecnico
								? 'selected'
								: ''}}
								>{{$empleado->nombre.' '.$empleado->apellido_paterno.' '.$empleado->apellido_materno}}</option>
							@endif
						@endforeach
					</select>
				</div>
				<div class="col s3">
					<label for="fk_id_estatus_ticket">Estatus:</label>
					<select name="fk_id_estatus_ticket" id="fk_id_estatus_ticket">
						@foreach($status as $estatus)
							<option value="{{$estatus->id_estatus_ticket}}"
							{{$data->fk_id_estatus_ticket == $estatus->id_estatus_ticket
							? 'selected'
							: ''}}
							>{{$estatus->estatus}}</option>
						@endforeach
					</select>
				</div>
				<div class="col s3">
					<label for="fk_id_impacto">Estatus:</label>
					<select name="fk_id_impacto" id="fk_id_impacto">
						@foreach($impacts as $impacto)
							<option value="{{$impacto->id_impacto}}"
							{{$data->fk_id_impacto == $impacto->id_impacto
							? 'selected'
							: ''}}
							>{{$impacto->impacto}}</option>
						@endforeach
					</select>
				</div>
				<div class="col s3">
					<label for="fk_id_urgencia">Urgencia:</label>
					<select name="fk_id_urgencia" id="fk_id_urgencia">
						@foreach($urgencies as $urgencia)
							<option value="{{$urgencia->id_urgencia}}"
							{{$data->fk_id_urgencia == $urgencia->id_urgencia
							? 'selected'
							: ''}}
							>{{$urgencia->urgencia}}</option>
						@endforeach
					</select>
				</div>
			</div>
			<div class="col s6">
				<p>
					<input type="checkbox" id="solucion" name="solucion"
					   {{$data->resolucion != null ? 'checked' : ''}}
					   onclick="descripcion()">
					<label for="solucion">¿Solucionado?</label>
				</p>
			</div>
			<div class="col s6">
				<label for="resolucion">Descripción de resolución:</label>
					<textarea class="materialize-textarea" name="resolucion" id="resolucion" disabled>{{$data->resolucion}}</textarea>
			</div>
			<div class="col s6">
				<p class="right-align">
					<button class="waves-effect waves-light btn btn-flat teal-text">Guardar cambios</button>
				</p>
			</div>
		@elseif($employee_department != 18 || $data->fk_id_empleado_tecnico != Auth::id()) {{--Si no es el técnico asignado y no pertenece a sistemas, no podrá editar los valores--}}
		<div class="col s12 m8">
			<h5>Datos adicionales sobre la solución del ticket</h5>
			<div class="col s3 row">
				<label>Asignado a:</label>
				<p>{{
				$data->fk_id_empleado_tecnico
				? $data->empleado_tecnico->nombre.' '.$data->empleado_tecnico->apellido_paterno.' '.$data->empleado_tecnico->apellido_materno
				: 'Sin encargado'
				}}</p>
			</div>
			<div class="col s3">
				<label>Estatus:</label>
				<p class="green-text"><i class="material-icons"></i>{{$data->estatusTickets->estatus}}</p>
			</div>
		</div>
		<div class="col s12 row m5">
			<h5>Solución:</h5>
			<div class="col s6">
				<label>Fecha</label>
				<p><i class="material-icons">event</i>{{$data->fecha_hora_resolucion}}</p>
			</div>
			<div class="col s12">
				<label>Descripción de resolución:</label>
				<p>{{$data->resolucion ? $data->resolucion : 'Ticket sin resolver'}}</p>
			</div>
			@endif
		</div>
	</form>
</div><!--/row-->
{{--Fin datos del ticket--}}
{{--Conversación--}}
@if(Auth::id() == $data->fk_id_tecnico_asignado || Auth::id() == $data->fk_id_empleado_solicitud)
<ul class="collection with-header">
	<li class="collection-header"><h4>Chat</h4></li>
	@foreach($data->seguimiento as $seguimiento)
		<li class="collection-item avatar lighten-5 {{$seguimiento->fk_id_empleado_comentario == $data->fk_id_empleado_tecnico
			? 'teal'
			: ''}}">
			<i class="material-icons circle">person</i>
			<span class="title"><b>{{ $seguimiento->empleado->nombre
							  .' '.$seguimiento->empleado->apellido_paterno.' '.$seguimiento->empleado->apellido_materno}}</b>
				<br><i class="material-icons tiny">event</i>{{ $seguimiento->fecha_hora}}</span>
			<p>{{$seguimiento->comentario}}
			<ul>
				@foreach($seguimiento->archivo_adjunto as $archivo_adjunto)
					<li><a href="{{companyRoute('descargarArchivosAdjuntos', ['id' => $archivo_adjunto->id_archivo_adjunto])}}">
						<i class="material-icons">attachment</i>{{$archivo_adjunto->nombre_archivo}}
					</a></li>
				@endforeach
			</ul>
			</p>
		</li>
	@endforeach
	<li class="collection-item avatar row">
		<i class="material-icons circle">person</i>
		<span class="title"><b>{{$data->empleado->nombre.' '.$data->empleado->apellido_paterno.' '.$data->empleado->apellido_materno}}</b></span>
		<form action="{{ companyRoute('Soporte\SeguimientoSolicitudesController@index') }}" method="post" class="col s12" enctype="multipart/form-data">
			{{ csrf_field() }}
			{{ method_field('POST') }}
		<div class="input-field col s12">
			<input type="hidden" name="fk_id_solicitud" id="fk_id_solicitud" value="{{ $data->id_solicitud}}">
			<input type="hidden" name="fk_id_empleado_comentario" id="fk_id_empleado_comentario"
				   data-url="{{companyRoute('RecursosHumanos\EmpleadosController@obtenerEmpleado')}}">{{-- El empleado que está logueado --}}
			<input type="text" id="asunto" name="asunto" class="input-field" value="{{old('asunto')}}">
			<label for="asunto">Asunto</label>
			@if ($errors->has('asunto'))
				<span class="help-block">
						<strong>{{ $errors->first('asunto') }}</strong>
					</span>
			@endif
		</div>
		<div class="input-field col s12">
			<textarea id="comentario" name="comentario" class="materialize-textarea">{{old('comentario')}}</textarea>
			<label for="comentario">Respuesta</label>
			@if ($errors->has('comentario'))
				<span class="help-block">
						<strong>{{ $errors->first('comentario') }}</strong>
					</span>
			@endif
		</div>
		<div class="file-field input-field col s12">
			<div class="btn">
				<span><i class="material-icons">file_upload</i>Anexar archivos</span>
				<input type="file" name="archivo[]" id="archivo" multiple>
			</div>
			<div class="file-path-wrapper">
				<input class="file-path validate" type="text" placeholder="Anexa uno o más archivos">
			</div>
		</div>
		<div class="file-field input-field col s12">
			<button class="btn waves-effect waves-light">Responder</button>
		</div>
		</form>
	</li>
	<li class="collection-item"><!--Item para finalizar chat-->
		<h5 class="green-text center"><b>El ticket se ha cerrado con éxito, si tienes algún problema adicional te recomendamos dar de alta otro ticket.</b></h5>
	</li>
</ul>
@endif
{{--Fin conversación--}}
@endsection
