@extends(smart())
@section('content-width')

@section('form-content')
    {{ Form::setModel($data) }}
    <div class="row mb-3">
    	<div class="col-sm-4 col-md-4 col-12">
    		{{ Form::cText('* Nombre','nombre') }}
    	</div>
    	<div class="col-sm-4 col-md-4 col-12">
    		{{ Form::cText('* Url','url') }}
    	</div>
    	<div class="col-sm-4 col-md-4 col-12">
    		{{ Form::cText('* Icono','icono') }}
    	</div>
    </div>
    <div class="row">
    	<div class="col-md-12">
			<div class="form-group">
				{{ Form::cTextArea('* Descripción','descripcion') }}
			</div>
    	</div>
    </div>
    <div class="row">
    	<div class="col-md-4 col-sm-4 col-12">
    		{{ Form::hidden('accion_menu', 0) }}
    		{{ Form::checkbox('accion_menu', null, old('accion_menu'), ['id'=>'accion_menu']) }}
    		{{ Form::label('accion_menu', '¿Accion Menu?') }}
    	</div>
    	<div class="col-md-4 col-sm-4 col-12">
    		{{ Form::hidden('accion_barra', 0) }}
    		{{ Form::checkbox('accion_barra', null, old('accion_barra'), ['id'=>'accion_barra']) }}
    		{{ Form::label('accion_barra', '¿Accion Menu?') }}
    	</div>
    	<div class="col-md-4 col-sm-4 col-12">
    		{{ Form::hidden('accion_tabla', 0) }}
    		{{ Form::checkbox('accion_tabla', null, old('accion_tabla'), ['id'=>'accion_tabla']) }}
    		{{ Form::label('accion_tabla', '¿Accion Menu?') }}
    	</div>
    </div>
	<div class="col-md-12">
		<div class="alert alert-warning" role="alert">
			Recuerda que al no estar <b>activo</b>, este <b>dato</b> no se mostrara en los modulos correspondientes que se requieran.
		</div>
		{{ Form::cCheckboxBtn('Estatus','Activo','activo', $data['activo'] ?? null, 'Inactivo') }}
	</div>
@endsection

@section('form-utils')
	<div id="modal-1" class="modal bottom-sheet">
		<div class="modal-content">
			<h5 class="teal-text"><i class="material-icons">announcement</i> RFC:</h5>
			<ul class="collection">
            	<li class="collection-item">
                	<i class="material-icons teal-text">info</i>
                	<span class="title">Publico General: XAXX010101000.</span>
                </li>
                <li class="collection-item">
                	<i class="material-icons teal-text">info</i>
                  	<span class="title">Extranjero: XEXX010101000.</span>
                </li>
            </ul>
			<br>
		</div>
	</div>
@endsection