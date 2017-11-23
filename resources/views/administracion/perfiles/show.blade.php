@extends('layouts.dashboard')

@section('title', 'Ver')

@section('header-top')
@endsection

@section('header-bottom')
	<script src="{{ asset('js/perfiles.js') }}"></script>
@endsection

@section('content')
<div class="container-fluid">

	<div class="col-12">
		<div class="text-right">
			<a href="{{ companyRoute("edit",['id' => $data->id_perfil, 'company'=> $company]) }}" class="btn btn-primary progress-button"><i class="material-icons align-middle">mode_edit</i> Editar</a>
			<a href="{{ companyRoute("index",['company'=> $company]) }}" class="btn btn-default progress-button">Regresar</a>
		</div>
	</div>

	<div class="row">

	<div class="col-sm-12 col-md-5">
		<h3>Perfil</h3>
		<div class="row">
			<div class="col-sm-12 col-md-7">
				<div class="form-group">
					<label for="nombre_perfil">Perfil:</label>
					<input id="nombre_perfil" name="nombre_perfil" type="text" class="validate form-control" readonly value="{{$data->nombre_perfil}}">
				</div>
			</div>
			<div class="col-sm-12 col-md-5">
				<div class="form-group">
					<label for="descripcion">Descripción:</label>
					<input id="descripcion" name="descripcion" type="text" class="validate form-control" readonly value="{{$data->descripcion}}">
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-12">
				<label for="users">Usuarios asignados:</label>
				<select id="users" multiple class="form-control">
					<option disabled selected>Selecciona...</option>
					{{--For usuarios--}}
							{{--if--}}
						@foreach($users as $user)
						<option value="{{$user->id_usuario}}" {{in_array($user->id_usuario,$data->usuarios->pluck('id_usuario')->toArray()) ? 'selected' : ''}}>{{$user->usuario}}</option>
							{{--end if--}}
						@endforeach
					{{--end for usuarios--}}
				</select>
			</div>
		</div>
	</div>

	<div class="col-sm-12 col-md-7">
		<h3>Empresas</h3>
		<div class="card z-depth-1-half">
			<div class="card-header white-text">
				<p>Recueda que cada empresa tiene sus permisos diferentes.</p>
				<ul class="nav nav-pills card-header-pills nav-justified" role="tablist">
					@foreach($empresas as $empresa)
						<li class="nav-item"><a data-toggle="tab" role="tab" aria-controls="{{$empresa->nombre_comercial}}" aria-expanded="true" class="nav-link" href="#e_{{$empresa->nombre_comercial}}">{{$empresa->nombre_comercial}}</a></li>
					@endforeach
				</ul>
			</div>
			<div class="card-body">
			<div id="myTabContent" class="tab-content">
			@foreach($empresas as $empresa)
				<div id="e_{{$empresa->nombre_comercial}}" class="tab-pane fade show active" role="tabpanel" aria-labelledby="{{$empresa->nombre_comercial}}-tab">
					<div class="row">
						<div class="col-12">
							<table class="table table-responsive-sm table-striped table-hover">
								<thead>
									<tr>
										<th>Módulo</th>
										<th>Acciones</th>
									</tr>
								</thead>
								<tbody>
									@foreach($empresa->modulos as $module)

									    <tr>
									      	<th>
									      		{{--for para modulos--}}
												{{--in_array($user->id_usuario,$data->usuarios->pluck('id_usuario')->toArray())--}}
												<input type="checkbox" id="{{$empresa->id_empresa}}{{$module->id_modulo}}" />
												<label for="{{$empresa->id_empresa}}{{$module->id_modulo}}" class="parent_checkbox">{{$module->nombre}}</label>
									      	</th>
											<td>
												<div>
													<input type="checkbox" id="check1{{$empresa->id_empresa}}{{$module->id_modulo}}" class="fac1_child" checked="checked" />
													<label for="check1{{$empresa->id_empresa}}{{$module->id_modulo}}">Crear</label>
												</div>
												<div>
													<input type="checkbox" id="check2{{$empresa->id_empresa}}{{$module->id_modulo}}" class="fac1_child" checked="checked"/>
													<label for="check2{{$empresa->id_empresa}}{{$module->id_modulo}}">Editar</label>
												</div>
												<div>
													<input type="checkbox" id="check3{{$empresa->id_empresa}}{{$module->id_modulo}}" class="fac1_child" checked="checked" />
													<label for="check3{{$empresa->id_empresa}}{{$module->id_modulo}}">Borrar</label>
												</div>
												<div>
													<input type="checkbox" id="check4{{$empresa->id_empresa}}{{$module->id_modulo}}" class="fac1_child" checked="checked" />
													<label for="check4{{$empresa->id_empresa}}{{$module->id_modulo}}">Ver</label>
												</div>
												{{--end for modulos--}}
											</td>
									    </tr>

									@endforeach
								</tbody>
							</table>
						</div>
					</div>
				</div><!--/aquí termina el contenido de un tab-->
			@endforeach
			</div>
			</div>
		</div>
	</div><!--/col-s12 m4-->

	</div><!--/row-->
</div>
@endsection
