@extends('layouts.dashboard')

@section('title', 'Inicio')

@section('header-bottom')
	<script type="text/javascript">
		$('.wmenu').click( function() {
			if($(this).attr('aria-expanded') == 'false')
				$(this).parent().addClass('col-sm-4').removeClass('col-sm-2');
			else
				$(this).parent().addClass('col-sm-2').removeClass('col-sm-4');
		});
	</script>
@endsection

@section('content')
<div class="text-center">
	<h4 class="display-4">INVENTARIOS</h4>
</div>
<div class="container-fluid"> <!--
	<div id="metro" class="row col-sm-12 mb-3" style="opacity: 0; top:50px;">
		@if(isset($menu) && !empty($menu->where('id_modulo',5)[0]->submodulos))
			@each('partials.wmenu', $menu->where('id_modulo',5)[0]->submodulos , 'modulo')
		@endif
	</div>-->
</div>
@endsection
