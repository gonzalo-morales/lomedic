@extends('layouts.dashboard')

@section('title', 'Crear')

@section('header-bottom')
	<script src="{{ asset('js/perfiles.js') }}"></script>
@endsection

@section('content')
	<form action="{{ companyRoute("index",['company' => $company]) }}" method="post" class="col s12">
		{{ csrf_field() }}
		{{ method_field('POST') }}
	<div class="col-12">
		<div class="text-right">
			<button class="btn btn-primary progress-button" name="action">Guardar</button>
			<a href="{{ url()->previous() }}" class="btn btn-default progress-button">Cancelar y salir</a>
		</div>
	</div><!--/row buttons-->

	<div class="row">

		<div class="col s12 m5">
			<h3>Perfil</h3>
			<div class="row">
				<div class="col-sm-12 col-md-7">
					<div class="form-group">
						<label for="nombre_perfil">Perfil:</label>
						<input id="nombre_perfil" name="nombre_perfil" type="text" class="validate form-control">
					</div>
				</div>
				<div class="col-sm-12 col-md-5">
					<div class="form-group">
						<label for="descripcion">Descripción:</label>
						<input id="descripcion" name="descripcion" type="text" class="validate form-control">
					</div>
				</div>
			</div>

			<pre>
			<!--{{ print_r($errors)  }}-->
			</pre>

			<div class="row">
				<div class="col-12">
					<div class="form-group">
					<label>Usuarios asignados:</label>
					<select name="usuarios[]" multiple class="form-control">
						<option value="" disabled selected>Selecciona...</option>
						@foreach($users as $user)
							<option value="{{$user->id_usuario}}">{{$user->usuario}}</option>
						@endforeach
					</select>
					</div>
				</div>
			</div>
		</div>


	</div><!--/row-->
	</form>

@endsection
