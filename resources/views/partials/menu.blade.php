<li>
    @if(!$modulo->submodulos->count())
    <a class="w-100" href="{{ !empty($modulo->url) ? companyAction($modulo->url) : '#' }}" title="{{ $modulo->nombre }}" data-toggle="tooltip" data-placement="right">
        <i class='material-icons'>{{ $modulo->icono }}</i>
        <span> {{ $modulo->nombre }} </span>
        {!! !empty($modulo->url) ? "<i class='material-icons window p-2'>open_in_browser</i>" : '' !!}
    </a>
    @else
	<span title="{{ $modulo->nombre }}" data-toggle="tooltip" data-placement="right">
        <a class="collapsed" href="#submenu{{$modulo->id_modulo}}" data-toggle="collapse" aria-expanded="false" title="{{ $modulo->nombre }}">
            	<i class='material-icons left'>{{ $modulo->icono }}</i> {{ $modulo->nombre }}
            	<i class="material-icons float-right grey-text">expand_more</i>
        </a>
	</span>
    <ul id="submenu{{$modulo->id_modulo}}" class="list-unstyled collapse" aria-expanded="false">
        @each('partials.menu', $modulo->submodulos, 'modulo')
    </ul>
    @endif
</li>
