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
		<div class="col s6 xl8 offset-xl2">
			<h5>Datos Jurisdicción</h5>
			<div class="row">
				<div class="input-field col s6">
					<input type="text" name="jurisdiccion" id="jurisdiccion" class="validate" value="{{old('jurisdiccion')}}">
					<label for="jurisdiccion">Jurisdiccion</label>
					@if ($errors->has('jurisdiccion'))
						<span class="help-block">
						<strong>{{ $errors->first('jurisdiccion') }}</strong>
					</span>
					@endif
				</div>
				<div class="input-field col s6 m6">
					<select name="fk_id_estado" id="fk_id_estado">
						<option value="" disabled selected>Selecciona...</option>
						@foreach($states as $estado)
							<option value="{{$estado->id_estado}}"
									{{ $estado->id_estado == old('fk_id_estado') ? 'selected' : '' }}
							>{{$estado->estado}}</option>
						@endforeach
					</select>
					<label for="fk_id_estado">Estado</label>
				</div>
			</div>
		</div>
	</form>
@endsection
