@extends('layouts.dashboard')

@section('header-bottom')
	@parent
    <!--Plugins para los charts-->
    {{ HTML::script(asset('js/amcharts/amcharts.js')) }}
    {{ HTML::script(asset('js/amcharts/pie.js')) }}
    {{ HTML::script(asset('js/amcharts/themes/light.js')) }}
    {{ HTML::script(asset('js/pickadate/picker.js')) }}
    {{ HTML::script(asset('js/pickadate/picker.date.js')) }}
    {{ HTML::script(asset('js/pickadate/translations/es_Es.js')) }}
	{!! isset($validator) ? $validator : '' !!}
	
    <script type="text/javascript">
		$(document).ready(function() {

			var from_picker = $('#fecha_ini').pickadate({ selectMonths: true, selectYears: 3, format: 'yyyy-mm-dd' }).pickadate('picker');
			var to_picker = $('#fecha_fin').pickadate({ selectMonths: true, selectYears: 3, format: 'yyyy-mm-dd' }).pickadate('picker');

			

			from_picker.on('set', function(event) {
				if ( 'select' in event ) {
					to_picker.start().clear().set('min', from_picker.get('select'));
			    }

			    if ( 'clear' in event ) {
			    	to_picker.clear().set('min', false).stop();
			    	$('#fecha_fin').prop('readonly', true);
				  }
			});

/*
			var from_$input = $('#fecha_ini').pickadate({ selectMonths: true, selectYears: 3, format: 'yyyy-mm-dd' }),
		    	from_picker = from_$input.pickadate('picker')

    		var to_$input = $('#fecha_fin').pickadate({ selectMonths: true, selectYears: 3, format: 'yyyy-mm-dd' }),
    		    to_picker = to_$input.pickadate('picker')
    
    
    		if ( from_picker.get('value') ) {
    		  to_picker.set('min', from_picker.get('select'))
    		}
    		if ( to_picker.get('value') ) {
    		  from_picker.set('max', to_picker.get('select'))
    		}
    
    		// When something is selected, update the “from” and “to” limits.
    		from_picker.on('set', function(event) {
    		  if ( event.select ) {
    		    to_picker.set('min', from_picker.get('select'))    
    		  }
    		  else if ( 'clear' in event ) {
    		    to_picker.set('min', false)
    		  }
    		})
    		to_picker.on('set', function(event) {
    		  if ( event.select ) {
    		    from_picker.set('max', to_picker.get('select'))
    		  }
    		  else if ( 'clear' in event ) {
    		    from_picker.set('max', false)
    		  }
    		});
*/
        	$("#localidades").select2({       
            	"language": { //para cambiar el idioma a español
                "noResults": function(){
                	return "No se encontraron resultados";
                }
            	},
                escapeMarkup: function (markup) {
                    return markup;
            	}
          	});

            var chart = AmCharts.makeChart( "piepadecimientos", {
                "type": "pie",
                "theme": "light",
                "dataProvider": {!! !empty($padecimientos) ? $padecimientos->toJson() : '[]' !!} ,
                "valueField": "total",
                "titleField": "nombre",
                "outlineAlpha": 0.4,
          	    "depth3D": 15,
          	  	"angle": 50,
          	  	"pullOutRadius": 60,
          	  	"marginTop": 20,
          	  	"labelText": "[[clave]] : [[percents]]%",
          	    "balloonText": "<b>[[clave]] :</b> [[nombre]]<br><span style='font-size:14px'><b>[[value]]</b> ([[percents]]%)</span>",
                "balloon":{
                	"fixedPosition":false
                },
                "export": {
                	"enabled": true
                }
            });

            var chart = AmCharts.makeChart( "piepacientes", {
                "type": "pie",
                "theme": "light",
                "dataProvider": {!! !empty($pacientes) ? $pacientes->toJson() : '[]' !!} ,
                "valueField": "total",
                "titleField": "nombre",
                "outlineAlpha": 0.4,
                "innerRadius": "40%",
                "depth3D": 15,
                "angle": 40,
                "pullOutRadius": 6,
                "marginTop": 20,
                "labelText": "[[clave]] : [[percents]]%",
          	    "balloonText": "[[nombre]]<br><span style='font-size:14px'><b>[[value]]</b> ([[percents]]%)</span>",
                "balloon":{
                	"fixedPosition":true
                },
                "export": {
                	"enabled": true
                }
            });

            var chart = AmCharts.makeChart( "piemedicos", {
                "type": "pie",
                "theme": "light",
                "dataProvider": {!! !empty($medicos) ? $medicos->toJson() : '[]' !!} ,
                "valueField": "total",
                "titleField": "nombre",
                "startEffect": "elastic",
                "outlineAlpha": 0.4,
          	    "depth3D": 20,
          	  	"angle": 50,
          	  	"pullOutRadius": 20,
          	  	"labelText": "[[cedula]] : [[percents]]%",
          	    "balloonText": "<b>[[cedula]] :</b>[[nombre]]<br><span style='font-size:14px'><b>[[value]]</b> ([[percents]]%)</span>",
                "balloon":{
                	"fixedPosition":true,
                },
                "export": {
                	"enabled": true
                }
            });
        });
    </script>
