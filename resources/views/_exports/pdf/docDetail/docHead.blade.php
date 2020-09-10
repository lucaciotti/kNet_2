<span class="floatleft">
    <span class="contentSubTitle">{{ trans('doc.dataDoc') }}</span>
    <dl class="dl-horizontal">
        <dt>{{ trans('doc.document') }}</dt>
        <dd>
            <a href="{{ route('doc::detail', $head->id) }}">
                <strong>{{$head->tipodoc}} {{$head->numerodoc}}</strong>
            </a>
        </dd>

        <dt>{{ trans('doc.client') }}</dt>
        <dd><strong>{{$head->client->descrizion}} [{{$head->codicecf}}]</strong></dd>

        <dt>{{ trans('doc.dateDoc') }}</dt>
        <dd>{{$head->datadoc->format('d/m/Y')}}</dd>

        @if($head->tipomodulo == 'O')
        <dt>{{ trans('doc.deliverType') }}</dt>
        <dd>{{$head->u_tipocons}}</dd>

        {{-- <dt>Prevista Consegna</dt>
                          <dd>{{$head->datacons->format('d/m/Y')}}</dd> --}}
        @endif

        <dt>{{ trans('doc.referenceDoc') }}</dt>
        <dd>{{$head->numerodocf}}</dd>

        <dt>{{ trans('client.agent') }}</dt>
        <dd>{{$head->agente}} - {{$head->agent->descrizion}}</dd>
    </dl>
</span>

<span class="floatright">
    @if (in_array(RedisUser::get('location'), ['it']))
    <img src="{{ asset('/img/loghi/KK-AEO_logo_col.jpg') }}" alt="" height="120" align="right">
    @endif
    @if (in_array(RedisUser::get('location'), ['es']))
    <img src="{{ asset('/img/loghi/KK_SP.jpg') }}" alt="" height="120" align="right">
    @endif
    @if (in_array(RedisUser::get('location'), ['fr']))
    <img src="{{ asset('/img/loghi/KK_FR.jpg') }}" alt="" height="120" align="right">
    @endif
</span>