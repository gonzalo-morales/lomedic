@extends('layouts.dashboard')

@section('title', 'Ver ticket')

@section('header-top')
@endsection

@section('header-bottom')

@endsection

@section('content')
<div class="col s12 xl8 offset-xl2">
	<div class="row">
		<div class="col s12">
			<p class="right-align">
				<a href="{{ companyRoute('Soporte\SolicitudesController@conversation', ['id'=> $data->id_solicitud])}}" class="waves-effect waves-light btn orange"><i class="material-icons right">message</i>Ver conversación</a>
				<a href="{{ companyRoute('index') }}" class="waves-effect waves-light btn btn-flat teal-text">Regresar</a>
			</p>
		</div>
	</div>
</div>
<div class="col s12 xl8 offset-xl2">
	<h5>Datos del ticket</h5>
	<div class="row">
		<div class="input-field col s6">
			<input type="text" id="asunto" name="asunto" value="{{$data->asunto}}" readonly>
			<label for="asunto">Asunto</label>
		</div>
		<div class="input-field col s4">
			<input type="text" id="encargado" name="encargado" value="{{
			$data->fk_id_empleado_tecnico
			? $data->empleado_tecnico->nombre.' '.$data->empleado_tecnico->apellido_paterno.' '.$data->empleado_tecnico->apellido_materno
			: 'Sin encargado'
			}}" readonly>
			<label for="encargado">Encargado de la revisión</label>
		</div>
		<div class="input-field col s2">
			<input type="text" id="estado_ticket" name="estado_ticket" value="{{$data->estatusTickets->estatus}}" readonly>
			<label for="estado_ticket">Estatus del Ticket</label>
		</div>
		<div class="input-field col s12">
			<label for="descripcion">Descripcion</label>
			<textarea name="descripcion" id="descripcion" class="materialize-textarea" readonly>{{ $data->descripcion}}</textarea>
		</div>
		<div class="input-field col s6">
			<input type="text" id="solicitante" name="solicitante" value="{{$data->nombre_solicitante}}" readonly>
			<label for="solicitante">Nombre del solicitante</label>
		</div>
		<div class="input-field col s6">
			<input type="text" id="sucursal_nombre" nombre="sucursal_nombre" value="{{$data->sucursal->nombre_sucursal}}" readonly>
			<label for="sucursal_nombre">Sucursal</label>
		</div>
		<div class="input-field col s4">
			<input type="text" id="category" nombre="category" value="{{$data->categoria->categoria}}" readonly>
			<label for="category">Categoría</label>
		</div>
		<div class="input-field col s4">
			<input type="text" id="subcategory" nombre="subcategory" value="{{$data->subcategoria->subcategoria}}" readonly>
			<label for="subcategory">Subcategoría</label>
		</div>
		<div class="input-field col s4">
			<input type="text" id="action" name="action" value="{{$data->accion->accion}}" readonly>
			<label for="action">Acción</label>
		</div>
		<label>{{$data->fecha_hora_resolucion != null ? 'Fecha y hora de resolución: '.$data->fecha_hora_resolucion : ''}}</label>
		<div class="input-field col s12">
			<textarea readonly class="materialize-textarea" name="resolucion">{{$data->resolucion ? $data->resolucion : 'Sin resolución'}}</textarea>
			<label for="resolucion">Resolución</label>
		</div>
		<div>
			<label>Archivos Adjuntos:</label>
			{{--Por cada archivo adjunto--}}
			@foreach($data->archivos_adjuntos as $archivo_adjunto)
				<br>
				<a href="{{companyRoute('descargarArchivosAdjuntos', ['id' => $archivo_adjunto->id_archivo_adjunto])}}">
					<i class="material-icons">attachment</i>{{$archivo_adjunto->nombre_archivo}}
				</a>
			@endforeach
		</div>
	</div>
</div>
@endsection