@endsection

@section('content')
<div class="container-fluid">
	<div class="panel shadow-3 panel-danger">
    	<div class="panel-heading">
    		<h3 class="panel-title text-center">Estad&#237;sticas Generales</h3>
    	</div>
    	<div class="panel-body">
    		{!! Form::open(['url' => companyRoute('index'), 'id' => 'form-model', 'class' => 'row']) !!}
    			<div class="col-md-6 col-sm-12 col-xs-12">
    				<div class="form-group">
    					{{ Form::cSelect('Localidad', 'localidades', $localidades ?? []) }}
                    </div>
        		</div>
                <div class="col-md-3 col-sm-6 col-xs-12">
                	<div class="form-group">
                		{{ Form::cText('* Fecha inicio', 'fecha_ini',['readonly'=>true]) }}
                	</div>
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12">
                	<div class="form-group input-field">
                		{{ Form::cText('* Fecha final', 'fecha_fin',['readonly'=>true]) }}
                	</div>
                </div>
                <div class="text-center w-100">
                	<button type="submit" class="btn btn-primary">Aceptar</button>
                </div>
    		{!! Form::close() !!}
    
    		@if(!empty($padecimientos))
    		<div class="divider"></div>
			
            <div class="row">
            	<div class="col-lg-6 col-md-12">
                	<h4>Padecimientos:</h4>
            		<div id="piepadecimientos" class="chart w-100 h-75"></div>
                </div>
                <div class="col-lg-6 col-md-12 border-right table-responsive">
                	<table class="table table-striped table-hover">
                		@if(isset($padecimientos[0]))
                        <thead>
                        	<tr>
                                @foreach($padecimientos[0] as $col=>$value)
                                <th>{{$col}}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                                @foreach($padecimientos as $row)
                                <tr>
                                    @foreach($row as $value)
                                    <th>{{$value}}</th>
                                    @endforeach
                                </tr>
                                @endforeach
                        </tbody>
                        @endif
                	</table>
                </div>
    		</div>
    		@endif
    
    		@if(!empty($pacientes))
            <div class="divider"></div>
            
            <div class="row">
                <div class="col-lg-6 col-md-12 border-right table-responsive">
                	<h4>Pacientes:</h4>
                	<table class="table table-striped table-hover">
                		@if(isset($pacientes[0]))
                        <thead>
                        	<tr>
                                @foreach($pacientes[0] as $col=>$value)
                                <th>{{$col}}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                                @foreach($pacientes as $row)
                                <tr>
                                    @foreach($row as $value)
                                    <th>{{$value}}</th>
                                    @endforeach
                                </tr>
                                @endforeach
                        </tbody>
                        @endif
                	</table>
                </div>
                <div class="col-lg-6 col-md-12">
                    <div id="piepacientes" class="chart w-100 h-75"></div>
                </div>
    		</div>
    		@endif
    
          	@if(!empty($medicos))
          	<div class="divider"></div>
    		
            <div class="row">
            	<div class="col-lg-6 col-md-12">
                	<h4>Medicos:</h4>
            		<div id="piemedicos" class="chart w-100 h-75"></div>
                </div>
                <div class="col-lg-6 col-md-12 border-right table-responsive">
                	<table class="table table-striped table-hover">
                		@if(isset($medicos[0]))
                        <thead>
                        	<tr>
                                @foreach($medicos[0] as $col=>$value)
                                <th>{{$col}}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                                @foreach($medicos as $row)
                                <tr>
                                    @foreach($row as $value)
                                    <th>{{$value}}</th>
                                    @endforeach
                                </tr>
                                @endforeach
                        </tbody>
                        @endif
                	</table>
                </div>
    		</div>
    		@endif

    	</div><!--/panel-body-->
	</div><!--/panel-->
</div>
@endsection
