@extends('layouts.app')

@section('htmlheader_title')
    - {{ trans('client.headTitle_dtl') }}
@endsection

@section('contentheader_title')
    {{$contact->descrizion}}
@endsection

{{-- @section('contentheader_description')
    [{{$client->codice}}]
@endsection --}}

{{-- @section('contentheader_breadcrumb')
  {!! Breadcrumbs::render('client', $client->codice) !!}
@endsection --}}

@section('main-content')
{{-- <div class="container"> --}}
<div class="row">
  <div class="col-lg-4">
    <div class="nav-tabs-custom">
      <ul class="nav nav-tabs">
        <li class="active"><a href="#Anag" data-toggle="tab" aria-expanded="true">{{ trans('client.dataCli') }}</a></li>
        <li class=""><a href="#Cont" data-toggle="tab" aria-expanded="false">{{ trans('client.contactCli') }}</a></li>
      </ul>
      <div class="tab-content">
        <div class="tab-pane active" id="Anag">
          <dl class="dl-horizontal">
            <dt>{{ trans('client.descCli') }}</dt>
            <dd>
              <big><strong>{{$contact->descrizion}}</strong></big>
              {{-- <small>{{$client->supragsoc}}</small> --}}
            </dd>

            <dt>{{ trans('client.codeCli') }}</dt>
            <dd>@if ($contact->codicecf)
                <a href="{{ route('client::detail', $contact->codicecf ) }}">{{$contact->codicecf}} - {{$contact->client->descrizion}}</a>
            @endif</dd>

            <dt>{{ trans('client.vatCode') }}</dt>
            <dd>{{$contact->partiva}}</dd>

            @if($contact->codfiscale != $contact->partiva)
              <dt>{{ trans('client.taxCode') }}</dt>
              <dd>{{$contact->codfiscale}}</dd>
            @endif

            <dt>Sito</dt>
            <dd>{{$contact->sitoweb}} @if (!empty($contact->sitoweb)) &nbsp;
              <a href="http://{{$contact->sitoweb}}" target="_blank"><i class="btn btn-xs fa fa-external-link btn-primary"></i></a> @endif
            </dd>

            {{-- <dt>{{ trans('client.sector_full') }}</dt>
            <dd>{{$client->settore}} - @if($client->detSect) {{$client->detSect->descrizion}} @endif</dd> --}}
          </dl>

          <h4><strong> {{ trans('client.location') }} </strong> </h4>
          <hr style="padding-top: 0; margin-top:0;">
          <dl class="dl-horizontal">

            <dt>{{ trans('client.location') }}</dt>
            <dd>{{$contact->localita}} ({{$contact->prov}}) - {{ $contact->regione }} - {{$contact->codnazione}} </dd>

            <dt>{{ trans('client.address') }}</dt>
            <dd>{{$contact->indirizzo}}</dd>

            <dt>{{ trans('client.posteCode') }}</dt>
            <dd>{{$contact->cap}}</dd>

          </dl>

          <h4><strong> {{ trans('client.situationCli') }}</strong> </h4>
          <hr style="padding-top: 0; margin-top:0;">
          <dl class="dl-horizontal">

            <dt>{{ trans('client.statusCli') }}</dt>
            <dd>
              {{$contact->statocf}} - 
              @if($contact->statocf=='T') 
              Attivo
              {{Form::open(['method' => 'POST', 'route' => ['rubri::close', $contact->id]])}} 
              {{Form::button('Chiudi Contatto', array('type' => 'submit', 'class' => 'btn-xs btn-danger'))}} 
              {{Form::close() }} 
              @else Chiuso @endif
            </dd>

            <dt>Data Ultima Visita</dt>
            <dd>@if($contact->date_lastvisit){{$contact->date_lastvisit->format('d-m-Y')}}@endif</dd>

            <dt>Data Prossima Visita</dt>
            <dd>@if($contact->date_nextvisit){{$contact->date_nextvisit->format('d-m-Y')}}@endif</dd>
          </dl>
        </div>
        <!-- /.tab-pane -->
        <div class="tab-pane" id="Cont">
          <dl class="dl-horizontal">

            <dt>{{ trans('client.referencePerson') }}</dt>
            <dd>{{$contact->legalerapp}}</dd>

            <dt>n° Dipendenti</dt>
            <dd>@if($contact->nDipendenti){{$contact->nDipendenti}}@endif</dd>

            <dt>{{ trans('client.referenceAgent') }}</dt>
            <dd>@if($contact->agent) {{$contact->agent->descrizion}} @endif</dd>

            <hr>

            <dt>{{ trans('client.phone') }}</dt>
            <dd>{{$contact->telefono}}
              @if (!empty($contact->telefono))
                  &nbsp;<a href="tel:{{$contact->telefono}}"><i class="btn btn-xs fa fa-phone bg-green"></i></a>
              @endif
            </dd>

            <hr>

            <dt>{{ trans('client.email') }}</dt>
            <dd>{{$contact->email}}
              @if (!empty($contact->email))
                  &nbsp;<a href="mailto:{{$contact->email}}"><i class="btn btn-xs fa fa-envelope-o bg-red"></i></a>
              @endif
            </dd>

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
        <h3 class="box-title" data-widget="collapse">Analisi di Mercato 2019</h3>
        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        </div>
      </div>
      <div class="box-body">
        @if ($contact->isModCarp01)
          <a type="button" class="btn btn-success btn-block" href="{{ route('ModCarp01::edit', $contact->id) }}">Edita Modulo</a> 
          <br>   
          {{Form::open(['method'  => 'DELETE', 'route' => ['ModCarp01::delete', $contact->id]])}}
          {{Form::button('Cancella Modulo', array('type' => 'submit', 'class' => 'btn btn-danger btn-block'))}}
          {{Form::close() }}
          {{-- <a type="button" class="btn btn-danger btn-block" href="{{ route('ModCarp01::delete', $contact->id) }}" data-method="delete" data-token="{{csrf_token()}}" data-confirm="Are you sure?">Cancella Modulo</a> --}}
        @else
          <a type="button" class="btn btn-warning btn-block" href="{{ route('ModCarp01::create', $contact->id) }}">Compilazione Modulo</a>
        @endif
      </div>
    </div>

    @if ($contact->isModCarp01)
    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title" data-widget="collapse">Dati Contatto</h3>
        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        </div>
      </div>
      <div class="box-body">
        <dl class="dl-horizontal">
          <dt>Tipologia Produzione</dt>
          <dd>
            @if ($contact->prod_porte) Porte - @endif
            @if ($contact->prod_portefinestre) Finestre - @endif
            @if ($contact->prod_mobili) Mobili - @endif
            @if ($contact->prod_cucine) Cucine - @endif
            @if ($contact->prod_other) Altro - {{$contact->prod_note}} @endif
          </dd>
          
          <dt>Conosce Krona Koblenz?</dt>
          <dd>
            <big>
              <strong>
                @if ($contact->know_kk) SI @endif
                @if (!$contact->know_kk) NO @endif
              </strong>
            </big>
          </dd>

          <dt>Acquista Già Prodotti KK?</dt>
          <dd>
            <big>
              <strong>
                @if ($contact->idKkBuyer) SI @endif
                @if (!$contact->idKkBuyer) NO @endif
              </strong>
            </big>
          </dd>
          @if (!$contact->idKkBuyer || !$contact->know_kk)
          <dt>Vuole Provare KK?</dt>
          <dd>
            <big>
              <strong>
                @if ($contact->wants_tryKK) SI @endif
                @if (!$contact->wants_tryKK) NO @endif
              </strong>
            </big>
          </dd>              
          @endif
          @if ($contact->know_kk)
          <dt>Vuole Info da KK?</dt>
          <dd>
            <big>
              <strong>
                @if ($contact->wants_info) SI @endif
                @if (!$contact->wants_info) NO @endif
              </strong>
            </big>
          </dd>
          @endif
        </dl>
      </div>
    </div>

    @endif

    @if ($contact->codicecf)
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title" data-widget="collapse">{{ trans('client.statsCli') }}</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          </div>
        </div>
        <div class="box-body">
          <a type="button" class="btn btn-default btn-block" href="{{ route('stFatt::fltCli', $contact->codicecf) }}">{{ trans('client.revenue') }}</a>
          <a type="button" class="btn btn-default btn-block" href="{{ route('stAbc::idxCli', ['codcli'=>$contact->codicecf]) }}">Abc Articoli</a>
        </div>
      </div>        
    @endif

    {{-- @if (!Auth::user()->hasRole('client'))
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
    @endif --}}
  </div>

</div>
@if (!Auth::user()->hasRole('client'))
<div class="row">

  <div class="col-lg-6">
    @include('rubri.partials.timeline', [
      'visits' => $visits,
      'codcli' => '',
      'rubri_id' => $contact->id,
      'dateNow' => $dateNow,
      ])
  </div>

  <div class="col-lg-6">
    <div class="box box-default collapsed-box"> 
      <div class="box-header with-border">
        <h3 class="box-title" data-widget="collapse">{{ trans('client.noteCli') }}</h3>
        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
        </div>
      </div>
      <div class="box-body">
        <strong>{!! $contact->final_note !!}</strong>
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
