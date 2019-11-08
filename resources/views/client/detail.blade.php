@extends('layouts.app')

@section('htmlheader_title')
    - {{ trans('client.headTitle_dtl') }}
@endsection

@section('contentheader_title')
    {{$client->descrizion}}
@endsection

@section('contentheader_description')
    [{{$client->codice}}]
@endsection

@section('contentheader_breadcrumb')
  {!! Breadcrumbs::render('client', $client->codice) !!}
@endsection

@section('main-content')
{{-- <div class="container"> --}}
<div class="row">
  <div class="col-lg-4">
    <div class="nav-tabs-custom">
      <ul class="nav nav-tabs">
        <li class="active"><a href="#Anag" data-toggle="tab" aria-expanded="true">{{ trans('client.dataCli') }}</a></li>
        <li class=""><a href="#Cont" data-toggle="tab" aria-expanded="false">{{ trans('client.contactCli') }}</a></li>
        @if(RedisUser::get('role')!='client')
          <li class=""><a href="#List" data-toggle="tab" aria-expanded="false">Listini Personalizzati</a></li>
        @endif
      </ul>
      <div class="tab-content">
        <div class="tab-pane active" id="Anag">
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
        <!-- /.tab-pane -->
        <div class="tab-pane" id="Cont">
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

            <hr>

            <dt>{{ trans('client.emailAdm') }}</dt>
            <dd>{{$client->emailam}}
              @if (!empty($client->emailam))
                  &nbsp;<a href="mailto:{{$client->emailam}}"><i class="btn btn-xs fa fa-envelope-o bg-red"></i></a>
              @endif
            </dd>

            <dt>{{ trans('client.emailOrder') }}</dt>
            <dd>{{$client->emailut}}
              @if (!empty($client->emailut))
                  &nbsp;<a href="mailto:{{$client->emailut}}"><i class="btn btn-xs fa fa-envelope-o bg-red"></i></a>
              @endif
            </dd>

            <dt>{{ trans('client.emailDdt') }}</dt>
            <dd>{{$client->emailav}}
              @if (!empty($client->emailav))
                  &nbsp;<a href="mailto:{{$client->emailav}}"><i class="btn btn-xs fa fa-envelope-o bg-red"></i></a>
              @endif
            </dd>

            <dt>{{ trans('client.emailInvoice') }}</dt>
            <dd>{{$client->emailpec}}
              @if (!empty($client->emailpec))
                  &nbsp;<a href="mailto:{{$client->emailpec}}"><i class="btn btn-xs fa fa-envelope-o bg-red"></i></a>
              @endif
            </dd>

          </dl>
        </div>

        <div class="tab-pane" id="List">
          <dl class="dl-horizontal">
            @if($client->gruppolist)
              <dt>Listino Gruppo Cliente</dt>
              <dd>
                <a type="button" class="btn btn-default btn-block" href="{{ route('listini::grpCli', [$client->gruppolist]) }}" >
                    {{$client->gruppolist}} - {{$client->grpCli->descrizion or ''}}
                </a>
              </dd>
            @endif
        
            <dt>Listino Cliente</dt>
            <dd>
              <a type="button" class="btn btn-default btn-block" href="{{ route('listini::idxCli', [$client->codice]) }}">
                  Listino Personalizzato
              </a>
            </dd>
        
            <hr>
        
          </dl>
        </div>
      </div>
    </div>
  </div>

  <div class="col-lg-4">
    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">{{ trans('client.maps') }}</h3>
        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        </div>
      </div>
      <div class="box-body">
        <div style="height: 403px; width: 100%;">
          @if($mapsException=='')
            {!! Mapper::render() !!}
          @else
            {{ $mapsException }}
          @endif
        </div>
      </div>
    </div>
  </div>

  <div class="col-lg-4">
    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title" data-widget="collapse">{{ trans('client.docuCli') }}</h3>
        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        </div>
      </div>
      <div class="box-body">
        <a type="button" class="btn btn-default btn-block" href="{{ route('doc::client', [$client->codice, '']) }}">{{ strtoupper(trans('client.allDocs')) }}</a>
        <a type="button" class="btn btn-default btn-block" href="{{ route('doc::client', [$client->codice, 'P']) }}">{{ trans('client.quotes') }}</a>
        <a type="button" class="btn btn-default btn-block" href="{{ route('doc::client', [$client->codice, 'O']) }}">{{ trans('client.orders') }}</a>
        <a type="button" class="btn btn-default btn-block" href="{{ route('doc::client', [$client->codice, 'B']) }}">{{ trans('client.ddt') }}</a>
        <a type="button" class="btn btn-default btn-block" href="{{ route('doc::client', [$client->codice, 'F']) }}">{{ trans('client.invoice') }}</a>
        <a type="button" class="btn btn-default btn-block" href="{{ route('doc::client', [$client->codice, 'N']) }}">{{ trans('client.notecredito') }}</a>
      </div>
    </div>

    <div class="box box-default collapsed-box">
      <div class="box-header with-border">
        <h3 class="box-title" data-widget="collapse">{{ trans('client.paymentCli') }}</h3>
        <span class="badge bg-yellow">{{$scads->count()}}</span>
        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
        </div>
      </div>
      <div class="box-body">
        @include('scads.partials.tblGeneric', $scads)
      </div>
    </div>

    @if (!Auth::user()->hasRole('client'))
    <div class="box box-default collapsed-box">
      <div class="box-header with-border">
        <h3 class="box-title" data-widget="collapse"><i class='fa fa-cloud-download'> </i> Download</h3>
        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
        </div>
      </div>
      <div class="box-body">
        <a type="button" class="btn btn-default btn-block" target="_blank" href="{{ route('schedaCli::PDF', $client->codice) }}">Scheda Cliente PDF</a>
      </div>
    </div>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title" data-widget="collapse">{{ trans('client.statsCli') }}</h3>
        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        </div>
      </div>
      <div class="box-body">
        <a type="button" class="btn btn-default btn-block" href="{{ route('stFatt::fltCli', $client->codice) }}">{{ trans('client.revenue') }}</a>
        <a type="button" class="btn btn-default btn-block" href="{{ route('stAbc::idxCli', ['codcli'=>$client->codice]) }}">Abc Articoli</a>
      </div>
    </div>
    @endif

    @if (!Auth::user()->hasRole('client') && !Auth::user()->hasRole('agent') && !Auth::user()->hasRole('superagent'))
    <div class="box box-default collapsed-box">
      <div class="box-header with-border">
        <h3 class="box-title" data-widget="collapse"> Richiesta di Personalizzazione Prodotto</h3>
        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
        </div>
      </div>
      <div class="box-body">
        <a type="button" class="btn btn-default btn-block" target="_blank"
          href="{{ route('ModRicFatt::create', ['codicecf'=>$client->codice]) }}">Compila Modulo richiesta</a>
      </div>
    </div>
    @endif
  </div>

</div>
@if (!Auth::user()->hasRole('client'))
<div class="row">

  <div class="col-lg-6">
    @include('client.partials.timeline', [
      'visits' => $visits,
      'codcli' => $client->codice,
      'dateNow' => $dateNow,
      ])
  </div>

  <div class="col-lg-6">
    <div class="box box-default collapsed-box">  {{-- collapsed-box --}}
      <div class="box-header with-border">
        <h3 class="box-title" data-widget="collapse">{{ trans('client.noteCli') }}</h3>
        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
        </div>
      </div>
      <div class="box-body">
        <strong>{!! $client->note !!}</strong>
      </div>
    </div>

  </div>
</div>
@endif
<script type="text/javascript">

    function onMapLoad(map)
    {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    var pos = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude
                    };

                    var marker = new google.maps.Marker({
                      position: pos,
                      map: map,
                      label: "#",
                      title: "You Are Here"
                    });

                    // map.setCenter(pos);
                }
            );
        }
    }
</script>

{{-- </div> --}}
@endsection
