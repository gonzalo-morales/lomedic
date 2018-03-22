@extends(smart())
@section('content-width')

@section('form-content')
	{{ Form::setModel($data) }}
	@crear
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-8 col-sm-12 mb-3 card z-depth-1-half">
					<div class="text-center card-header row">
                		INFORMACION DEL USUARIO
                	</div>
					<div class="row mt-3">
    					<div class="col-md-6 form-group">
    						{{Form::cText('* Nombre corto','nombre_corto',['placeholder'=>'Ejemplo: Jose Lopez'])}}
    					</div>
    					<div class="col-md-6 form-group">
    						{{Form::cText('* Usuario','usuario',['placeholder'=>'Ejemplo: jose.lopez'])}}
    					</div>
    					<div class="col-md-6 cform-group">
    						{{ Form::cSelect('Empleado','fk_id_empleado', $empleados ?? [],['style' =>'width:100%;']) }}
    					</div>
    					<div class="col-md-6 cform-group">
    						{{ Form::cSelect('* Empresa por defecto','fk_id_empresa', $emresas ?? []) }}
    					</div>
    					<hr>
    					<div class="col-md-6 form-group">
    						{{Form::cPassword('* Contraseña','password')}}
    					</div>
    					<div class="col-md-6 form-group">
    						{{Form::cPassword('* Confirmar Contraseña','password')}}
    					</div>
    					<div  class="text-center col-md-12">
                    		<div class="alert alert-warning" role="alert">
                                Recuerda que al no estar <b>activo</b>, este <b>dato</b> no se mostrara en los modulos que se requieran.
                            </div>
                            {{ Form::cCheckboxBtn('Estatus','Activo','activo', $data['activo'] ?? null, 'Inactivo') }}
                    	</div>
    					<div class="col-sm-12 mb-3 card z-depth-1-half">
                        	<div class="card-header row">
                        		<div class="col-md-6 form-group">
        							{{Form::cSelect('* Empresa','empresa_correo',$empresas ?? [],['placeholder' => 'Seleccionar una empresa...'])}}
        						</div>
        						<div class="col-md-6 form-group">
        							{{Form::cText('* Correo','correo',['placeholder'=>'jose.lopez@mail.com','type'=>'email'])}}
        						</div>
        						<div class="form-group col-md-12 my-3">
                        			<div class="sep sepBtn">
                        				{{Form::button('<i class="material-icons">add</i>',['class'=>'btn btn-primary btn-large','onclick'=>'agregarCorreo()','style'=>'width: 4em; height:4em; border-radius:50%;','data-delay'=>'50','data-toggle'=>'Agregar','title'=>'Agregar','type'=>'button'])}}
                        			</div>
                        		</div>
                        	</div>
                        	<div class="card-body row" style="max-height: 250px; overflow: auto;">
                        		<table class="table table-hover table-responsive-sm">
        							<thead>
        								<tr>
        									<th>Empresa</th>
        									<th>Correo</th>
        								</tr>
        							</thead>
        							<tbody id="lista_correo"></tbody>
        						</table>
                            </div>
        				</div>
        			</div>
				</div>
				<div class="col-md-4 mb-3 card z-depth-1-half" style="max-height: 800px;">
                	<div class="text-center card-header row">
                		PERFILES
                	</div>
                	<div class="card-body btn-group-toggle" data-toggle="buttons" style="overflow: auto;">
                		@foreach( $profiles as $profile )
                			{{ Form::cCheckboxBtn('','<i class="material-icons" style="font-size:14px;">check</i> '.$profile->nombre_perfil,'perfil[]', $profile->id_perfil ?? null, $profile->nombre_perfil) }}
                			
                			
						@endforeach
                	</div>
                </div>
				<div class="col-md-12 mb-3 card z-depth-1-half">
					<h4 class="card-header row">Permisos por empresa</h4>
					<div class="card-body row text-center">
						<p class="col-md-12">Aquí se muestran las empresas que <b>cuentan con sus respectivos módulos y permisos</b></p>
						<div class="col-md-12">
    						<ul class="nav nav-tabs" role="tablist">
    							@php($i = 0)
    							@foreach($companys as $empresa)
    									@php($i++)
    									<li class="nav-item">
    										<a class="nav-link{{$i == 1 ? ' active' : ''}}" data-toggle="tab" href="#empresa_{{$empresa->id_empresa}}" role="tab">{{$empresa->nombre_comercial}}</a>
    									</li>
    							@endforeach
    
    						</ul>
    						<div id="modulos" class="tab-content">
    							@php($i = 0)
    							@foreach($companys as $data_company)
    								@php($i++)
    								<div class="tab-pane {{$i == 1 ? ' active' : ''}}" id="empresa_{{$data_company->id_empresa}}" role="tabpanel">
    									<div class="row">
        									<div class="col-md-8 mt-2 card z-depth-1-half">
        										<div class="text-center card-header row">
                                            		PERMISOS
                                            	</div>
                                            	<div class="card-body btn-group-toggle" data-toggle="buttons">
                									<table class="table table-hover table-responsive-sm border-0">
                										<thead>
                										<tr>
                											<th class="border-top-0">Modulo</th>
                											<th class="border-top-0">Acciones</th>
                											<th class="border-top-0"></th>
                											<th class="border-top-0"></th>
                											<th class="border-top-0"></th>
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
                														{{ Form::cCheckboxBtn('','<i class="material-icons" style="font-size:14px;">check</i> '.$row_accion->nombre,'accion_modulo[]', $row_accion->id_modulo_accion ?? null, $row_accion->nombre) }}
                													</td>
                												@endforeach
                											</tr>
                										@endforeach
                										</tbody>
                									</table>
            									</div>
            								</div>
            								<div class="col-md-4 mt-2 card z-depth-1-half">
                                            	<div class="text-center card-header row">
                                            		SUCURSALES
                                            	</div>
                                            	<div class="card-body btn-group-toggle" data-toggle="buttons">
                                            		@foreach($data_company->sucursales->unique() as $row)
                                            			{{ Form::cCheckboxBtn('','<i class="material-icons" style="font-size:14px;">check</i> '.$row->sucursal,'sucursal[]', $row->id_sucursal ?? null, $row->sucursal) }}
                            						@endforeach
                                            	</div>
                                            </div>
        								</div>
    								</div>
    							@endforeach
    						</div>
    					</div>
					</div>
				</div>
				
				
			</div>
		</div>
	@endif		
				
				
	@ver
			
		</div><!--/container-fluid-->
	@endif
	
	@ver
    	<div class="container-fluid">
    		<div class="row">
    			<div class="col-md-8 col-sm-12 mb-3">
					<div class="row">
						<div class="col-md-3 col-12">
							<div class="form-group">
								{{Form::label('nombre_corto','Nombre',['for'=>'name'])}}
								{{Form::text('nombre_corto',null,array('id'=>'nombre_corto','class'=>'form-control','placeholder'=>'Ejemplo: Juan'))}}
							</div>
						</div>
						<div class="col-md-3 col-12">
							<div class="form-group">
								{{Form::label('usuario','Usuario',['for'=>'usuarios'])}}
								{{Form::text('usuario','',array('id'=>'usuario','class'=>'form-control','placeholder'=>'Ejemplo: Juan'))}}
							</div>
						</div>
						<div class="col-md-6 col-12">
							{{ Form::cSelect('Empleado','fk_id_empleado', $empleados ?? [],['style' =>'width:100%;']) }}
						</div>
					</div><!--/row forms-->
					<div class="row">
						<div class="col-md-6 col-sm-6">
							<div class="form-group">
								{{Form::label('fk_id_empresa','Empresa',['for'=>'fk_id_empresa'])}}
								{{ Form::select('fk_id_empresa',$companies->pluck('nombre_comercial','id_empresa'),null,['id'=>'fk_id_empresa','class'=>'form-control','placeholder' => 'Seleccionar una empresa...',])}}
							</div>
						</div>
						{{--  {{dd($sucursales)}}  --}}
						<div class="col-md-6 col-sm-6">
							<div class="form-group">
								<label>Sucursal(es):</label><br>
								@foreach($sucursales as $sucursal)
									<span class="badge badge-secondary rounded-0 badge_sucursales">{{$sucursal->sucursal}}</span>
								@endforeach
							</div>
						</div>
					</div><!--/row forms-->
					<hr>
					<h5>Correo Empresarial</h5>
					<fieldset class="card">
						<div class="card-body">
							<table class="table table-hover table-responsive-sm">
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
    			<div class="col-md-4 col-sm-12">
    				<h5>Perfiles</h5>
    				<div id="listProfiles" class="list-group">
						<div class="btn-group-toggle" data-toggle="buttons">
							@foreach( $profiles as $profile )
								<label class="btn btn-info active btn-check">
									<input type="checkbox" name="perfil[]" id="perfil_{{$profile->id_perfil}}" onclick="accionesPerfil(this.id)"/>{{$profile->nombre_perfil}}
								</label>
							@endforeach
						</div>
    				</div>
    			</div>
    		</div>
    		<div class="row mb-3 mt-3">
    			<div class="col-md-12">
    				<div class="card z-depth-1-half">
    					<h4 class="card-header">Empresas</h4>
    					<div class="card-body">
    						<div class="tab-content">
								@foreach($companies as $data_company)
									@if ($data_company->modulos_usuario($data->id_usuario,$data_company->id_empresa)->unique() != '[]')
									<h4>{{$data_company->nombre_comercial}}</h4>
									<table class="table table-hover table-responsive-sm">
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
									@endif
								@endforeach
    						</div>
    					</div>
    				</div>
    			</div>
    		</div>
    	</div><!--/container-fluid-->
	@endif

	@editar
    	<div class="container-fluid">
    		<div class="row">
    			<div class="col-md-8 col-sm-12 mb-3">
					<div class="row">
						<div class="col-md-3 col-sm-6 col-12">
							<div class="form-group">
								{{Form::label('nombre_corto','Nombre',['for'=>'name'])}}
								{{Form::text('nombre_corto',$data->nombre_corto,array('id'=>'nombre_corto','class'=>'form-control','placeholder'=>'Ejemplo: Juan'))}}
							</div>
						</div>
						<div class="col-md-3 col-sm-6 col-12">
							<div class="form-group">
								{{Form::label('usuario','Usuario',['for'=>'usuarios'])}}
								{{Form::text('usuario',$data->usuario,array('id'=>'usuario','class'=>'form-control','placeholder'=>'Ejemplo: Juan'))}}
							</div>
						</div>
						{{--<div class="col-md-3 col-sm-6 col-12">--}}
							{{--<div class="form-group">--}}
								{{--{{Form::label('password','Contraseña',['for'=>'password'])}}--}}
								{{--{{Form::password('password',['class'=>'form-control','placeholder'=>'Contraseño'],$data->password)}}--}}
							{{--</div>--}}
						{{--</div>--}}
						<div class="col-md-6 col-sm-6 col-12">
							{{ Form::cSelect('Empleado','fk_id_empleado', $empleados ?? [],['style' =>'width:100%;']) }}
						</div>
					</div><!--/row forms-->
					<div class="row">
						<div class="col-md-6 col-sm-6">
							<div class="form-group">
								{{Form::label('fk_id_empresa','Empresa',['for'=>'fk_id_empresa'])}}
								{{ Form::select('fk_id_empresa',$companies->pluck('nombre_comercial','id_empresa'),null,[
									'id'=>'fk_id_empresa',
									'class'=>'form-control',
									'placeholder' => 'Seleccionar una empresa...',
									'data-url' => companyAction('HomeController@index').'/administracion.sucursales/api',
									])}}
							</div>
						</div>
						<div class="col-md-6 col-sm-12">
								<div id="loadingsucursales" class="w-100 h-100 text-center text-white align-middle loadingData" style="display: none">
									Cargando Sucursal(es)... <i class="material-icons align-middle loading">cached</i>
								</div>
								{{ Form::cSelect('* Sucursal(es)','fk_id_sucursal[]', $sucursales ?? [],[
									'style' => 'width:100%;',
									'class' => !Route::currentRouteNamed(currentRouteName('show')) ? 'select2 fk_id_sucursales' : ''
								]) }}
								<label>Actual(es):</label>
								@foreach($sucursales_anteriores as $sucursal)
									<span class="badge badge-secondary">{{$sucursal->sucursal}}</span>
								@endforeach
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
								<div class="col-sm-12 text-center">
									<div class="sep">
										<div class="sepBtn">
											{{Form::button('<i class="material-icons">add</i>',['class'=>'btn btn-primary btn-large','onclick'=>'agregarCorreo()','style'=>'width: 4em; height:4em; border-radius:50%;','data-delay'=>'50','data-toggle'=>'Agregar','title'=>'Agregar'])}}
										</div>
									</div>
								</div>
							</div>
							<div class="card-body">
								<table class="table table-hover table-responsive-sm">
									<thead>
									<tr>
										<th>Empresa</th>
										<th>Correo</th>
										<th>Acción</th>
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
						</div>
					</fieldset>
    			</div>
    			<div class="col-md-4 col-sm-12">
    				<h5>Perfiles</h5>
					
    				<div id="listProfiles" class="btn-group-toggle" data-toggle="buttons">
    					@foreach( $profiles as $profile )
    						@if( array_intersect( $perfiles_usuario->pluck('nombre_perfil','id_perfil')->toArray() ,$profile->toArray() ) )
    							<label class="btn btn-info active btn-check" id="perfil_{{$profile->id_perfil}}" onclick="accionesPerfil(this.id)">
    								<input type="checkbox"  name="perfil[]"  id="perfil_check_{{$profile->id_perfil}}" value="{{$profile->id_perfil}}" checked="checked" style="display: none">{{$profile->nombre_perfil}}
								</label>
    						@else
    							<label class="btn btn-info btn-check" id="perfil_{{$profile->id_perfil}}" onclick="accionesPerfil(this.id)">
    								<input type="checkbox" name="perfil[]" id="perfil_check_{{$profile->id_perfil}}" value="{{$profile->id_perfil}}"  style="display: none">{{$profile->nombre_perfil}}
								</label>
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
									@if($data_company->modulos_empresa != '[]')
										<li class="nav-item">
											<a class="nav-link" data-toggle="tab" href="#empresa_{{$data_company->id_empresa}}" role="tab">{{$data_company->nombre_comercial}}</a>
										</li>
									@endif
								@endforeach
							</ul>
							<div id="modulos" class="tab-content">
								@foreach($companies as $data_company)
									<div class="tab-pane " id="empresa_{{$data_company->id_empresa}}" role="tabpanel">
										<table class="table table-hover table-responsive-sm">
											<thead>
											<tr>
												<th class="border-top-0">Modulo</th>
												<th class="border-top-0">Accion</th>
												<th class="border-top-0"></th>
												<th class="border-top-0"></th>
												<th class="border-top-0"></th>				
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
    @endif
@endsection

@section('header-bottom')
	@parent
	@notroute(['index'])
		{{ HTML::script(asset('js/administracion/usuarios.js')) }}
		<script type="text/javascript">
			var api_sucursales = '{{ $sucursales_js ?? '' }}';
			var profiles_permissions = {!!$profiles_permissions!!};
			console.info(profiles_permissions);
            var cont_correo = $('#lista_correo tr').length;
		</script>
	@endif
@endsection