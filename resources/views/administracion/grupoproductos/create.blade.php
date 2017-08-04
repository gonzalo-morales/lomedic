@extends('layouts.dashboard')

@section('title', 'Crear')

@section('header-top')
@endsection

@section('header-bottom')
	<script src="{{ asset('js/bancos.js') }}"></script>
    {{--<script src="{{ asset('js/InitiateComponents.js') }}"></script>--}}
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
				<div class="input-field col s4">
					<input type="text" name="descripcion" id="descripcion" class="validate" value="{{ old('descripcion')  }}">
					<label for="descripcion">Nombre descripcion</label>
					@if ($errors->has('descripcion'))
						<span class="help-block">
							<strong>{{ $errors->first('descripcion') }}</strong>
						</span>
					@endif
				</div>
			</div>
			<div class="row">
				<div class="input-field col s4">
					<input type="text" name="descripcion_producto" id="descripcion_producto" class="validate" value="{{ old('descripcion_producto')  }}">
					<label for="descripcion">Descripcion del producto</label>
					@if ($errors->has('descripcion_producto'))
						<span class="help-block">
							<strong>{{ $errors->first('descripcion_producto') }}</strong>
						</span>
					@endif
				</div>
			</div>
			<div class="row">
				<div class="input-field col s4">
					<input type="text" name="nomenclatura" id="nomenclatura" class="validate" value="{{ old('nomenclatura')  }}">
					<label for="descripcion">Nomenclatura</label>
					@if ($errors->has('nomenclatura'))
						<span class="help-block">
							<strong>{{ $errors->first('nomenclatura') }}</strong>
						</span>
					@endif
				</div>
			</div>
			<div class="row">
				<div class="input-field col s4">
					<input type="text" name="tipo" id="tipo" class="validate" value="{{ old('tipo')  }}">
					<label for="descripcion">Tipo</label>
					@if ($errors->has('tipo'))
						<span class="help-block">
							<strong>{{ $errors->first('tipo') }}</strong>
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
