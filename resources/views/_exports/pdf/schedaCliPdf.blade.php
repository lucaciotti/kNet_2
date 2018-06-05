@extends('_exports.pdf.masterPage.masterPdf')

@section('pdf-main')
    <p>
        <div class="row">
            <span class="floatleft">
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

                <h4>{{ trans('client.location') }} </h4>
                <hr style="padding-top: 0; margin-top:0;">
                <dl class="dl-horizontal">

                    <dt>{{ trans('client.location') }}</dt>
                    <dd>{{$client->localita}} ({{$client->prov}}) - @if($client->detNation) {{$client->detNation->descrizion}} @endif</dd>

                    <dt>{{ trans('client.address') }}</dt>
                    <dd>{{$client->indirizzo}}</dd>

                    <dt>{{ trans('client.posteCode') }}</dt>
                    <dd>{{$client->cap}}</dd>

                    {{-- <dt>{{ trans('client.zone') }}</dt>
                    <dd>@if($client->detZona) {{$client->detZona->descrizion}} @endif</dd> --}}
                </dl>

                <h4> {{ trans('client.situationCli') }} </h4>
                <hr style="padding-top: 0; margin-top:0;">
                <dl class="dl-horizontal">

                    <dt>{{ trans('client.statusCli') }}</dt>
                    <dd>{{$client->statocf}} - @if($client->detStato) {{$client->detStato->descrizion}} @endif</dd>

                    <dt>{{ trans('client.paymentType') }}</dt>
                    <dd>{{$client->pag}} - @if($client->detPag) {{$client->detPag->descrizion}} @endif</dd>

                    <dt>{{ trans('client.relationStart') }}</dt>
                    <dd>{{$client->u_dataini}}</dd>
                </dl>
            </span>


            <span class="floatright">
                <br><br><br><br><br><br>
                <dl class="dl-horizontal">
                    <dt>{{ trans('client.referencePerson') }}</dt>
                    <dd>{{$client->persdacont}}</dd>

                    <dt>{{ trans('client.referenceAgent') }}</dt>
                    <dd>@if($client->agent) {{$client->agent->descrizion}} @endif</dd>

                    <hr>

                    <dt>{{ trans('client.phone') }}</dt>
                    <dd>{{$client->telefono}}
                    @if (!empty($client->telefono))
                        &nbsp;<a href="tel:{{$client->telefono}}"><i class="btn btn-xs fa fa-phone bg-green"></i></a>
                    @endif
                    </dd>
                    <dt>{{ trans('client.fax') }}</dt>
                    <dd>{{$client->fax}}</dd>

                    <dt>{{ trans('client.phone2') }}</dt>
                    <dd>{{$client->telex}}</dd>

                    <dt>{{ trans('client.mobilePhone') }}</dt>
                    <dd>{{$client->telcell}}
                    @if (!empty($client->telcell))
                        &nbsp;<a href="tel:{{$client->telcell}}"><i class="btn btn-xs fa fa-phone bg-green"></i></a>
                    @endif
                    </dd>

                    <hr>

                    <dt>{{ trans('client.email') }}</dt>
                    <dd>{{$client->email}}
                    @if (!empty($client->email))
                        &nbsp;<a href="mailto:{{$client->email}}"><i class="btn btn-xs fa fa-envelope-o bg-red"></i></a>
                    @endif
                    </dd>
                </dl>
            </span>
        </div>
    </p>
@endsection