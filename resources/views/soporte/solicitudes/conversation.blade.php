@extends('layouts.dashboard')

@section('title', 'Editar')

@section('header-top')
@endsection

@section('header-bottom')
	<script type="text/javascript" src="{{ asset('js/seguimiento.js') }}"></script>
@endsection

@section('content')
	<div class="col s12 xl8 offset-xl2">
		<h5>Conversación del ticket {{$data->id_solicitud}}</h5>
		@foreach($data->seguimiento as $seguimiento)
			<div>Mensaje</div>
			<div class="row">
				<div class="input-field col s4">
					<input type="text" name="empleado{{$seguimiento->id_seguimiento}}"
						   id="empleado{{$seguimiento->id_seguimiento}}" value="{{ $seguimiento->empleado->nombre
							  .' '.$seguimiento->empleado->apellido_paterno.' '.$seguimiento->empleado->apellido_materno}}"
						   readonly >
					<label for="empleado{{$seguimiento->id_seguimiento}}">Remitente: </label>
				</div>
				<div class="input-field col s4">
					<input type="text" name="asunto{{$seguimiento->id_seguimiento}}"
						   id="asunto{{$seguimiento->id_seguimiento}}" value="{{ $seguimiento->asunto}}"
						   readonly >
					<label for="asunto{{$seguimiento->id_seguimiento}}">Asunto: </label>
				</div>
				<div class="input-field col s4">
					<input type="text" name="fecha{{$seguimiento->id_seguimiento}}"
						   id="fecha{{$seguimiento->id_seguimiento}}" value="{{ $seguimiento->fecha_hora}}"
						   readonly >
					<label for="fecha{{$seguimiento->id_seguimiento}}">Fecha y Hora: </label>
				</div>
			</div>
		<div class="row">
				<div class="input-field col s12">
						<textarea name="comentario{{$seguimiento->id_seguimiento}}" id="comentario{{$seguimiento->id_seguimiento}}"
								  class="materialize-textarea" readonly>{{$seguimiento->comentario}}</textarea>
					<label for="comentario{{$seguimiento->id_seguimiento}}">Comentario</label>
				</div>
		</div>
			<div class="row">
			@foreach($seguimiento->archivo_adjunto as $archivo_adjunto)
					<a href="{{companyRoute('descargarArchivosAdjuntos', ['id' => $archivo_adjunto->id_archivo_adjunto])}}">
						<i class="material-icons">attachment</i>{{$archivo_adjunto->nombre_archivo}}
					</a>
			@endforeach
			</div>
		@endforeach
<form action="{{ companyRoute('Soporte\SeguimientoSolicitudesController@store') }}" method="post" class="col s12" enctype="multipart/form-data">
	{{ csrf_field() }}
	{{ method_field('POST') }}
		<div class="row">
			<div>Responder</div>
			<input type="hidden" name="fk_id_solicitud" id="fk_id_solicitud" value="{{ $data->id_solicitud}}">
			<input type="hidden" name="fk_id_empleado_comentario" id="fk_id_empleado_comentario"
				   data-url="{{companyRoute('RecursosHumanos\EmpleadosController@obtenerEmpleado')}}">
			<div class="input-field col s12">
				<input type="text" name="asunto" id="asunto" class="validate" value="{{ old('asunto')}}">
				<label for="asunto">Asunto</label>
				@if ($errors->has('asunto'))
					<span class="help-block">
						<strong>{{ $errors->first('asunto') }}</strong>
					</span>
				@endif
			</div>
			<div class="input-field col s12">
				<textarea name="comentario" id="comentario" class="materialize-textarea">{{old('comentario')}}</textarea>
				<label for="comentario">Comentario</label>
				@if ($errors->has('comentario'))
					<span class="help-block">
						<strong>{{ $errors->first('comentario') }}</strong>
					</span>
				@endif
			</div>
		</div>
		<div class="row">
			<div class="file-field input-field col s12">
				<div class="btn">
					<span><i class="material-icons">file_upload</i>Anexar archivos</span>
					<input type="file" name="archivo[]" id="archivo" multiple>
				</div>
				<div class="file-path-wrapper">
					<input class="file-path validate" type="text" placeholder="Upload one or more files">
				</div>
			</div>
		</div>
		<div class="col s12 xl8 offset-xl2">
			<div class="row">
				<div class="center">
					<button type="submit" class="waves-effect btn orange">Responder</button>
					<a href="{{ url()->previous() }}" class="waves-effect waves-teal btn-flat teal-text">Cancelar y salir</a>
				</div>
			</div>
		</div>
</form>
	</div>
@endsection
