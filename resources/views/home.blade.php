@extends('layouts.dashboard')

@section('title', 'Inicio')

@section('header-bottom2')
<!--CDN chartsJS, esta versión viene con http://momentjs.com/ incluído-->
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.bundle.min.js"></script>
	<script src="{{ asset('js/dataChart.js') }}"></script>
@endsection

@section('content')
<div class="text-center">
<h2>¡Bienvenido!</h2>
<h6>Tenemos algunos elementos que necesitan de tu atención:</h6>
</div>
<div class="container-fluid">
	<div class="row">
		<div class="col-md-8 col-xs-12">

			<div id="accordion" role="tablist">
			  <div class="card">
			    <div class="card-header" role="tab" id="medicamentoCad">
			      <div class="mb-0 d-flex align-items-center">
			        <a class="mb-0 d-flex align-items-center" data-toggle="collapse" href="#medCad" aria-expanded="true" aria-controls="medCad">
			          <i class="material-icons">info</i> El siguiente medicamento está a punto de caducar:
			        </a>
			      </div>
			    </div>

			    <div id="medCad" class="collapse show" role="tabpanel" aria-labelledby="medicamentoCad" data-parent="#accordion">
			      <div class="card-body">
			      	<div class="text-right">
						<a href="#!" class="btn btn-primary">Notificar a Compras y PNC</a>
					</div>
	                <table class="table table-hover table-stripped table-responsive">
	                  <thead class="">
	                    <tr>
	                      <th>#</th>
	                      <th>Número</th>
	                      <th class="text-danger">Fecha vencimiento</th>
	                      <th>Nombre</th>
	                      <th>Cantidad</th>
	                    </tr>
	                  </thead>
	                  <tbody>
	                    <tr>
	                      <th scope="row">1</th>
	                      <td>1A2B3C</td>
	                      <td class="d-flex align-items-center"><i class="material-icons">today</i> 15/02/2017</td>
	                      <td>Edición Limitada de la Bebida de Proteína en Polvo</td>
	                      <td>50</td>
	                    </tr>
	                    <tr>
	                      <th scope="row">2</th>
	                      <td>4C5D6F</td>
	                      <td class="d-flex align-items-center"><i class="material-icons">today</i> 15/02/2017</td>
	                      <td>Ultimate Prostate Formula</td>
	                      <td>39</td>
	                    </tr>
	                    <tr>
	                      <th scope="row">3</th>
	                      <td>123456789</td>
	                      <td class="d-flex align-items-center"><i class="material-icons">today</i> 15/02/2017</td>
	                      <td>Solución de mujer</td>
	                      <td>21</td>
	                    </tr>
	                    <tr>
	                      <th scope="row">4</th>
	                      <td>7G8H9I</td>
	                      <td class="d-flex align-items-center"><i class="material-icons">today</i> 15/02/2017</td>
	                      <td>Complejo de Arándanos</td>
	                      <td>15</td>
	                    </tr>
	                    <tr>
	                      <th scope="row">5</th>
	                      <td>987654321</td>
	                      <td class="d-flex align-items-center"><i class="material-icons">today</i> 15/02/2017</td>
	                      <td>Guaraná Natural N-R-G en tabletas</td>
	                      <td>8</td>
	                    </tr>
	                  </tbody>
	                </table>
			      </div>
			    </div>
			  </div>
			  <div class="card">
			    <div class="card-header" role="tab" id="procesoDes">
			      <div class="mb-0 d-flex align-items-center">
			        <a class="mb-0 d-flex align-items-center" class="collapsed" data-toggle="collapse" href="#proDesv" aria-expanded="false" aria-controls="proDesv">
			          <i class="material-icons">info</i> Se detectó medicamento en proceso de desviación:
			        </a>
			      </div>
			    </div>
			    <div id="proDesv" class="collapse" role="tabpanel" aria-labelledby="procesoDes" data-parent="#accordion">
			      <div class="card-body">
			      	<div class="text-right">
						<a href="#!" class="btn btn-primary">Notificar a Compras y PNC</a>
					</div>
	                <table class="table table-hover table-stripped table-responsive">
	                  <thead class="">
	                    <tr>
	                      <th>#</th>
	                      <th>Número</th>
	                      <th>Nombre</th>
	                      <th>Cantidad</th>
	                    </tr>
	                  </thead>
	                  <tbody>
	                    <tr>
	                      <th scope="row">1</th>
	                      <td>1A2B3C</td>
	                      <td>Edición Limitada de la Bebida de Proteína en Polvo</td>
	                      <td>50</td>
	                    </tr>
	                    <tr>
	                      <th scope="row">2</th>
	                      <td>4C5D6F</td>
	                      <td>Ultimate Prostate Formula</td>
	                      <td>39</td>
	                    </tr>
	                    <tr>
	                      <th scope="row">3</th>
	                      <td>123456789</td>
	                      <td>Solución de mujer</td>
	                      <td>21</td>
	                    </tr>
	                    <tr>
	                      <th scope="row">4</th>
	                      <td>7G8H9I</td>
	                      <td>Complejo de Arándanos</td>
	                      <td>15</td>
	                    </tr>
	                    <tr>
	                      <th scope="row">5</th>
	                      <td>987654321</td>
	                      <td>Guaraná Natural N-R-G en tabletas</td>
	                      <td>8</td>
	                    </tr>
	                  </tbody>
	                </table>
			      </div>
			    </div>
			  </div>
			  <div class="card">
			    <div class="card-header" role="tab" id="ipejal">
			      <div class="mb-0 d-flex align-items-center">
			        <a class="collapsed mb-0 d-flex align-items-center" data-toggle="collapse" href="#proyIpejal" aria-expanded="false" aria-controls="proyIpejal">
			          <i class="material-icons">info</i> El proyecto IPEJAL encontró los siguientes problemas:
			        </a>
			      </div>
			    </div>
			    <div id="proyIpejal" class="collapse" role="tabpanel" aria-labelledby="ipejal" data-parent="#accordion">
			      <div class="card-body">
					<div class="list-group">
					  <a href="#" class="list-group-item list-group-item-action flex-column align-items-start">
					    <div class="d-flex w-100 justify-content-between text-right">
					      <small class="text-muted d-flex align-items-center"><i class="material-icons">today</i> 3 days ago</small>
					    </div>
					    <p class="mb-1">82 Medicamentos en proceso de devolución</p>
					    <small class="text-muted">Donec id elit non mi porta.</small>
					  </a>
					  <a href="#" class="list-group-item list-group-item-action flex-column align-items-start">
					    <div class="d-flex w-100 justify-content-between text-right">
					      <small class="text-muted d-flex align-items-center"><i class="material-icons">today</i> 3 days ago</small>
					    </div>
					    <p class="mb-1">Levantó un ticket con el asuto: No pude registrar el medicamento</p>
					    <small class="text-muted">Donec id elit non mi porta.</small>
					  </a>
					</div>
			      </div>
			    </div>
			  </div>
			</div>

		</div><!--/col collapsibles-->
		<div class="col-md-4 col-xs-12">
			<div class="card text-center">
			  <div class="card-header">
			    <ul class="nav nav-pills card-header-pills">
			      <li class="nav-item">
			        <a class="nav-link active" href="#">IPEJAL</a>
			      </li>
			      <li class="nav-item">
			        <a class="nav-link" href="#">Querétaro</a>
			      </li>
			      <li class="nav-item">
			        <a class="nav-link" href="#">Proyecto</a>
			      </li>
			    </ul>
			  </div>
			  <div class="card-body">
			    <h4 class="card-title">Special title treatment</h4>
			    <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
			    <a href="#" class="btn btn-primary">Go somewhere</a>
			  </div>
			</div>
		</div><!--/col aditional info-->
	</div>
</div>
@endsection
