@component('mail::message')
    {{-- Greeting --}}
    @if (! empty($greeting))
        # {{ $greeting }}
    @else
        # @lang('Ciao')
    @endif

    {{-- Intro Lines --}}
    con questa email automatica le inviamo in allegato il report {{ $nomeReport }} emesso da kNet, il portale di Krona Koblenz.

    Come sempre, rimaniamo a sua disposizione.

    Ringraziando per l'attenzione, auguriamo un buon lavoro.

    Staff kNet
    @lang('Regards'),{{ config('app.name') }}


    {{-- Subcopy --}}
    {{-- @component('mail::subcopy')
        @lang(
            "If you’re having trouble please contact the Admin"
        )
    @endcomponent --}}

@endcomponent
