@extends('layouts.dashboard')

@section('title', 'Crear')

@section('header-top')
@endsection

@section('header-bottom')
<!-- Import js picker data and time -->
	{{-- <script type="text/javascript" src="{{ asset('js/picker.date.js') }}"></script> --}}
	{{-- <script type="text/javascript" src="{{ asset('js/picker.time.js') }}"></script> --}}

	{{-- <script src="{{ asset('js/empleados.js') }}"></script> --}}
@endsection

@section('content')
	<div class="col-12">
	    <div class="text-right">
	        <button class="btn btn-primary" name="action">Guardar</button>
	        <button class="btn btn-info" name="action"><i class="material-icons align-middle">print</i> Imprimir</button>
	        <button class="btn btn-default text-primary">Cancelar</button>
	    </div>
	</div><!--/row buttons-->

	<div class="row">
		<div class="col s6 m3">
			<label>Razón social:</label>
			<h6 class="grey-text text-darken-1">PHARMA CIENTIFIC. S.A. DE C.V.</h6>
		</div>
		<div class="col s6 m3">
			<label>Nombre:</label>
			<h6 class="grey-text text-darken-1">PHARMA CIENTIFIC. S.A. DE C.V.</h6>
		</div>
		<div class="col s6 m3">
			<label>RFC:</label>
			<h5 class="grey-text text-darken-1">PCI050502BG6</h5>
		</div>
		<div class="col s6 m3">
			<label>Estatus:</label>
			<h5 class="green-text"><i class="material-icons">done</i>Vigente</h5>
			<p class="facturas-line"><span><i class="tiny material-icons">today</i> 2017-11-25</span><span><i
					class="tiny material-icons">query_builder</i> 09:18:38</span></p>
		</div>
	</div><!--first Section-->

	<div class="divider"></div>

	<div class="row">
	    <div class="col s12 m4">
	        <h5>Datos de proveedor</h5>
	        <div class="col s12">
	            <label>*Tipo de proveedor:</label>
	            <select>
	                <option value="" disabled selected>Selecciona...</option>
	                <option value="1">Insumos</option>
	                <option value="2">Servicios</option>
	            </select>
	        </div>
	        <div class="col s12">
	            <label>Tipo de insumos:</label>
	            <select>
	                <option value="" disabled selected>Selecciona...</option>
	                <option value="1">Equipo Médico</option>
	                <option value="2">Instrumental</option>
	                <option value="3">Material de curación</option>
	                <option value="4">Medicamento</option>
	                <option value="5">Otro</option>
	            </select>
	        </div>
	        <div class="col s12">
	            <label>Tipo de pagos:</label>
	            <select>
	                <option value="" disabled selected>Selecciona...</option>
	                <option value="1">Crédito</option>
	                <option value="2">Contado</option>
	                <option value="3">50/50</option>
	                <option value="4">Otro</option>
	            </select>
	        </div>
	        <div class="col s12">
	            <label>Prioridad:</label>
	            <select>
	                <option value="" disabled selected>Selecciona...</option>
	                <option value="1">Alta</option>
	                <option value="2">Media</option>
	                <option value="3">Baja</option>
	            </select>
	        </div>
	        <div class="input-field col s6">
	            <label for="monto_credito">Monto crédito</label>
	            <input id="monto_credito" type="number" class="validate active">
	        </div>
	        <div class="input-field col s6">
	            <label for="dias_credito">Días de crédito</label>
	            <input id="dias_credito" type="number" class="validate active">
	        </div>
	    </div><!--/col s12 m4-->

	    <div class="col s12 m4">
	        <h5>Datos de contacto</h5>
	        <div class="input-field col s12">
	            <label for="phone1">Teléfono:</label>
	            <input id="phone1" type="tel" class="validate active">
	        </div>
	        <div class="input-field col s12">
	            <label for="phone2">Teléfono Alternativo:</label>
	            <input id="phone2" type="tel" class="validate active">
	        </div>
	        <h5>Órdenes de compra</h5>
	        <div class="input-field col s12">
	            <label for="email1">E-mail:</label>
	            <input id="email1" type="email" class="validate active">
	        </div>
	        <div class="input-field col s12">
	            <label for="email2">E-mail 2:</label>
	            <input id="email2" type="email" class="validate active">
	        </div>
	        <div class="input-field col s12">
	            <label for="email3">E-mail 3:</label>
	            <input id="email3" type="email" class="validate active">
	        </div>
	    </div><!--/col-s12 m4-->

	    <div class="col s12 m4">
	        <h5>Información de entrega</h5>
	        <div class="col s12">
	            <label>Entrega:</label>
	            <form action="#">
	                <p>
	                    <input name="group1" type="radio" id="sucursal"/>
	                    <label for="sucursal">Sucursal</label>
	                </p>
	                <p>
	                    <input name="group1" type="radio" id="paqueteria"/>
	                    <label for="paqueteria">Paquetería</label>
	                </p>
	                <p>
	                    <input name="group1" type="radio" id="recoleccion"/>
	                    <label for="recoleccion">Recolección</label>
	                </p>
	            </form>
	            <div class="col s12">
	                <label>Empresa:</label>
	                <select multiple>
	                    <option value="" disabled selected>Selecciona...</option>
	                    <option value="1">D.F.</option>
	                    <option value="2">Jalisco</option>
	                    <option value="5">Otro</option>
	                </select>
	            </div>
	            <div class="col s12">
	                <label>Paga:</label>
	                <form action="#">
	                    <p>
	                        <input name="group2" type="radio" id="proveedor"/>
	                        <label for="proveedor">Proveedor</label>
	                    </p>
	                    <p>
	                        <input name="group2" type="radio" id="nosotros"/>
	                        <label for="nosotros">Nosotros</label>
	                    </p>
	                </form>
	            </div>
	            <div class="input-field col s12">
	                <label for="colectiva">Tiempo de entrega:</label>
	                <input id="colectiva" type="number" class="validate active col s9">
	                <p>Días</p>
	            </div>
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
	                                <select>
	                                    <option value="" disabled selected>Selecciona...</option>
	                                    <option value="1">Crédito</option>
	                                    <option value="2">Cobranza</option>
	                                    <option value="3">Cotizaciones</option>
	                                    <option value="4">Venta gobiernos</option>
	                                    <option value="5">Venta privados</option>
	                                    <option value="6">Otro</option>
	                                </select>
	                            </div>
	                            <div class="input-field col s12 m6">
	                                <label for="name">Nombre Completo:</label>
	                                <input id="name" type="text" class="validate">
	                            </div>
	                            <div class="input-field col s12 m8">
	                                <input id="tel_office" type="tel" class="validate">
	                                <label for="tel_office">Teléfono Oficina:</label>
	                            </div>
	                            <div class="input-field col s12 m4">
	                                <input id="ext" type="number" class="validate">
	                                <label for="ext">Ext</label>
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
	                                    class="material-icons">delete</i></a></td>
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
	            <h5>Direcciones adicionales</h5>
	            <div class="card">
	                <div class="card-image teal lighten-5">
	                    <form>
	                        <div class="row">
	                            <div class="col s12">
	                              <form action="#">
	                              <label for="dom1">Tipos de domicilio</label>
	                                <p>
	                                  <input name="type_dom" type="radio" id="dom1" />
	                                  <label for="dom1">Domicilio fiscal</label>
	                                </p>
	                                <p>
	                                  <input name="type_dom" type="radio" id="dom2" />
	                                  <label for="dom2">Principal</label>
	                                </p>
	                                <p>
	                                  <input name="type_dom" type="radio" id="dom3" />
	                                  <label for="dom3">Otro</label>
	                                </p>
	                              </form>
	                            </div>
	                            <div class="input-field col s12">
	                                <label for="name">Calle:</label>
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
	                                    <label for="cp">C.P.:</label>
	                                    <input id="cp" type="number" class="validate">
	                                </div>
	                            </div>
	                            <div class="col s12 m6">
	                                <label>País:</label>
	                                <select>
	                                    <option value="" disabled selected>Selecciona...</option>
	                                    <option value="1">México</option>
	                                </select>
	                            </div>
	                            <div class="col s12 m6">
	                                <label>Estado:</label>
	                                <select>
	                                    <option value="" disabled selected>Selecciona...</option>
	                                    <option value="1">Jalisco</option>
	                                </select>
	                            </div>
	                            <div class="col s12 m6">
	                                <label>Municipio:</label>
	                                <select>
	                                    <option value="" disabled selected>Selecciona...</option>
	                                    <option value="1">Guadalajara</option>
	                                </select>
	                            </div>
	                            <div class="col s12 m6">
	                                <label>Colonia:</label>
	                                <select>
	                                    <option value="" disabled selected>Selecciona...</option>
	                                    <option value="1">Oblatos</option>
	                                </select>
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
	                            <th>Principal</th>
	                            <th>Calle y número</th>
	                            <th>País</th>
	                        </tr>
	                        </thead>
	                        <tbody>
	                        <tr>
	                            <td><i class="material-icons">add</i></td>
	                            <td>Calle </td>
	                            <td><a class="eliminar btn btn_tables waves-effect btn-flat" href="#"><i
	                                    class="material-icons">delete</i></a></td>
	                        </tr>
	                        <tr>
	                            <td>Curación</td>
	                            <td><a class="eliminar btn btn_tables waves-effect btn-flat" href="#"><i
	                                    class="material-icons">delete</i></a></td>
	                        </tr>
	                        <tr>
	                            <td>Controlados</td>
	                            <td><a class="eliminar btn btn_tables waves-effect btn-flat" href="#"><i
	                                    class="material-icons">delete</i></a></td>
	                        </tr>
	                        </tbody>
	                    </table>
	                </div><!--/here ends de down section-->
	            </div>
	        </div><!--/Here ends the overall container-->
	    </div>
    </div>


@endsection
