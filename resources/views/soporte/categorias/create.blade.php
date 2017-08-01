@extends('layouts.dashboard')

@section('title', 'Crear')

@section('header-top')
@endsection

@section('header-bottom')
@endsection

@section('content')

	<form action="{{ companyRoute('index') }}" method="post" class="col s12">
		{{ csrf_field() }}
		{{ method_field('POST') }}
		<div class="col s12 xl8 offset-xl2">
			<div class="row">
				<div class="right">
					<button type="submit" class="waves-effect btn orange">Guardar y salir</button>
					<a href="{{ url()->previous() }}" class="waves-effect waves-teal btn-flat teal-text">Cancelar y salir</a>
				</div>
			</div>
		</div>
		<div class="col s12 xl8 offset-xl2">
			<h5>Datos Categorias</h5>
			<div class="row">
				<div class="input-field col s12">
					<input type="text" name="categoria" id="categoria" class="validate" value="{{old('categoria')}}">
					<label for="categoria">Categoría</label>
					@if ($errors->has('categoria'))
						<span class="help-block">
						<strong>{{ $errors->first('categoria') }}</strong>
					</span>
					@endif
				</div>
			</div>
		</div>
	</form>
@endsection