
@section('content-width', 's12')

@section('form-content')
{{ Form::setModel($data) }}


@if (Route::currentRouteNamed(currentRouteName('create')))
	@section('form-content')

		<div class="container-fluid">
			<div class="row">
				<div class="col-md-8 col-sm-12 mb-3">
					<div class="card">
						<div class="card-body">
							<div class="row">
								<div class="col-md-6 col-sm-6">
									<div class="form-group">
										{{Form::label('nombre_corto','Nombre',['for'=>'name'])}}
										{{Form::text('nombre_corto',null,array('id'=>'nombre_corto','class'=>'form-control','placeholder'=>'Ejemplo: Juan'))}}
									</div>
								</div>
								<div class="col-md-6 col-sm-6">
									<div class="form-group">
										{{Form::label('usuario','Usuario',['for'=>'usuarios'])}}
										{{Form::text('usuario','',array('id'=>'usuario','class'=>'form-control','placeholder'=>'Ejemplo: Juan'))}}
									</div>
								</div>
							</div><!--/row forms-->
							<div class="row">
								<div class="col-md-6 col-sm-6">
									<div class="form-group">
										{{Form::label('password','Contraseña',['for'=>'password'])}}
										{{Form::password('password',['class'=>'form-control','placeholder'=>'Contraseña'])}}
									</div>
								</div>
								<div class="col-md-6 col-sm-6">
									<div class="form-group">
										{{Form::label('fk_id_empresa_default','Empresa',['for'=>'fk_id_empresa_default'])}}
										{{ Form::select('fk_id_empresa_default',$companies->pluck('nombre_comercial','id_empresa'),null,['id'=>'fk_id_empresa_default','class'=>'form-control','placeholder' => 'Seleccionar una empresa...'])}}
									</div>
								</div>
							</div><!--/row forms-->
							<hr>
							<h5>Correo Empresarial</h5>
							<fieldset>
								<div class="card">
									<div class="card-header">
										<div class="row">
											<div class="col-md-6 col-sm-6">
												<div class="form-group">
													{{Form::label('empresa_correo','Empresa:',['for'=>'empresa_correo'])}}
													{{Form::select('empresa_correo',$companies->pluck('nombre_comercial','id_empresa'),null,['id'=>'empresa_correo','class'=>'form-control','placeholder' => 'Seleccionar una empresa...'])}}
												</div>
											</div>
											<div class="col-md-6 col-sm-6">
												<div class="form-group">
													{{Form::label('correo','Correo:',['for'=>'correo'])}}
													{{Form::text('correo',null,['id'=>'correo','class'=>'form-control','placeholder'=>'micorreogenial@mail.com'])}}
												</div>
											</div>
										</div><!--/row forms-->
									</div>
								</div>
								<div class="col-sm-12 text-center">
									<div class="sep">
										<div class="sepBtn">
											{{Form::button('<i class="material-icons">add</i>',['class'=>'btn btn-primary btn-large','onclick'=>'agregarCorreo()','style'=>'width: 4em; height:4em; border-radius:50%;','data-delay'=>'50','data-toggle'=>'Agregar','title'=>'Agregar'])}}
										</div>
									</div>
								</div>
								<div class="card-body">
									<table class="table table-hover table-responsive">
										<thead>
											<tr>
												<th>Empresa</th>
												<th>Correo</th>
											</tr>
										</thead>
										<tbody id="lista_correo"></tbody>
									</table>
								</div>
							</fieldset>
						</div>
					</div>
				</div>
				<div class="col-md-4 col-sm-12">
					<h5>Perfiles</h5>
					<div id="listProfiles" class="list-group">
						@foreach( $profiles as $profile )
							<a href="#" name="perfil[]"  class="list-group-item list-group-item-action" id="perfil_{{$profile->id_perfil}}" onclick="accionesPerfil(this.id)">{{$profile->nombre_perfil}}</a>
						@endforeach
					</div>
				</div>
			</div>
			<div class="row mb-3 mt-3">
				<div class="col-md-12">
					<div class="card">
						<h4 class="card-header">Empresas</h4>
						<div class="card-body">
							<ul class="nav nav-tabs" role="tablist">
								@foreach($companies as $data_company)
									<li class="nav-item">
										<a class="nav-link" data-toggle="tab" href="#empresa_{{$data_company->id_empresa}}" role="tab">{{$data_company->nombre_comercial}}</a>
									</li>
								@endforeach

							</ul>
							<div class="tab-content">
								@foreach($companies as $data_company)
									<div class="tab-pane " id="empresa_{{$data_company->id_empresa}}" role="tabpanel">
										<table class="table table-hover">
											<thead>
											<tr>
												<th>Modulo</th>
												<th>Accion</th>
												<th></th>
											</tr>
											</thead>
											<tbody>
											@foreach($data_company->modulos_empresa->unique() as $row_modul)
												<tr>
													<td>
														{{$row_modul->nombre}}
													</td>
													@foreach($data_company->accion_empresa($row_modul->id_modulo) as $row_accion)
														<td>
															{{Form::checkbox('accion_modulo[]',$row_accion->id_modulo_accion,false,array('id'=>'accion_'.$row_accion->id_modulo_accion))}}
															{{Form::label('accion_modulo[]',$row_accion->nombre,array('for'=>'activo'))}}
														</td>
													@endforeach
												</tr>
											@endforeach
											</tbody>
										</table>
									</div>
								@endforeach
							</div>
						</div>
					</div>
				</div>
			</div>
		</div><!--/container-fluid-->

	@endsection

