@extends('layouts.dashboard')

@section('title', 'Crear')

@section('header-top')
@endsection

@section('header-bottom')
	<script src="{{ asset('js/dataTableGeneralConfig.js') }}"></script>
@endsection

@section('content')
<div class="col s12 xl8 offset-xl2">
	<p class="left-align">
		<a href="{{ url()->previous() }}" class="waves-effect waves-light btn">Regresar</a> <br>
	</p>
	<div class="divider"></div>
</div>
<div class="col s12 xl8 offset-xl2">
	<h4>Capturar nuevo {{ trans_choice('messages.'.$entity, 0) }}</h4>
</div>

<div class="col s12 xl8 offset-xl2">
	<div class="row">
		<form action="{{ companyRoute("index", ['company'=> $company]) }}" method="post" class="col s12">
			{{ csrf_field() }}
			{{ method_field('POST') }}
			<div class="row">
				<div class="input-field col s6">
					<input type="text" name="municipio" id="municipio" class="validate">
					<label for="municipio">Municipio</label>
					@if ($errors->has('municipio'))
						<span class="help-block">
							<strong>{{ $errors->first('municipio') }}</strong>
						</span>
					@endif
				</div>
				<div class="input-field col s6">
					<p>
						<input type="hidden" name="activo" value="0">
						<input type="checkbox" id="activo"  name="activo" checked="true"/>
						<label for="activo">¿Activo?</label>
					</p>

					@if ($errors->has('activo'))
						<span class="help-block">
							<strong>{{ $errors->first('activo') }}</strong>
						</span>
					@endif
				</div>
			</div>
			<div class="row">
				<div class="input-field col s6 ">
					<select name="estados">
						<option disabled selected>Estado</option>
						@foreach ($estados as $estado)
						<option value="{{$estado->id_estado}}" >{{$estado->estado}}</option>
						@endforeach
					</select>
					@if ($errors->has('estados'))
						<span class="help-block">
							<strong>{{ $errors->first('estados') }}</strong>
						</span>
					@endif
				</div>
			</div>
			<div class="row">
				<div class="col s12">
					<button class="waves-effect waves-light btn right">Guardar {{ trans_choice('messages.'.$entity, 0) }}</button>
				</div>
			</div>
		</form>
	</div>
</div>
@endsection
