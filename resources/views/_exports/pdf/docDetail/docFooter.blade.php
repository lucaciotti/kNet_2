<span class="floatleft20">
    <span class="contentSubTitle">{{ trans('doc.totsDoc') }}</span>
    <dl class="dl-horizontal">
        @if($head->sconti)
        <dt>{{ trans('doc.scontoMerce') }}</dt>
        <dd>{{$head->sconti}} %</dd>
        <hr class="smalldivider">
        @endif

        <dt>{{ trans('doc.totMerce') }}</dt>
        <dd>{{$head->totmerce}} €</dd>

        <dt>{{ trans('doc.deliveryCost') }}</dt>
        <dd>{{$head->speseim + $head->spesetr}} €</dd>

        <hr class="smalldivider">

        <dt>{{ trans('doc.totImp') }}</dt>
        <dd>{{$head->totimp}} €</dd>

        <dt>{{ trans('doc.totVat') }}</dt>
        <dd>{{$head->totiva}} €</dd>
    </dl>
    <dl class="dl-horizontal">
        <dt>{{ trans('doc.totDoc_condensed') }}</dt>
        <dd><strong>{{$head->totdoc}} €</strong></dd>

        @if($head->tipomodulo == 'F' || $head->tipomodulo == 'N')
        <hr class="smalldivider">

        @if($head->scontocass)
        <dt>{{ trans('doc.scontoCassa') }}</dt>
        <dd>{{$head->scontocass}} %</dd>
        @endif

        <dt>{{ trans('doc.totPayment') }}</dt>
        <dd>{{$head->totdoc}} €</dd>
        @endif
    </dl>
</span>

<span class="floatleft20">
    @if($prevDocs->count()>0)
    <span class="contentSubTitle">{{ trans('doc.linkedDocs') }}</span>
        @foreach($prevDocs as $doc)
            <dl>
                <a href="{{ route('doc::downloadPDF', $doc->id) }}">
                    {{$doc->tipodoc}} {{$doc->numerodoc}} del {{$doc->datadoc->format('d/m/Y')}}
                </a>
            </dl>
        @endforeach
    @endif
    
    @if($prevDocs->count()>0)
    <span class="contentSubTitle">{{ trans('doc.nextDocs') }}</span>
    @foreach($nextDocs as $doc)
    <dl>
        <a href="{{ route('doc::downloadPDF', $doc->id) }}">
            {{$doc->tipodoc}} {{$doc->numerodoc}} del {{$doc->datadoc->format('d/m/Y')}}
        </a>
    </dl>
    @endforeach
    @endif

</span>



<span class="floatright">
    @if($head->tipomodulo == 'B' || $head->tipodoc == 'BV' || $head->tipodo == 'EQ')
    <span class="contentSubTitle">{{ trans('doc.dataSped') }}</span>
    <dl class="dl-horizontal">
        <dt>{{ trans('doc.nColli') }}</dt>
        <dd>{{$head->colli}}</dd>

        <dt>{{ trans('doc.goodsAspect') }}</dt>
        <dd>@if($head->detBeni) {{$head->detBeni->descrizion}} @endif</strong></dd>

        <dt>{{ trans('doc.volume') }}</dt>
        <dd>{{$head->volume}} mc</dd>

        <dt>{{ trans('doc.weightNet') }}</dt>
        <dd>{{$head->pesonetto}} Kg</dd>

        <dt>{{ trans('doc.weightGross') }}</dt>
        <dd>{{$head->pesolordo}} Kg</dd>

        <hr class="smalldivider">

        <dt>{{ trans('doc.carrier') }}</dt>
        <dd>@if($head->vettore) {{$head->vettore->descrizion}} @endif</dd>

        <dt>{{ trans('doc.carrierDelivery') }}</dt>
        <dd>@if($head->v1data) {{$head->v1data->format('d/m/Y')}} - {{$head->v1ora}} @else -- @endif</dd>

        @if($destinaz)
        <hr class="smalldivider">
        <dt>{{ trans('doc.goodsDestination') }}</dt>
        <dd>{{$destinaz->ragionesoc}}</dd>
        <dd>{{$destinaz->cap}}, {{$destinaz->localita}} ({{$destinaz->pv}}) - {{$destinaz->u_nazione}}</dd>
        <dd>{{$destinaz->indirizzo}}</dd>
        <dd>{{$destinaz->telefono}}</dd>
        @endif

        @if (!empty($ddtOk))
        <hr class="smalldivider">
        <dt>{{ trans('doc.dataReceived') }}</dt>
        <dd>{{$ddtOk->created_at->format('d/m/Y')}}</dd>

        <dt>{{ trans('doc.signReceived') }}</dt>
        <dd>{{$ddtOk->firma}}</dd>

        <dt>{{ trans('doc.noteReceived') }}</dt>
        <dd>{{$ddtOk->note or ''}}</dd>
        @endif

        @if (!empty($head->patrasf))
        <hr class="smalldivider">
        <dt>ID Tracking: </dt>
        <dd>
            <a target="_blank" href={!! str_replace('<IDCOLLO>', $head->patrasf, $head->vettore->note) !!}>
                <strong> {{$head->patrasf}} </strong>
            </a>
        </dd>
        @endif
    </dl>
    @endif
</span>