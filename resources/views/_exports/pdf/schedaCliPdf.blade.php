@extends('_exports.pdf.masterPage.masterPdf')

@section('Pdf_title')
    Scheda Cliente
@endsection

@section('Pdf_description')
    - {{ $client->descrizion }}
@endsection

@section('pdf-main')
    <p>
        <div class="row">
        <dl class="dl-horizontal">
            <dt>{{ trans('client.descCli') }}</dt>
            <dd>
                <big><strong>{{$client->descrizion}}</strong></big>
                <small>{{$client->supragsoc}}</small>
            </dd>

            <dt>{{ trans('client.codeCli') }}</dt>
            <dd>{{$client->codice}}</dd>

            <dt>{{ trans('client.vatCode') }}</dt>
            <dd>{{$client->partiva}}</dd>

            @if($client->codfiscale != $client->partiva)
                <dt>{{ trans('client.taxCode') }}</dt>
                <dd>{{$client->codfiscale}}</dd>
            @endif

            <dt>{{ trans('client.sector_full') }}</dt>
            <dd>{{$client->settore}} - @if($client->detSect) {{$client->detSect->descrizion}} @endif</dd>
            </dl>

            <h4><strong> {{ trans('client.location') }} </strong> </h4>
            <hr style="padding-top: 0; margin-top:0;">
            <dl class="dl-horizontal">

            <dt>{{ trans('client.location') }}</dt>
            <dd>{{$client->localita}} ({{$client->prov}}) - @if($client->detNation) {{$client->detNation->descrizion}} @endif</dd>

            <dt>{{ trans('client.address') }}</dt>
            <dd>{{$client->indirizzo}}</dd>

            <dt>{{ trans('client.posteCode') }}</dt>
            <dd>{{$client->cap}}</dd>

            <dt>{{ trans('client.zone') }}</dt>
            <dd>@if($client->detZona) {{$client->detZona->descrizion}} @endif</dd>
            </dl>

            <h4><strong> {{ trans('client.situationCli') }}</strong> </h4>
            <hr style="padding-top: 0; margin-top:0;">
            <dl class="dl-horizontal">

            <dt>{{ trans('client.statusCli') }}</dt>
            <dd>{{$client->statocf}} - @if($client->detStato) {{$client->detStato->descrizion}} @endif</dd>

            <dt>{{ trans('client.paymentType') }}</dt>
            <dd>{{$client->pag}} - @if($client->detPag) {{$client->detPag->descrizion}} @endif</dd>

            <dt>{{ trans('client.relationStart') }}</dt>
            <dd>{{$client->u_dataini}}</dd>

            <dt>{{ trans('client.relationEnd') }}</dt>
            <dd>{{$client->u_datafine}}</dd>
        </dl>
    </div>
    </p>
@endsection