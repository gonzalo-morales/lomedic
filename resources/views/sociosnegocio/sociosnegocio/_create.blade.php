@extends('layouts.dashboard')

@section('title', 'Crear')

@section('header-top')
@endsection

@section('header-bottom')
<!-- Import js picker data and time -->
	{{-- <script type="text/javascript" src="{{ asset('js/picker.date.js') }}"></script> --}}
	{{-- <script type="text/javascript" src="{{ asset('js/picker.time.js') }}"></script> --}}
	<script src="{{ asset('js/socios/proveedores1.js') }}"></script>
@endsection

@section('content')
	{{-- <form action="{{ companyRoute("store", ['company'=> $company]) }}" method="post" class="col s12" enctype="multipart/form-data"> --}}
	{{-- <form action="{{ companyRoute("Socios\ProveedoresController@create") }}" method="post" class="col s12" enctype="multipart/form-data"> --}}
	<form action="{{ companyRoute("store",['company' => $company]) }}" method="post" class="col s12" enctype="multipart/form-data">
		{{ csrf_field() }}
		{{ method_field('POST') }}
	<div class="row">
	    <div class="right">
	        <button class="btn orange waves-effect waves-light" type="submit" name="action" id="guardarProveedor">Guardar {{ trans_choice('messages.'.$entity, 0) }}</button>
	        <button class="btn waves-effect waves-light" name="action">Imprimir</button>
	        <button class="waves-effect waves-teal btn-flat teal-text">Cancelar</button>
	    </div>
	</div><!--/row buttons-->


	<div class="row">
		<div class="input-field col s6 m4">
			<label for="razon_social">*Razón social:</label>
			<input id="razon_social" name="razon_social" type="text" class="validate active">
			@if ($errors->has('razon_social'))
			<span class="help-block">
				<strong>{{ $errors->first('razon_social') }}</strong>
			</span>
			@endif
		</div>
		<div class="input-field col s6 m4">
			<label for="rfc">*RFC:</label>
			<input id="rfc" name="rfc" type="text" class="validate active">
			{{ $errors->has('rfc') ? HTML::tag('span', $errors->first('rfc'), ['class'=>'help-block deep-orange-text']) : '' }}
		</div>
		<div class="input-field col s6 m4">
			<label for="nombre_corto">Nombre corto:</label>
			<input id="nombre_corto" name="nombre_corto" type="text" class="validate active">
			{{ $errors->has('nombre_corto') ? HTML::tag('span', $errors->first('nombre_corto'), ['class'=>'help-block deep-orange-text']) : '' }}
		</div>
		<div class="input-field col s2">
			<p>
				<input type="hidden" name="estatus" value="0">
				<input type="checkbox" id="estatus"  name="estatus" checked="true"/>
				<label for="estatus">Estatus</label>
			</p>

			@if ($errors->has('estatus'))
			<span class="help-block deep-orange-text">
				<strong>{{ $errors->first('estatus') }}</strong>
			</span>
			@endif
		</div>
		{{-- <div class="col s12 m6">
			<label>*Ramo(s):</label>
			<select multiple id="ramo" name="ramo[]">
				<option value="" disabled selected>Selecciona...</option>
				<option value="1">Equipo Médico</option>
				<option value="2">Instrumental</option>
				<option value="3">Material de curación</option>
				<option value="4">Medicamento</option>
				<option value="5">Otro</option>
			</select>
		</div>
		<div class="col s12 m6">
		<label>Aviso de Funcionamiento</label>
			<div class="file-field input-field">
			  <div class="btn">
				<i class="material-icons">attach_file</i>
				<input type="file" id="avisoFuncionamiento" name="avisoFuncionamiento">
			  </div>
			  <div class="file-path-wrapper">
				<input class="file-path validate" type="text">
			  </div>
			</div>
			@if ($errors->has('avisoFuncionamiento'))
			<span class="help-block">
				<strong>{{ $errors->first('avisoFuncionamiento') }}</strong>
			</span>
			@endif
		</div> --}}

	</div>

	{{-- </form> --}}

	<div class="divider"></div>

	<div class="row">
	    <div class="col s12 m4">
	        <h5>Datos de proveedor</h5>
	        <div class="col s12 m6">
	            <label data-error="Campo obligatorio">*Tipo de socio:</label>
	            <select multiple id="tipo_socio" name="tipo_socio">
	                <option value="" disabled selected>Selecciona...</option>
					<option value="1">Cliente</option>
		            <option value="2">Proveedor</option>
		            <option value="5">Oportunidad de venta</option>
	            </select>
				{{ $errors->has('tipo_socio') ? HTML::tag('span', $errors->first('tipo_socio'), ['class'=>'help-block deep-orange-text']) : '' }}
	        </div>
	        <div class="col s12 m6">
				<label>*Ramo(s):</label>
	            <select multiple id="fk_id_ramo" name="fk_id_ramo">
	                <option value="" disabled selected>Selecciona...</option>
	                <option value="1">Equipo Médico</option>
	                <option value="2">Instrumental</option>
	                <option value="3">Material de curación</option>
	                <option value="4">Medicamento</option>
	                <option value="5">Otro</option>
	            </select>
				{{ $errors->has('fk_id_ramo') ? HTML::tag('span', $errors->first('fk_id_ramo'), ['class'=>'help-block deep-orange-text']) : '' }}
	        </div>
			<div class="input-field col s12 m6">
	            <input id="telefono" name="telefono" type="tel" class="validate">
	            <label for="telefono">Teléfono:</label>
				{{ $errors->has('telefono') ? HTML::tag('span', $errors->first('telefono'), ['class'=>'help-block deep-orange-text']) : '' }}
	        </div>
			<div class="input-field col s12 m6">
	            <input id="sitio_web" name="sitio_web" type="text" class="validate">
	            <label for="sitio_web">Sitio web:</label>
				{{ $errors->has('sitio_web') ? HTML::tag('span', $errors->first('sitio_web'), ['class'=>'help-block deep-orange-text']) : '' }}
	        </div>
	        <div class="col s12 m6">
	        <label>Licencia Sanitaria</label>
	            <div class="file-field input-field">
	              <div class="btn">
	                <i class="material-icons">attach_file</i>
	                <input type="file" id="lic_sanitaria" name="lic_sanitaria" multiple>
	              </div>
	              <div class="file-path-wrapper">
	                <input class="file-path validate" type="text">
	              </div>
	            </div>
				{{ $errors->has('lic_sanitaria') ? HTML::tag('span', $errors->first('lic_sanitaria'), ['class'=>'help-block deep-orange-text']) : '' }}
	        </div>
			<div class="col s12 m6">
	        <label>Aviso de Funcionamiento</label>
	            <div class="file-field input-field">
	              <div class="btn">
	                <i class="material-icons">attach_file</i>
	                <input type="file" id="aviso_funcionamiento" name="aviso_funcionamiento" multiple>
	              </div>
	              <div class="file-path-wrapper">
	                <input class="file-path validate" type="text">
	              </div>
	            </div>
				{{ $errors->has('aviso_funcionamiento') ? HTML::tag('span', $errors->first('aviso_funcionamiento'), ['class'=>'help-block deep-orange-text']) : '' }}
	        </div>
			<div class="col s12 m6">
	        <label>Aviso de Responsable Sanitario</label>
	            <div class="file-field input-field">
	              <div class="btn">
	                <i class="material-icons">attach_file</i>
	                <input type="file" id="aviso_responsable_sanitario" name="aviso_responsable_sanitario" multiple>
	              </div>
	              <div class="file-path-wrapper">
	                <input class="file-path validate" type="text">
	              </div>
	            </div>
				{{ $errors->has('aviso_responsable_sanitario') ? HTML::tag('span', $errors->first('aviso_responsable_sanitario'), ['class'=>'help-block deep-orange-text']) : '' }}
	        </div>
		</div><!--/col s12 m4-->

		<div class="col s12 m4">
			<h5>Condiciones de pago</h5>
				<label data-error="Campo obligatorio">*Condiciones:</label>
					<p>
						<input name="tipo_pago" type="radio" id="credito"/>
						<label for="credito">Crédito</label>
					</p>
					<p>
						<input name="tipo_pago" type="radio" id="contado"/>
						<label for="contado">Contado</label>
					</p>
				{{ $errors->has('tipo_pago') ? HTML::tag('span', $errors->first('tipo_pago'), ['class'=>'help-block deep-orange-text']) : '' }}
			<div class="input-field col s6">
				<label for="monto_credito">Monto de crédito:</label>
				<input id="monto_credito" name="monto_credito" type="number" class="validate">
				{{ $errors->has('monto_credito') ? HTML::tag('span', $errors->first('monto_credito'), ['class'=>'help-block deep-orange-text']) : '' }}
			</div>
			<div class="input-field col s6">
				<label for="dias_credito">Días de crédito:</label>
				<input id="dias_credito" name="dias_credito" type="number" class="validate">
				{{ $errors->has('dias_credito') ? HTML::tag('span', $errors->first('dias_credito'), ['class'=>'help-block deep-orange-text']) : '' }}
			</div>
			<div class="col s12 m6">
		        <label>Banco:</label>
		        <select id="fk_id_banco" name="fk_id_banco">
		            <option value="" disabled selected>Selecciona...</option>
					@foreach ($bancos as $banco)
			        <option value="{{$banco->id_banco}}" >{{$banco->banco}}</option>
			        @endforeach
			    </select>
				    @if ($errors->has('sucursal'))
				        <span class="help-block">
				            <strong>{{ $errors->first('sucursal') }}</strong>
				        </span>
				    @endif
				{{ $errors->has('fk_id_banco') ? HTML::tag('span', $errors->first('fk_id_banco'), ['class'=>'help-block deep-orange-text']) : '' }}
	        </div>
			<div class="input-field col s12 m6">
	            <label for="cuenta_bancaria">Cuenta bancaria:</label>
	            <input id="cuenta_bancaria" name="cuenta_bancaria" type="number" class="validate">
				{{ $errors->has('cuenta_bancaria') ? HTML::tag('span', $errors->first('cuenta_bancaria'), ['class'=>'help-block deep-orange-text']) : '' }}
	        </div>
	    </div><!--/col-s12 m4-->

		<div class="col s12 m4">
			<h5>Información de entrega</h5>
			<div class="col s12">
				<label data-error="Campo obligatorio">*Tipo de Entrega:</label>
				<form action="#">
					<p>
						<input name="tipoEntrega" type="radio" id="tsucursal"/>
						<label for="tsucursal">Sucursal</label>
					</p>
					<p>
						<input name="tipoEntrega" type="radio" id="tpaqueteria"/>
						<label for="tpaqueteria">Paquetería</label>
					</p>
					<p>
						<input name="tipoEntrega" type="radio" id="trecoleccion"/>
						<label for="trecoleccion">Recolección</label>
					</p>
				</form>
				<div class="input-field col s12">
					<label for="colectiva">Monto mínimo de facturación:</label>
					<input id="colectiva" type="number" class="validate">
				</div>
			<div class="col s12">
				<label data-error="Campo obligatorio">*Correos para envío de orden de compra:</label>
				<div id="emails" class="chips chips-initial"></div>
			</div>
			</div><!--/col-s12-->
		</div><!--/col-s12 m4-->
	</div><!--/row-->

	<div class="row">
		<div class="col s12 m6">
			<h5>Contactos</h5>
			<div class="card">
				<div class="card-image teal lighten-5">
					<form>
						<div class="row">
							<div class="col s12 m6">
								<label>Tipo de contacto:</label>
								<select id="tipoContacto" name="tipoContacto">
									<option value="" disabled selected>Selecciona...</option>
									<option value="1">Crédito</option>
									<option value="2">Cobranza</option>
									<option value="3">Cotizaciones</option>
									<option value="4">Venta gobiernos</option>
									<option value="5">Venta privados</option>
									<option value="6">Otro</option>
								</select>
							</div>
							<div class="input-field col s12 m12">
								<label for="nombreContacto">Nombre:</label>
								<input id="nombreContacto" name="nombreContacto" type="text" class="validate">
							</div>
							<div class="input-field col s12 m6">
								<input id="puestoContacto" name="puestoContacto" type="text" class="validate">
								<label for="puestoContacto">Puesto:</label>
							</div>
							<div class="input-field col s12 m6">
								<input id="celContacto" name="celContacto" type="tel" class="validate">
								<label for="celContacto">Teléfono celular:</label>
							</div>
							<div class="input-field col s12 m8">
								<input id="telContacto" name="telContacto" type="tel" class="validate">
								<label for="telContacto">Teléfono:</label>
							</div>
							<div class="input-field col s12 m4">
								<input id="extContacto" name="extContacto" type="number" class="validate">
								<label for="extContacto">Ext</label>
							</div>
							<div class="col s12">
								<label>Correo(s) electrónico(s):</label>
								<div id="correosContacto" name="correosContacto" class="chips chips-initial"></div>
							</div>
						</div>
						<button class="btn-floating btn-large orange halfway-fab waves-effect waves-light tooltipped"
								data-position="bottom" data-delay="50" data-tooltip="Agregar" type="submit"><i
								class="material-icons">add</i></button>
					</form><!--/Here ends de form-->
					<div class="divider"></div>
				</div><!--/Here ends the up section-->
				<div class="card-content">
					<table class="responsive-table highlight">
						<thead>
						<tr>
							<th>Tipo de contacto</th>
							<th>Nombre</th>
							<th>Teléfono oficina + Ext</th>
							<th>Acción</th>
						</tr>
						</thead>
						<tbody>
						<tr>
							<td>Venta gobiernos</td>
							<td>Ana Lilia Gonzáles Pérez</td>
							<td>3312345678 +524</td>
							<td><a class="eliminar btn btn_tables waves-effect btn-flat" href="#"><i
									class="material-icons">delete</i></a>
								<a class="eliminar btn btn_tables waves-effect btn-flat" href="#"><i
									class="material-icons">edit</i></a></td>
						</tr>
						<tr>
							<td>Venta privados</td>
							<td>Guillermo Miguel Hernández Domínguez</td>
							<td>3312345678 +624</td>
							<td><a class="eliminar btn btn_tables waves-effect btn-flat" href="#"><i
									class="material-icons">delete</i></a></td>
						</tr>
						</tbody>
					</table>
				</div><!--/here ends de down section-->
			</div>
		</div><!--/Here ends the overall container-->
		<div class="col s12 m6">
			<h5>Direcciones</h5>
			<div class="card">
				<div class="card-image teal lighten-5">
					<form>
						<div class="row">
							<div class="col s12">
							  <form action="#">
								<p class="col s4">
								  <input name="type_dom" type="radio" id="dom1" />
								  <label for="dom1">Domicilio fiscal</label>
								</p>
								<p class="col s4">
								  <input name="type_dom" type="radio" id="dom2" />
								  <label for="dom2">Domicilio de entrega</label>
								</p>
								<p class="col s4">
								  <input name="type_dom" type="radio" id="dom3" />
								  <label for="dom3">Otro</label>
								</p>
							  </form>
							</div>
							<div class="input-field col s12">
								<label for="name" data-error="Campo obligatorio">*Calle:</label>
								<input id="name" type="text" class="validate">
							</div>
							<div class="col s12 m6">
								<div class="col s4">
									<label for="num_ext">Número Ext.:</label>
									<input id="num_ext" type="number" class="validate">
								</div>
								<div class="col s4">
									<label for="num_int">Interior:</label>
									<input id="num_int" type="number" class="validate">
								</div>
								<div class="col s4">
									<label for="cp" data-error="Campo obligatorio">*C.P.:</label>
									<input id="cp" type="number" class="validate">
								</div>
							</div>
							<div class="col s12 m6">
								<label data-error="Campo obligatorio">*País:</label>
								<select>
									<option value="" disabled selected>Selecciona...</option>
									<option value="1">México</option>
								</select>
							</div>
							<div class="col s12 m6">
								<label data-error="Campo obligatorio">*Estado:</label>
								<select>
									<option value="" disabled selected>Selecciona...</option>
									<option value="1">Jalisco</option>
								</select>
							</div>
							<div class="col s12 m6">
								<label data-error="Campo obligatorio">*Municipio:</label>
								<select>
									<option value="" disabled selected>Selecciona...</option>
									<option value="1">Guadalajara</option>
								</select>
							</div>
							<div class="col s12">
								<label for="autocomplete-input">*Colonia:</label>
								<input type="text" id="autocomplete-input" class="autocomplete">
							</div>
						</div><!--/row-->
						<button class="btn-floating btn-large orange halfway-fab waves-effect waves-light tooltipped"
								data-position="bottom" data-delay="50" data-tooltip="Agregar" type="submit"><i
								class="material-icons">add</i></button>
					</form><!--/Here ends de form-->
					<div class="divider"></div>
				</div><!--/Here ends the up section-->
				<div class="card-content">
					<table class="responsive-table highlight">
						<thead>
						<tr>
							<th>Tipo</th>
							<th>Calle y número</th>
							<th>Acciones</th>
						</tr>
						</thead>
						<tbody>
						<tr>
							<td>Domicilio fiscal</td>
							<td>Calle Chicle 197</td>
							<td><a class="eliminar btn btn_tables waves-effect btn-flat"><i class="material-icons">edit</i></a>
							<a class="eliminar btn btn_tables waves-effect btn-flat"><i class="material-icons">delete</i></a></td>
						</tr>
						<tr>
							<td>Domicilio de entrega</td>
							<td>Misión San Patricio 128</td>
							<td><a class="eliminar btn btn_tables waves-effect btn-flat" href="#"><i class="material-icons">edit</i></a>
							<a class="eliminar btn btn_tables waves-effect btn-flat" href="#"><i class="material-icons">delete</i></a></td>
						</tr>
						<tr>
							<td>Otro</td>
							<td>Av Piotr Chaikovski 568</td>
							<td><a class="eliminar btn btn_tables waves-effect btn-flat" href="#"><i class="material-icons">edit</i></a>
							<a class="eliminar btn btn_tables waves-effect btn-flat" href="#"><i class="material-icons">delete</i></a></td>
						</tr>
						</tbody>
					</table>
				</div><!--/here ends de down section-->
			</div>
		</div><!--/Here ends the overall container-->
	</div>
	</form>





@endsection
