@extends('layouts.app')

@section('htmlheader_title')
    - {{ trans('supplier.headTitle_dtl') }}
@endsection

@section('contentheader_title')
    {{$supplier->descrizion}}
@endsection

@section('contentheader_description')
    [{{$supplier->codice}}]
@endsection

@section('contentheader_breadcrumb')
  {!! Breadcrumbs::render('supplier', $supplier->codice) !!}
@endsection

@section('main-content')
{{-- <div class="container"> --}}
<div class="row">
  <div class="col-lg-6">
    <div class="nav-tabs-custom">
      <ul class="nav nav-tabs">
        <li class="active"><a href="#Anag" data-toggle="tab" aria-expanded="true">{{ trans('supplier.dataCli') }}</a></li>
        <li class=""><a href="#Cont" data-toggle="tab" aria-expanded="false">{{ trans('supplier.contactCli') }}</a></li>
        @if(RedisUser::get('role')!='supplier')
          {{-- <li class=""><a href="#List" data-toggle="tab" aria-expanded="false">Listini Personalizzati</a></li> --}}
        @endif
      </ul>
      <div class="tab-content">
        <div class="tab-pane active" id="Anag">
          <dl class="dl-horizontal">
            <dt>{{ trans('supplier.descCli') }}</dt>
            <dd>
              <big><strong>{{$supplier->descrizion}}</strong></big>
              <small>{{$supplier->supragsoc}}</small>
            </dd>

            <dt>{{ trans('supplier.codeCli') }}</dt>
            <dd>{{$supplier->codice}}</dd>

            <dt>{{ trans('supplier.vatCode') }}</dt>
            <dd>{{$supplier->partiva}}</dd>

            @if($supplier->codfiscale != $supplier->partiva)
              <dt>{{ trans('supplier.taxCode') }}</dt>
              <dd>{{$supplier->codfiscale}}</dd>
            @endif

            <dt>{{ trans('supplier.sector_full') }}</dt>
            <dd>{{$supplier->settore}} - @if($supplier->detSect) {{$supplier->detSect->descrizion}} @endif</dd>
          </dl>

          <h4><strong> {{ trans('supplier.location') }} </strong> </h4>
          <hr style="padding-top: 0; margin-top:0;">
          <dl class="dl-horizontal">

            <dt>{{ trans('supplier.location') }}</dt>
            <dd>{{$supplier->localita}} ({{$supplier->prov}}) - @if($supplier->detNation) {{$supplier->detNation->descrizion}} @endif</dd>

            <dt>{{ trans('supplier.address') }}</dt>
            <dd>{{$supplier->indirizzo}}</dd>

            <dt>{{ trans('supplier.posteCode') }}</dt>
            <dd>{{$supplier->cap}}</dd>

            <dt>{{ trans('supplier.zone') }}</dt>
            <dd>@if($supplier->detZona) {{$supplier->detZona->descrizion}} @endif</dd>
          </dl>

          <h4><strong> {{ trans('supplier.situationCli') }}</strong> </h4>
          <hr style="padding-top: 0; margin-top:0;">
          <dl class="dl-horizontal">

            <dt>{{ trans('supplier.statusCli') }}</dt>
            <dd>{{$supplier->statocf}} - @if($supplier->detStato) {{$supplier->detStato->descrizion}} @endif</dd>

            <dt>{{ trans('supplier.paymentType') }}</dt>
            <dd>{{$supplier->pag}} - @if($supplier->detPag) {{$supplier->detPag->descrizion}} @endif</dd>

            <dt>{{ trans('supplier.relationStart') }}</dt>
            <dd>{{$supplier->u_dataini}}</dd>

            <dt>{{ trans('supplier.relationEnd') }}</dt>
            <dd>{{$supplier->u_datafine}}</dd>
          </dl>
        </div>
        <!-- /.tab-pane -->
        <div class="tab-pane" id="Cont">
          <dl class="dl-horizontal">

            <dt>{{ trans('supplier.referencePerson') }}</dt>
            <dd>{{$supplier->persdacont}}</dd>

            <dt>{{ trans('supplier.referenceAgent') }}</dt>
            <dd>@if($supplier->agent) {{$supplier->agent->descrizion}} @endif</dd>

            <hr>

            <dt>{{ trans('supplier.phone') }}</dt>
            <dd>{{$supplier->telefono}}
              @if (!empty($supplier->telefono))
                  &nbsp;<a href="tel:{{$supplier->telefono}}"><i class="btn btn-xs fa fa-phone bg-green"></i></a>
              @endif
            </dd>
            <dt>{{ trans('supplier.fax') }}</dt>
            <dd>{{$supplier->fax}}</dd>

            <dt>{{ trans('supplier.phone2') }}</dt>
            <dd>{{$supplier->telex}}</dd>

            <dt>{{ trans('supplier.mobilePhone') }}</dt>
            <dd>{{$supplier->telcell}}
              @if (!empty($supplier->telcell))
                  &nbsp;<a href="tel:{{$supplier->telcell}}"><i class="btn btn-xs fa fa-phone bg-green"></i></a>
              @endif
            </dd>

            <hr>

            <dt>{{ trans('supplier.email') }}</dt>
            <dd>{{$supplier->email}}
              @if (!empty($supplier->email))
                  &nbsp;<a href="mailto:{{$supplier->email}}"><i class="btn btn-xs fa fa-envelope-o bg-red"></i></a>
              @endif
            </dd>

            <hr>

            <dt>{{ trans('supplier.emailAdm') }}</dt>
            <dd>{{$supplier->emailam}}
              @if (!empty($supplier->emailam))
                  &nbsp;<a href="mailto:{{$supplier->emailam}}"><i class="btn btn-xs fa fa-envelope-o bg-red"></i></a>
              @endif
            </dd>

            <dt>{{ trans('supplier.emailOrder') }}</dt>
            <dd>{{$supplier->emailut}}
              @if (!empty($supplier->emailut))
                  &nbsp;<a href="mailto:{{$supplier->emailut}}"><i class="btn btn-xs fa fa-envelope-o bg-red"></i></a>
              @endif
            </dd>

            <dt>{{ trans('supplier.emailDdt') }}</dt>
            <dd>{{$supplier->emailav}}
              @if (!empty($supplier->emailav))
                  &nbsp;<a href="mailto:{{$supplier->emailav}}"><i class="btn btn-xs fa fa-envelope-o bg-red"></i></a>
              @endif
            </dd>

            <dt>{{ trans('supplier.emailInvoice') }}</dt>
            <dd>{{$supplier->emailpec}}
              @if (!empty($supplier->emailpec))
                  &nbsp;<a href="mailto:{{$supplier->emailpec}}"><i class="btn btn-xs fa fa-envelope-o bg-red"></i></a>
              @endif
            </dd>

          </dl>
        </div>

        {{-- <div class="tab-pane" id="List">
          <dl class="dl-horizontal">
            @if($supplier->gruppolist)
              <dt>Listino Gruppo fornitore</dt>
              <dd>
                <a type="button" class="btn btn-default btn-block" href="{{ route('listini::grpCli', [$supplier->gruppolist]) }}" >
                    {{$supplier->gruppolist}} - {{$supplier->grpCli->descrizion or ''}}
                </a>
              </dd>
            @endif
        
            <dt>Listino fornitore</dt>
            <dd>
              <a type="button" class="btn btn-default btn-block" href="{{ route('listini::idxCli', [$supplier->codice]) }}">
                  Listino Personalizzato
              </a>
            </dd>
        
            <hr>
        
          </dl>
        </div> --}}
      </div>
    </div>
  </div>

  <div class="col-lg-6">
    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">{{ trans('supplier.maps') }}</h3>
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

</div>
@if (!Auth::user()->hasRole('supplier'))
<div class="row">

  <div class="col-lg-6">
    @include('visit.partials.timelinesupplier', [
      'visits' => $visits,
      'codcli' => $supplier->codice,
      'dateNow' => $dateNow,
      ])
  </div>

  <div class="col-lg-6">
    <div class="box box-default collapsed-box">  {{-- collapsed-box --}}
      <div class="box-header with-border">
        <h3 class="box-title" data-widget="collapse">{{ trans('supplier.noteCli') }}</h3>
        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
        </div>
      </div>
      <div class="box-body">
        @if ($supplier->anagNote)
          <strong>{!! $supplier->anagNote->note !!}</strong>  
        @else
          <strong>{!! $supplier->note !!}</strong>          
        @endif
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
