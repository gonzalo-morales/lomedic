<!-- Sidebar Holder -->
<nav id="rigth-sidebar" class="bg-white">
    <div class="sidebar-header">
        <img class="background" src="{{asset('img/helpBG2.png')}}">
    	<div class="h-content">
            <span><i class="material-icons medium white-text">live_help</i></span>
            <a class="white-text" href="#"><span class="name">{{cTrans('messages.help_section','Secci�n de Ayuda')}}</span></a>
            <a href="{{companyAction("Soporte\SolicitudesController@create")}}" class="white-text dismiss"><span class='window'>Crear ticket/solicitud</span></a>
        </div>
    </div>
    <ul class="list-unstyled components bg-white">
    	
    	<li>
            <a class="collapsed d-flex align-items-center" href="#tickets" data-toggle="collapse" aria-expanded="false">
            	<i class="material-icons">list</i>Tickets recientes
            	<i class="material-icons float-right grey-text">expand_more</i>
            </a>
            <ul id="tickets" class="list-unstyled collapse" aria-expanded="false">
            	@php($ultimos_tickets = ticket_menu())
            	@if(!isset($ultimos_tickets) & !$ultimos_tickets->count())
            		<li> </li>
            	@endif
                @foreach($ultimos_tickets as $ticket)
                <li>
                	<a href="{{ companyAction('Soporte\SolicitudesController@show', ['id' => $ticket->id_solicitud]) }}" class="btn d-flex align-items-center"><i class="material-icons">note</i>
                        {{$ticket->asunto}}
                    </a>
                </li>
                @endforeach
            </ul>
        </li>
        <li><a class="d-flex align-items-center" href="{{ companyAction('Soporte\SolicitudesController@index') }}"><i class="material-icons">dvr</i>Todos mis tickets</a></li>
        <div class="divider"></div>
        <li><a href="#!">Gestion Estrategica (GE)</a></li>
        <li><a href="#!">Gestion de Negociacion (GN)</a></li>
        <li><a href="#!">Gestion de Recursos (GR)</a></li>
        <li><a href="#!">Proveeduria y Suministro (PS)</a></li>
    </ul>
    <div class="w-100 text-center text-primary mt-2 mb-1" style="position:absolute; bottom:0; border-top:1px solid #ddd;">{{cTrans('messages.lang','Idioma')}}: 
		@foreach(config('app.locales') as $s=>$lang)
			{{ HTML::link(url('/lang/'.$s), $s, ['class' => 'text-danger', 'title' => $lang]) }}
		@endforeach
	</div>
</nav>

<div class="overlay"></div>
{!! !empty($modulo->url) ? "<i class='material-icons window p-2'>open_in_browser</i>" : '' !!}