@endif


@if (Route::currentRouteNamed(currentRouteName('show')))
	{{--{{dd($data)}}--}}
	@section('form-content')

		<div class="container-fluid">
			<div class="row">
				<div class="col-md-8 col-sm-12 mb-3">
					<div class="card">
						<div class="card-body">
							<div class="row">
								<div class="col-md-6 col-sm-6">
									<div class="form-group">
										{{Form::label('nombre_corto','Nombre',['for'=>'name'])}}
										{{Form::text('nombre_corto',null,array('id'=>'nombre_corto','class'=>'form-control','placeholder'=>'Ejemplo: Juan'))}}
									</div>
								</div>
								<div class="col-md-6 col-sm-6">
									<div class="form-group">
										{{Form::label('usuario','Usuario',['for'=>'usuarios'])}}
										{{Form::text('usuario','',array('id'=>'usuario','class'=>'form-control','placeholder'=>'Ejemplo: Juan'))}}
									</div>
								</div>
							</div><!--/row forms-->
							<div class="row">
								<div class="col-md-6 col-sm-6">
									<div class="form-group">
										{{Form::label('fk_id_empresa_default','Empresa',['for'=>'fk_id_empresa_default'])}}
										{{ Form::select('fk_id_empresa_default',$companies->pluck('nombre_comercial','id_empresa'),null,['id'=>'fk_id_empresa_default','class'=>'form-control','placeholder' => 'Seleccionar una empresa...'])}}
									</div>
								</div>
							</div><!--/row forms-->
							<hr>
							<h5>Correo Empresarial</h5>
							<fieldset>
								<div class="card-body">
									<table class="table table-hover table-responsive">
										<thead>
										<tr>
											<th>Empresa</th>
											<th>Correo</th>
										</tr>
										</thead>
										<tbody id="lista_correo">

											@foreach($correos as $correo)
												<tr>
													<td>{{$correo->nombre_comercial}}</td>
													<td>{{$correo->correo}}</td>
												</tr>

											@endforeach

										</tbody>
									</table>
								</div>
							</fieldset>
						</div>
					</div>
				</div>
				<div class="col-md-4 col-sm-12">
					<h5>Perfiles</h5>
					<div id="listProfiles" class="list-group">
						@foreach( $profiles as $profile )
							<a href="#" name="perfil[]"  class="list-group-item list-group-item-action" id="perfil_{{$profile->id_perfil}}" onclick="accionesPerfil(this.id)">{{$profile->nombre_perfil}}</a>
						@endforeach
					</div>
				</div>
			</div>
			<div class="row mb-3 mt-3">
				<div class="col-md-12">
					<div class="card">
						<h4 class="card-header">Empresas</h4>
						<div class="card-body">
							<div class="tab-content">
								@foreach($companies as $data_company)
									<h4>{{$data_company->nombre_comercial}}</h4>
										<table class="table table-hover">
											<thead>
											<tr>
												<th>Modulo</th>
												<th>Accion</th>
												<th></th>
											</tr>
											</thead>
											<tbody>
											@foreach($data_company->modulos_usuario($data->id_usuario,$data_company->id_empresa)->unique() as $row_modul)
												<tr>
													<td>
														{{$row_modul->nombre}}
													</td>
													@foreach($data_company->accion_usuario($data->id_usuario,$data_company->id_empresa,$row_modul->id_modulo) as $row_accion)
														<td>
															{{$row_accion->nombre}}
														</td>
													@endforeach
												</tr>
											@endforeach
											</tbody>
										</table>
									{{--</div>--}}
								@endforeach
							</div>
						</div>
					</div>
				</div>
			</div>
		</div><!--/container-fluid-->

	@endsection

