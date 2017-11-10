@component('mail::message')
    {{-- Greeting --}}
    @if (! empty($greeting))
    	# {{ $greeting }}
    @else
        @if ($level == 'error')
        	# Whoops!
        @else
        	# Hello!
        @endif
    @endif
    
    {{-- Intro Lines --}}
    @foreach ($introLines as $line)
    	{{ $line }}
    @endforeach
    
    {{-- Action Button --}}
    @isset($actionText)
        @switch($level)
            @case('success')
                {{$color = 'green'}}
            @break
            @case('error')
                {{$color = 'red'}}
            @break
            @default
                {{$color = 'blue'}}
        @endswitch
       
        @component('mail::button', ['url' => $actionUrl, 'color' => $color])
        	{{ $actionText }}
        @endcomponent
    @endisset
    
    {{-- Outro Lines --}}
    @foreach ($outroLines as $line)
    	{{ $line }}
    @endforeach
    
    {{-- Salutation --}}
    @if (! empty($salutation))
    	{{ $salutation }}
    @else
    	Regards, {{ config('app.name') }}
    @endif
    
    {{-- Subcopy --}}
    @isset($actionText)
        @component('mail::subcopy')
        	Si tiene problemas al hacer clic en el boton "{{ $actionText }}", copiar y pegar la URL que esta a continuación en su navegador web: [{{ $actionUrl }}]({{ $actionUrl }})
        @endcomponent
    @endisset
@endcomponent