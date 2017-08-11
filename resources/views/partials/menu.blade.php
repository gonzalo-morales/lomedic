<li>
    @if(!$modulo->submodulos->count())
    <a class="waves-effect" href="{{ companyAction($modulo->url) }}">
        <i class='material-icons'>{{ $modulo->icono }}</i>
        <span class="menu-text">{{ $modulo->nombre }}</span>
    </a>
    @else
    <ul class="collapsible collapsible-accordion">
        <li>
            <a class="collapsible-header">
                <i class="material-icons">arrow_drop_down</i> {{ $modulo->nombre }}
            </a>
            <div class="collapsible-body">
                <ul>
                    @each('partials.menu', $modulo->submodulos, 'modulo')
                </ul>
            </div>
        </li>
    </ul>
    @endif
</li>