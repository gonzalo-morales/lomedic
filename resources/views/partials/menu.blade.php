<li>
    @if(!$modulo->submodulos->count())
    <a href="{{ !empty($modulo->url) ? companyAction($modulo->url) : '#' }}">
        <i class='material-icons'>{{ $modulo->icono }}</i> {{ $modulo->nombre }}
    </a>
    @else
    <a class="collapsed" href="#submenu{{$modulo->id_modulo}}" data-toggle="collapse" aria-expanded="false">
    	<i class='material-icons left'>{{ $modulo->icono }}</i> {{ $modulo->nombre }}
    	<i class="material-icons float-right grey-text">expand_more</i>
    </a>
    <ul id="submenu{{$modulo->id_modulo}}" class="list-unstyled collapse" aria-expanded="false">
        @each('partials.menu', $modulo->submodulos, 'modulo')
    </ul>
    @endif
</li>
