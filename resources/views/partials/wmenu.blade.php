@if($modulo->submodulos->count())
	<div class="col-sm-2 d-flex text-white metrOpt green m-2 p-0 row" style="height:auto;">
    		<a class="wmenu pt-4 col-sm-12 text-center justify-content-center metrOpt text-white green collapsed" href="#wsubmenu{{$modulo->id_modulo}}" data-toggle="collapse" aria-expanded="false" title="{{ $modulo->nombre }}">
            	<i class='material-icons left'>{{ $modulo->icono }}</i> {{ $modulo->nombre }}
            	<span class="tag-menu">{{ $modulo->descripcion }}</span>
        	</a>
        <div id="wsubmenu{{$modulo->id_modulo}}" class="col-sm-12 list-unstyled collapse p-0 m-0" aria-expanded="false">
        	<ul class="list-group">
            	@each('partials.wmenu', $modulo->submodulos, 'modulo')
            </ul>
        </div>
    </div>
@else
    <li class="list-group-item">
    	<a class="w-100" href="{{ !empty($modulo->url) ? companyAction($modulo->url) : '#' }}" title="{{ $modulo->nombre }}" data-toggle="tooltip" data-placement="right">
            <i class='material-icons'>{{ $modulo->icono }}</i>
            <span> {{ $modulo->nombre }} </span>
            <span class="tag-menu">{{ $modulo->descripcion }}</span>
            {!! !empty($modulo->url) ? "<i class='material-icons window p-2 float-right'>open_in_browser</i>" : '' !!}
    	</a>
	</li>
@endif