@endif


@if (Route::currentRouteNamed(currentRouteName('edit')))

@section('form-content')
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-8 col-sm-12 mb-3">
				<div class="card">
					<div class="card-body">
						<div class="row">
							<div class="col-md-6 col-sm-6">
								<div class="form-group">
									{{Form::label('nombre_corto','Nombre',['for'=>'name'])}}
									{{Form::text('nombre_corto',$data->nombre_corto,array('id'=>'nombre_corto','class'=>'form-control','placeholder'=>'Ejemplo: Juan'))}}
								</div>
							</div>
							<div class="col-md-6 col-sm-6">
								<div class="form-group">
									{{Form::label('usuario','Usuario',['for'=>'usuarios'])}}
									{{Form::text('usuario',$data->usuario,array('id'=>'usuario','class'=>'form-control','placeholder'=>'Ejemplo: Juan'))}}
								</div>
							</div>
						</div><!--/row forms-->
						<div class="row">
							{{--<div class="col-md-6 col-sm-6">--}}
								{{--<div class="form-group">--}}
									{{--{{Form::label('password','Contraseña',['for'=>'password'])}}--}}
									{{--{{Form::password('password',['class'=>'form-control','placeholder'=>'Contraseño'],$data->password)}}--}}
								{{--</div>--}}
							{{--</div>--}}
							<div class="col-md-6 col-sm-6">
								<div class="form-group">
									{{Form::label('fk_id_empresa_default','Empresa',['for'=>'fk_id_empresa_default'])}}
									{{ Form::select('fk_id_empresa_default',$companies->pluck('nombre_comercial','id_empresa'),null,['id'=>'fk_id_empresa_default','class'=>'form-control','placeholder' => 'Seleccionar una empresa...'])}}
								</div>
							</div>
						</div><!--/row forms-->
						<hr>
						<h5>Correo Empresarial</h5>
						<fieldset>
							<div class="card">
								<div class="card-header">
									<div class="row">
										<div class="col-md-6 col-sm-6">
											<div class="form-group">
												{{Form::label('empresa_correo','Empresa:',['for'=>'empresa_correo'])}}
												{{Form::select('empresa_correo',$companies->pluck('nombre_comercial','id_empresa'),null,['id'=>'empresa_correo','class'=>'form-control','placeholder' => 'Seleccionar una empresa...'])}}
											</div>
										</div>
										<div class="col-md-6 col-sm-6">
											<div class="form-group">
												{{Form::label('correo','Correo:',['for'=>'correo'])}}
												{{Form::text('correo',null,['id'=>'correo','class'=>'form-control','placeholder'=>'micorreogenial@mail.com'])}}
											</div>
										</div>
									</div><!--/row forms-->
								</div>
							</div>
							<div class="col-sm-12 text-center">
								<div class="sep">
									<div class="sepBtn">
										{{Form::button('<i class="material-icons">add</i>',['class'=>'btn btn-primary btn-large','onclick'=>'agregarCorreo()','style'=>'width: 4em; height:4em; border-radius:50%;','data-delay'=>'50','data-toggle'=>'Agregar','title'=>'Agregar'])}}
									</div>
								</div>
							</div>
							<div class="card-body">
								<table class="table table-hover table-responsive">
									<thead>
									<tr>
										<th>Empresa</th>
										<th>Correo</th>
										<th></th>
									</tr>
									</thead>
									<tbody id="lista_correo">
										@foreach( $correos as $cont => $correo )
											{{--{{dump($correo)}}--}}
											<tr id="correo_{{$cont}}">
												<th scope="row">{{$correo->nombre_comercial}}</th>
												<td>{{$correo->correo}}</td>
												<td><a href="javascript:void(0)" class="waves-effect " onclick="eliminarFila('correo_{{$cont}}')"><i class="material-icons">delete</i></a></td>
												<input type="hidden" value="{{$correo->fk_id_empresa}}" name="correo_empresa[{{$cont}}][id_empresa]">
												<input type="hidden" value="{{$correo->correo}}" name="correo_empresa[{{$cont}}][correo]">

											</tr>
										@endforeach


									</tbody>
								</table>
							</div>
						</fieldset>
					</div>
				</div>
			</div>
			<div class="col-md-4 col-sm-12">
				<h5>Perfiles</h5>

				<div class="btn-group list-group">
					@foreach( $profiles as $profile )
						@if( array_intersect( $perfiles_usuario->pluck('nombre_perfil','id_perfil')->toArray() ,$profile->toArray() ) )
							<a href="#" class="list-group-item list-group-item-action active" id="perfil_{{$profile->id_perfil}}" onclick="accionesPerfil(this.id)">
								<input type="checkbox"  name="perfil[]"  id="perfil_check_{{$profile->id_perfil}}" value="{{$profile->id_perfil}}" checked="checked" style="display: none">{{$profile->nombre_perfil}}
							</a>
						@else
							<a href="#" name="perfil[]" class="list-group-item list-group-item-action " id="perfil_{{$profile->id_perfil}}" onclick="accionesPerfil(this.id)">
								<input type="checkbox" name="perfil[]" id="perfil_check_{{$profile->id_perfil}}" value="{{$profile->id_perfil}}"  style="display: none">{{$profile->nombre_perfil}}
							</a>
						@endif
					@endforeach

				</div>
			</div>
		</div>
		<div class="row mb-3 mt-3">
			<div class="col-md-12">
				<div class="card">
					<h4 class="card-header">Empresas</h4>
					<div class="card-body">
						<ul class="nav nav-tabs" role="tablist">
							@foreach($companies as $data_company)
								<li class="nav-item">
									<a class="nav-link" data-toggle="tab" href="#empresa_{{$data_company->id_empresa}}" role="tab">{{$data_company->nombre_comercial}}</a>
								</li>
							@endforeach

						</ul>
						<div class="tab-content">
							@foreach($companies as $data_company)
								<div class="tab-pane " id="empresa_{{$data_company->id_empresa}}" role="tabpanel">
									<table class="table table-hover">
										<thead>
										<tr>
											<th>Modulo</th>
											<th>Accion</th>
											<th></th>
										</tr>
										</thead>
										<tbody>
										@foreach($data_company->modulos_empresa->unique() as $row_modul)
											<tr>
												<td>
													{{$row_modul->nombre}}
												</td>
												@foreach($data_company->accion_empresa($row_modul->id_modulo) as $row_accion)
													<td>
														@if(array_search($row_accion->id_modulo_accion, array_column($acciones_usuario->toArray(), 'id_modulo_accion')) !== false)
															{{Form::checkbox('accion_modulo[]',$row_accion->id_modulo_accion,true,array('id'=>'accion_'.$row_accion->id_modulo_accion))}}
														@else
															{{Form::checkbox('accion_modulo[]',$row_accion->id_modulo_accion,false,array('id'=>'accion_'.$row_accion->id_modulo_accion))}}
														@endif
														{{Form::label('accion_modulo[]',$row_accion->nombre,array('for'=>'activo'))}}
													</td>
												@endforeach
											</tr>
										@endforeach
										</tbody>
									</table>
								</div>
							@endforeach
						</div>
					</div>
				</div>
			</div>
		</div>
	</div><!--/container-fluid-->

