@extends('layouts.dashboard')

@section('title', 'Ver')

@section('header-top')
@endsection

@section('header-bottom')
	<script src="{{ asset('js/perfiles.js') }}"></script>
@endsection

@section('content')
<div class="col s12 xl8 offset-xl2">
	<div class="row">
		<div class="col s6">
			<p class="left-align">
				<a href="{{ route("$entity.index",['company'=> $company]) }}" class="waves-effect waves-light btn">Regresar</a> <br>
			</p>
		</div>
		<div class="col s6">
			<p class="right-align">
				<a href="{{ route("$entity.edit",['id' => $data->id_correo, 'company'=> $company]) }}" class="waves-effect waves-light btn"><i class="material-icons right">mode_edit</i>Editar</a>
			</p>
		</div>
	</div>
	<div class="divider"></div>
</div>

<div class="row">

	<div class="col s12 m5">
		<h5>Perfil</h5>
		<div class="row">
			<div class="input-field col s12 m7">
				<input id="nombre_perfil" type="text" class="validate" readonly value="{{$data->nombre_perfil}}">
				<label for="nombre_perfil">Perfil:</label>
			</div>
			<div class="input-field col s12 m5">
				<input id="descripcion" type="text" class="validate" readonly value="{{$data->descripcion}}">
				<label for="descripcion">Descripción:</label>
			</div>
		</div>

		<div class="row">
			<div class="col s12 m6">
				<label>Usuarios asignados:</label>
				<select multiple>
					<option disabled selected>Selecciona...</option>
					{{--For usuarios--}}
							{{--if--}}
						@foreach($users as $user)
						<option disabled value="{{$user->id_usuario}}" {{in_array($user->id_usuario,$data->usuarios->pluck('id_usuario')->toArray()) ? 'selected' : ''}}>{{$user->usuario}}</option>
							{{--end if--}}
						@endforeach
					{{--end for usuarios--}}
				</select>
			</div>
		</div>
	</div>

	<div class="col s12 m7">
		<h5>Empresas</h5>
		<div class="card teal">
			<div class="card-content white-text">
				<p>Recueda que cada empresa tiene sus permisos diferentes.</p>
			</div>
			<div class="card-tabs">
				<ul class="tabs tabs-fixed-width tabs-transparent">
					{{--For para empresas--}}
					<li class="tab"><a class="active" href="#e_loMedic">LoMedic</a></li>
					<li class="tab"><a class="active" href="#e_abisa">Abisa</a></li>
					{{--end for--}}
				</ul>
			</div>
			<div class="card-content teal lighten-5">
				{{--for para empresas--}}
				<div id="e_loMedic">
					<ul class="collapsible" data-collapsible="accordion">
						<li>
							{{--for para modulos--}}
							<div class="collapsible-header">
								<input type="checkbox" id="fac1_par_check" checked="checked" />
								<label for="fac1_par_check" class="parent_checkbox">Facturación</label>
							</div>
							<div class="collapsible-body grey lighten-3">
								<ul class="collection">
									<li class="collection-item">
										<input type="checkbox" id="fac1_check1" class="fac1_child" checked="checked" />
										<label for="fac1_check1">Crear</label>
									</li>
									<li class="collection-item">
										<input type="checkbox" id="fac1_check2" class="fac1_child" checked="checked"/>
										<label for="fac1_check2">Editar</label>
									</li>
									<li class="collection-item">
										<input type="checkbox" id="fac1_check3" class="fac1_child" checked="checked" />
										<label for="fac1_check3">Borrar</label>
									</li>
									<li class="collection-item">
										<input type="checkbox" id="fac1_check4" class="fac1_child" checked="checked" />
										<label for="fac1_check4">Ver</label>
									</li>
								</ul>
							</div>
							{{--end for modulos--}}
						</li>
					</ul>
				</div><!--/aquí termina el contenido de un tab-->

				{{--end for empresas--}}
			</div>
		</div>
	</div><!--/col-s12 m4-->
</div><!--/row-->
@endsection
