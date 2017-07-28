@extends('layouts.dashboard')

@section('title', 'Crear')

@section('header-top')
@endsection

@section('header-bottom')
    <script src="{{ asset('js/empleados.js')  }}" type="text/javascript"></script>
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
					<input type="text" name="nombre" id="nombre" class="validate" value="{{old('nombre')}}">
					<label for="nombre">Nombre(s)</label>
					@if ($errors->has('nombre'))
						<span class="help-block">
							<strong>{{ $errors->first('nombre') }}</strong>
						</span>
					@endif
				</div>
				<div class="input-field col s4">
					<input type="text" name="apellido_paterno" id="apellido_paterno" class="validate" value="{{old('apellido_paterno')}}">
					<label for="apellido_paterno">Apellido paterno</label>
					@if ($errors->has('apellido_paterno'))
						<span class="help-block">
							<strong>{{ $errors->first('apellido_paterno') }}</strong>
						</span>
					@endif
				</div>
				<div class="input-field col s4">
					<input type="text" name="apellido_materno" id="apellido_materno" class="validate" value="{{old('apellido_materno')}}">
					<label for="apellido_materno">Apellido materno</label>
					@if ($errors->has('apellido_materno'))
						<span class="help-block">
							<strong>{{ $errors->first('apellido_materno') }}</strong>
						</span>
					@endif
				</div>
			</div>
			<div class="row">
				<div class="input-field col s2">
                    <label for="fecha_nacimiento">Fecha de nacimiento</label>
                    <input type="text" name="fecha_nacimiento" id="fecha_nacimiento" class="datepicker" value="{{old('fecha_nacimiento')}}">
                @if ($errors->has('fecha_nacimiento'))
						<span class="help-block">
							<strong>{{ $errors->first('fecha_nacimiento') }}</strong>
						</span>
					@endif
				</div>
				<div class="input-field col s5">
					<input type="text" name="curp" id="curp" class="validate" value="{{old('curp')}}">
					<label for="curp">CURP</label>
					@if ($errors->has('curp'))
						<span class="help-block">
							<strong>{{ $errors->first('curp') }}</strong>
						</span>
					@endif
				</div>
				<div class="input-field col s5">
					<input type="text" name="rfc" id="rfc" class="validate" value="{{old('rfc')}}">
					<label for="rfc">RFC</label>
					@if ($errors->has('rfc'))
						<span class="help-block">
							<strong>{{ $errors->first('rfc') }}</strong>
						</span>
					@endif
				</div>
			</div>
			<div class="row">
                <div class="input-field col s4">
                    <input type="text" name="correo_personal" id="correo_personal" class="validate" value="{{old('correo_personal')}}">
                    <label for="correo_personal">Correo</label>
                    @if ($errors->has('correo_personal'))
                        <span class="help-block">
							<strong>{{ $errors->first('correo_personal') }}</strong>
						</span>
                    @endif
                </div>
                <div class="input-field col s4">
                    <input type="text" name="telefono" id="telefono" class="validate" value="{{old('telefono')}}">
                    <label for="telefono">Teléfono</label>
                    @if ($errors->has('telefono'))
                        <span class="help-block">
							<strong>{{ $errors->first('telefono') }}</strong>
						</span>
                    @endif
                </div>
                <div class="input-field col s4">
                    <input type="text" name="celular" id="celular" class="validate" value="{{old('celular')}}">
                    <label for="celular">Celular</label>
                    @if ($errors->has('celular'))
                        <span class="help-block">
							<strong>{{ $errors->first('celular') }}</strong>
						</span>
                    @endif
                </div>
			</div>
            <div class="row">
                <div class="input-field col s4">
                    <select name="fk_id_empresa_alta_imss" id="fk_id_empresa_alta_imss">
                        @foreach($companies as $empresa)
                            <option value="{{$empresa->id_empresa}}"
                                    {{ $empresa->id_empresa == old('fk_id_empresa_alta_imss') ? 'selected' : '' }}
                            >{{$empresa->nombre_comercial}}</option>
                        @endforeach
                    </select>
                    <label for="fk_id_empresa_alta_imss">Empresa alta IMSS</label>
                </div>
                <div class="input-field col s4">
                    <input type="text" name="numero_imss" id="numero_imss" class="validate">
                    <label for="numero_imss">Número IMSS</label>
                    @if ($errors->has('numero_imss'))
                        <span class="help-block">
							<strong>{{ $errors->first('numero_imss') }}</strong>
						</span>
                    @endif
                </div>
                <div class="input-field col s4">
                    <select name="fk_id_empresa_laboral" id="fk_id_empresa_laboral">
                        @foreach($companies as $empresa)
                            <option value="{{$empresa->id_empresa}}"
                                    {{$empresa->id_empresa == old('fk_id_empresa_laboral') ? 'selected' : ''}}
                            >{{$empresa->nombre_comercial}}</option>
                        @endforeach
                    </select>
                    <label for="fk_id_empresa_laboral">Empresa Laboral</label>
                </div>
            </div>
            <div class="row">
                <div class="col s4">
                    <p>
                        <input type="checkbox" id="infonavit_activo" name="infonavit_activo" onclick="activar_infonavit()"
                            {{old('infonavit_activo') ? 'checked' : ''}}
                        />
                        <label for="infonavit_activo">¿Tiene INFONAVIT?</label>
                    </p>
                </div>
                <div class="input-field col s4">
                    <input type="text" name="numero_infonavit" id="numero_infonavit" class="validate" disabled value="{{old('numero_infonavit')}}">
                    <label for="numero_infonavit">Número INFONAVIT</label>
                    @if ($errors->has('numero_infonavit'))
                        <span class="help-block">
							<strong>{{ $errors->first('numero_imss') }}</strong>
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
