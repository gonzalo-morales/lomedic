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
		<div id="metro" class="col-md-8 col-sm-12 mb-3">

			<div class="row justify-content-end mx-2">
				<a class="col-6 m-2 d-inline-flex justify-content-center metrOpt text-white blue" href="#" title="" data-toggle="tooltip" data-placement="top" data-original-title="ADMINISTRACIÓN">
					<i class="material-icons align-self-center">web</i>
				</a>
				<a class="col m-2 d-inline-flex justify-content-center metrOpt text-white blue" href="#" title="" data-toggle="tooltip" data-placement="top" data-original-title="FINANZAS">
					<i class="material-icons align-self-center">trending_up</i>
				</a>
				<a class="col m-2 d-inline-flex justify-content-center metrOpt text-white blue" href="#" title="" data-toggle="tooltip" data-placement="top" data-original-title="FINANZAS">
					<i class="material-icons align-self-center">trending_up</i>
				</a>
				<a class="col m-2 d-inline-flex justify-content-center metrOpt text-white blue" href="#" title="" data-toggle="tooltip" data-placement="top" data-original-title="COMPRAS">
					<i class="material-icons align-self-center">shopping_cart</i>
				</a>
			</div>
			<div class="row justify-content-end mx-2 mt-1">
				<a class="col m-2 d-inline-flex justify-content-center metrOpt text-white blue" href="#" title="" data-toggle="tooltip" data-placement="top" data-original-title="PROYECTOS">
					<i class="material-icons align-self-center">grade</i>
				</a>
				<a class="col m-2 d-inline-flex justify-content-center metrOpt text-white blue" href="#" title="" data-toggle="tooltip" data-placement="top" data-original-title="GESTIÓN DE BANCOS">
					<i class="material-icons align-self-center">monetization_on</i>
				</a>
				<a class="col m-2 d-inline-flex justify-content-center metrOpt text-white blue" href="#" title="" data-toggle="tooltip" data-placement="top" data-original-title="SERVICIOS">
					<i class="material-icons align-self-center">perm_phone_msg</i>
				</a>
				<a class="col m-2 d-inline-flex justify-content-center metrOpt text-white blue" href="#" title="" data-toggle="tooltip" data-placement="top" data-original-title="INFORMES">
					<i class="material-icons align-self-center">equalizer</i>
				</a>
			</div>
			<div class="row justify-content-end mx-2 mt-1">
				<a class="col-6 m-2 d-inline-flex justify-content-center metrOpt text-white blue" href="#" title="" data-toggle="tooltip" data-placement="top" data-original-title="PROYECTOS">
					<i class="material-icons align-self-center">grade</i>
				</a>
				<a class="col m-2 d-inline-flex justify-content-center metrOpt text-white blue" href="#" title="" data-toggle="tooltip" data-placement="top" data-original-title="GESTIÓN DE BANCOS">
					<i class="material-icons align-self-center">monetization_on</i>
				</a>
				<a class="col m-2 d-inline-flex justify-content-center metrOpt text-white blue" href="#" title="" data-toggle="tooltip" data-placement="top" data-original-title="SERVICIOS">
					<i class="material-icons align-self-center">perm_phone_msg</i>
				</a>
				<a class="col m-2 d-inline-flex justify-content-center metrOpt text-white blue" href="#" title="" data-toggle="tooltip" data-placement="top" data-original-title="INFORMES">
					<i class="material-icons align-self-center">equalizer</i>
				</a>
			</div>

		</div><!--/col collapsibles-->
		<div class="col-md-4 col-sm-12">
			<div class="card text-center z-depth-1-half">
			  <div class="card-header">
			    <ul class="nav nav-pills card-header-pills nav-justified">
			      <li class="nav-item">
			        <a class="nav-link active" href="#"><i class="material-icons align-middle">pie_chart</i></a>
			      </li>
			      <li class="nav-item">
			        <a class="nav-link" href="#"><i class="material-icons align-middle">grade</i></a>
			      </li>
			      <li class="nav-item">
			        <a class="nav-link" href="#"><i class="material-icons align-middle">notifications</i></a>
			      </li>
			    </ul>
			  </div>
			  <div class="card-body">
			  	<div class="charts">
			    	<div class="chart" id="pie"></div>
				</div>
			  </div>
			</div>
		</div><!--/col aditional info-->
	</div>
</div>
@endsection
