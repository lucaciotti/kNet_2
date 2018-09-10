@component('mail::message')
    {{-- Greeting --}}
    @if (! empty($greeting))
        # {{ $greeting }}
    @else
        # @lang('Ciao')
    @endif

    {{-- Intro Lines --}}
    Un nuovo Utente si è registrato:

        ==> {{ $user->name }} - {{ $user->nickname }}

    Occorre procedere alla sua verifica ed attivazione.

    {{-- Action Button --}}
    {{-- @component('mail::button', ['url' => $actionUrl, 'color' => $color])
    {{ $actionText }}
    @endcomponent --}}


    {{-- Outro Lines --}}


    {{-- Salutation --}}
    @lang('Regards'),{{ config('app.name') }}


    {{-- Subcopy --}}
    {{-- @component('mail::subcopy')
        @lang(
            "If you’re having trouble please contact the Admin"
        )
    @endcomponent --}}

@endcomponent
