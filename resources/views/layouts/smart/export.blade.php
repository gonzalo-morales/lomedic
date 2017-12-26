@extends('layouts.slim')

@section('title', currentEntityBaseName())
@section('form-title', currentEntityBaseName())

@section('content')
<table class="smart-table striped col">
	<thead style="text-align: center;">
		<tr style="padding: 10px 0; text-transform: uppercase;">
			@if(strtolower($_GET['type']) == 'pdf')
			<th>
				{{ HTML::image(asset("img/logotipos/$menuempresa->logotipo"), 'Logo', ['class'=>'circle responsive-img','style'=>'height:50px;']) }}
			</th>
			<th colspan="{{ count($fields)-1 }}">
				{{ $menuempresa->nombre_comercial }}
			</th>
			@else
			<th colspan="{{ count($fields) }}">
				{{ $menuempresa->nombre_comercial }}
			</th>
			@endif
		</tr>
		<tr style="padding: 10px 0; text-transform: uppercase;">
			<th colspan="{{ count($fields) }}"> @yield('form-title') </th>
		</tr>
		<tr>
			@foreach ($fields as $label)
			<th> {{ $label }} </th>
			@endforeach
		</tr>
	</thead>
	<tbody>	
	@foreach ($data as $row)
	<tr>
		@foreach ($fields as $field => $label)
		@if(isset($row[$field]))
			<td>{{ $row[$field] }}</td>
		@else
			<td>{{ object_get($row , $field) }}</td>
		@endif
		@endforeach
	</tr>
	@endforeach
	</tbody>
</table>
@endsection