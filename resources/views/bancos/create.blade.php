@extends('layouts.dashboard')

@section('title', 'Crear')

@section('header-top')
@endsection

@section('header-bottom')
	<script src="{{ asset('js/bancos.js') }}"></script>
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
		<form action="{{ route("$entity.index") }}" method="post" class="col s12">
			{{ csrf_field() }}
			{{ method_field('POST') }}
			<div class="row">
				<div class="input-field col s12">
					<input type="text" name="razon_social" id="razon_social" class="validate">
					<label for="razon_social">Razón Social</label>
					@if ($errors->has('razon_social'))
						<span class="help-block">
							<strong>{{ $errors->first('razon_social') }}</strong>
						</span>
					@endif
				</div>
			</div>
			<div class="row">
				<div class="input-field col s6">
					<input type="text" name="banco" id="banco" class="validate">
					<label for="banco">Banco</label>
					@if ($errors->has('banco'))
						<span class="help-block">
							<strong>{{ $errors->first('banco') }}</strong>
						</span>
					@endif
				</div>
				<div class="input-field col s6">
					<input type="text" name="rfc" id="rfc" class="validate">
					<label for="rfc">RFC</label>
					@if ($errors->has('rfc'))
						<span class="help-block">
							<strong>{{ $errors->first('rfc') }}</strong>
						</span>
					@endif
				</div>
			</div>
			<div class="row">
				<div class="col s12">
					<p>
						<input type="checkbox" id="nacional" name="nacional" />
						<label for="nacional">¿Banco nacional?</label>
					</p>
					@if ($errors->has('nacional'))
						<span class="help-block">
							<strong>{{ $errors->first('nacional') }}</strong>
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
