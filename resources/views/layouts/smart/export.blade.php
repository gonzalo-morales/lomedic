@extends('layouts.slim')

@section('title', currentEntityBaseName())

@section('header-top')
	<!--dataTable.css-->
	<link rel="stylesheet" href="{{ asset('vendor/vanilla-datatables/vanilla-dataTables.css') }}">
@endsection

@section('header-bottom')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/rivets/0.9.6/rivets.bundled.min.js"></script>
    <script src="{{ asset('vendor/vanilla-datatables/vanilla-dataTables.js') }}"></script>
    <script src="{{ asset('js/smartindex.js') }}"></script>
    @if (session('message'))
    <script type="text/javascript">
    	Materialize.toast('<span><i class="material-icons">priority_high</i>{{session('message.text')}}</span>', 4000, '{{session('message.type')}}' );
    </script>
    @endif
@endsection

@section('content')
<table class="smart-table striped col">
	<thead>
		<tr class="teal white-text" style="padding: 10px 0;">
			<th></th>
			@if(strtolower($_GET['type']) == 'pdf')
			<th colspan="{{ count($fields) - 1 }}"> <span class="logo-enterprise">{{ HTML::image(asset("img/logotipos/$menuempresa->logotipo"), 'Logo', ['class'=>'circle responsive-img']) }}</span>
			@endif
			{{ $menuempresa->nombre_comercial }} </th>
			<th class="right-text"> {{ currentEntityBaseName() }} </th>
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
		<td></td>
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