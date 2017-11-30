@extends('layouts.dashboard')

@section('title', 'Inicio')

@section('header-bottom')
    {{ HTML::script(asset('js/amcharts/amcharts.js')) }}
    {{ HTML::script(asset('js/amcharts/pie.js')) }}
    {{ HTML::script(asset('js/amcharts/themes/light.js')) }}

<!--CDN chartsJS, esta versión viene con http://momentjs.com/ incluído-->
<!--	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.bundle.min.js"></script> -->
<!--	<script src="{{ asset('js/dataChart.js') }}"></script> -->
<script src="{{ asset('js/dataChart.js') }}"></script>
@endsection

@section('content')

<div class="text-center">
<h1 class="display-3">¡Bienvenido!</h1>
<h6>Aquí puedes consultar algúnos mensajes urgentes o últimas acitivades que realizaste:</h6>
</div>
<div class="container-fluid">
	<div class="row">
		<div id="metro" class="col-md-8 col-sm-12 mb-3" style="opacity: 0; top:50px;">

			<div class="row mx-2">
				{!! HTML::decode(link_to(companyRoute('administracion.aplicacionesmedicamentos.index'), '<div class="align-self-center d-flex"><i class="material-icons">web</i><span class="badge badge-danger align-self-start">9</span><span class="sr-only align-self-start">unread messages</span></div>',['class'=>'col-6 m-2 metrOpt text-white blue d-flex justify-content-center', 'data-toggle'=>'tooltip','data-placement'=>'top','data-original-title'=>'Aplicaciones medicamentos'])) !!}

				{!! HTML::decode(link_to(companyRoute('proyectos.proyectos.index'),'<div class="align-self-center d-flex"><i class="material-icons">book</i></div>',['class'=>'col m-2 d-flex justify-content-center metrOpt text-white blue', 'data-toggle'=>'tooltip','data-placement'=>'top','data-original-title'=>'Proyectos'])) !!}

				<a class="col m-2 d-flex justify-content-center metrOpt text-white blue" href="#" title="" data-toggle="tooltip" data-placement="top" data-original-title="FINANZAS">
					<i class="material-icons align-self-center">trending_up</i>
				</a>
				<a class="col m-2 d-flex justify-content-center metrOpt text-white blue" href="#" title="" data-toggle="tooltip" data-placement="top" data-original-title="COMPRAS">
					<i class="material-icons align-self-center">shopping_cart</i>
				</a>
			</div>
			<div class="row mx-2 mt-1">
				<a class="col m-2 d-flex justify-content-center metrOpt text-white green" href="#" title="" data-toggle="tooltip" data-placement="top" data-original-title="PROYECTOS">
					<i class="material-icons align-self-center">grade</i>
				</a>
				<a class="col m-2 d-flex justify-content-center metrOpt text-white green" href="#" title="" data-toggle="tooltip" data-placement="top" data-original-title="GESTIÓN DE BANCOS">
					<i class="material-icons align-self-center">monetization_on</i>
				</a>
				<a class="col m-2 d-flex justify-content-center metrOpt text-white green" href="#" title="" data-toggle="tooltip" data-placement="top" data-original-title="SERVICIOS">
					<i class="material-icons align-self-center">perm_phone_msg</i>
				</a>
				<a class="col m-2 d-flex justify-content-center metrOpt text-white green" href="#" title="" data-toggle="tooltip" data-placement="top" data-original-title="INFORMES">
					<i class="material-icons align-self-center">equalizer</i>
				</a>
			</div>
			<div class="row mx-2 mt-1">
				<a class="col-6 m-2 d-flex justify-content-center metrOpt text-white yellow" href="#" title="" data-toggle="tooltip" data-placement="top" data-original-title="PROYECTOS">
					<i class="material-icons align-self-center">grade</i>
				</a>
				<a class="col m-2 d-flex justify-content-center metrOpt text-white yellow" href="#" title="" data-toggle="tooltip" data-placement="top" data-original-title="GESTIÓN DE BANCOS">
					<i class="material-icons align-self-center">monetization_on</i>
				</a>
				<a class="col m-2 d-flex justify-content-center metrOpt text-white yellow" href="#" title="" data-toggle="tooltip" data-placement="top" data-original-title="SERVICIOS">
					<i class="material-icons align-self-center">perm_phone_msg</i>
				</a>
				<a class="col m-2 d-flex justify-content-center metrOpt text-white yellow" href="#" title="" data-toggle="tooltip" data-placement="top" data-original-title="INFORMES">
					<i class="material-icons align-self-center">equalizer</i>
				</a>
			</div>

		</div><!--/col collapsibles-->
		<div id="infoSections" class="col-md-4 col-sm-12"  style="opacity: 0; top:50px;">
			<div class="card text-center z-depth-1-half">
			  <div class="card-header">
				<ul class="nav nav-pills nav-justified" id="myTab" role="tablist">
				  <li class="nav-item">
				    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-expanded="true"><i class="material-icons align-middle">pie_chart</i></a>
				  </li>
				  <li class="nav-item">
				    <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile"><i class="material-icons align-middle">event_note</i></a>
				  </li>
				  <li class="nav-item">
				    <a class="nav-link" id="dropdown1-tab" data-toggle="tab" href="#dropdown1" role="tab" aria-controls="dropdown1"><i class="material-icons align-middle">notifications</i></a>
				  </li>
				</ul>
			  </div>
			  <div class="card-body">
				<div class="tab-content" id="myTabContent">
				  <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
				  	<div class="charts">
				  		<h4>Medicamento <b>más</b> vendido del mes</h4>
				    	<div class="chart" id="pie"></div>
					</div>
				  </div>
				  <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
					<div class="list-group border-0 text-left">
					  <a href="#" class="list-group-item list-group-item-action flex-column align-items-start list-left">
					    <div class="d-flex w-100 justify-content-between">
					      <small class="text-muted d-flex"><i class="material-icons">insert_invitation</i> 24/12/2017</small>
					    </div>
					    <p class="mb-1"><strong>QUERÉTARO</strong></p>
					    <small class="text-muted">4 Incidente(s)</small>
					  </a>
					  <a href="#" class="list-group-item list-group-item-action flex-column align-items-start list-left">
					    <div class="d-flex w-100 justify-content-between">
					      <small class="text-muted d-flex"><i class="material-icons">insert_invitation</i> 24/12/2017</small>
					    </div>
					    <p class="mb-1"><strong>IPEJAL</strong></p>
					    <small class="text-muted">1 Incidente(s)</small>
					  </a>
					  <a href="#" class="list-group-item list-group-item-action flex-column align-items-start list-left">
					    <div class="d-flex w-100 justify-content-between">
					      <small class="text-muted d-flex"><i class="material-icons">insert_invitation</i> 24/12/2017</small>
					    </div>
					    <p class="mb-1"><strong>CHIAPAS</strong></p>
					    <small class="text-muted">2 Incidente(s)</small>
					  </a>
					</div>
				  </div>
				  <div class="tab-pane fade" id="dropdown1" role="tabpanel" aria-labelledby="dropdown1-tab">
					<div class="list-group border-0 text-left">
					  <a href="#" class="list-group-item list-group-item-action flex-column align-items-start list-left">
					    <div class="d-flex w-100 justify-content-between">
					      <small class="text-muted d-flex"><i class="material-icons">insert_invitation</i> 24/12/2017</small>
					    </div>
					    <p class="mb-1">Tienes programado una junta a las 12:00pm</p>
					  </a>
					  <a href="#" class="list-group-item list-group-item-action flex-column align-items-start list-left">
					    <div class="d-flex w-100 justify-content-between">
					      <small class="text-muted d-flex"><i class="material-icons">insert_invitation</i> 24/12/2017</small>
					    </div>
					    <p class="mb-1">Revisar inventario</p>
					  </a>
					</div>
				  </div>
				</div>
			  </div>
			</div>
		</div><!--/col aditional info-->
	</div>
</div>
@endsection
