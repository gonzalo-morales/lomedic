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
	<div class="col s12 m8">
		<h5>Datos adicionales sobre la solución del ticket</h5>
		<div class="col s6">
			<label>Asignado a:</label>
			<p>{{
			$data->fk_id_empleado_tecnico
			? $data->empleado_tecnico->nombre.' '.$data->empleado_tecnico->apellido_paterno.' '.$data->empleado_tecnico->apellido_materno
			: 'Sin encargado'
			}}</p>
		</div>
		<div class="col s6">
			<label>Estatus:</label>
			<p class="green-text"><i class="material-icons"></i>{{$data->estatusTickets->estatus}}</p>
		</div>
		<h5>Solución:</h5>
		<div class="col s6">
			<label>Fecha</label>
			<p><i class="material-icons">event</i>02/12/2017</p>
		</div>
		<div class="col s12">
			<label>Descripción</label>
			<p>{{$data->resolucion ? $data->resolucion : 'Ticket sin resolver'}}</p>
		</div>
	</div><!--/col s12 m4-->
</div><!--/row-->
{{--Fin datos del ticket--}}
{{--Conversación--}}
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
{{--Fin conversación--}}
@endsection