@endsection



@endif

@section('header-bottom')
	@parent
	@if (Route::currentRouteNamed(currentRouteName('create'))||Route::currentRouteNamed(currentRouteName('edit')) )
		{{ HTML::script(asset('js/usuarios.js')) }}

		<script type="text/javascript">
            //iniciamos tooltips
            $(document).ready(function(){
                $('[data-toggle]').tooltip();

                //función para clic en el listado
                $(".list-group-item").click(function(){
                    $(this).toggleClass('active');
                });

            });
		</script>
		<script>
            var profiles_permissions = {!!$profiles_permissions!!};
            console.info(profiles_permissions);
            var cont_correo = $('#lista_correo tr').length;
		</script>
	@endif


@endsection




{{-- DONT DELETE --}}
@if (Route::currentRouteNamed(currentRouteName('index')))
	@include('layouts.smart.index')
@endif

@if (Route::currentRouteNamed(currentRouteName('create')))
	@include('layouts.smart.create')
@endif

@if (Route::currentRouteNamed(currentRouteName('edit')))
	@include('layouts.smart.edit')
@endif

@if (Route::currentRouteNamed(currentRouteName('show')))
	@include('layouts.smart.show')
@endif

@if (Route::currentRouteNamed(currentRouteName('export')))
	@include('layouts.smart.export')
@endif

