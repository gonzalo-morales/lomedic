@extends('layouts.dashboard')

@section('title', 'Ver')

@section('header-top')
@endsection

@section('header-bottom')

@endsection

@section('content')
<div class="col s12 xl8 offset-xl2">
	<div class="row">
		<div class="col s12">
			<p class="right-align">
				<a href="{{ companyRoute('edit') }}" class="waves-effect waves-light btn orange"><i class="material-icons right">mode_edit</i>Editar</a>
				<a href="{{ companyRoute('index') }}" class="waves-effect waves-light btn btn-flat teal-text">Regresar</a>
			</p>
		</div>
	</div>
</div>
<div class="col s12 xl8 offset-xl2">
	<h5>Datos de la Subcategoría</h5>
	<div class="row">
		<div class="input-field col s4 m5">
			<input type="text" name="accion" id="accion" class=""  readonly value="{{ $data->accion}}">
			<label for="accion">Acción</label>
		</div>
		<div class="input-field col s4">
			<input type="text" name="fk_id_subcategoria" id="fk_id_subcategoria" readonly value="{{$subcategory}}">
			<label for="fk_id_subcategoria">Subcategoria</label>
		</div>
		<div class="input-field col s4 m7">
			<input type="checkbox" id="activo" name="activo" disabled {{$data->activo ? 'checked' : ''}} />
			<label for="activo">Estatus:</label>
		</div>
	</div>
</div>
@endsection
