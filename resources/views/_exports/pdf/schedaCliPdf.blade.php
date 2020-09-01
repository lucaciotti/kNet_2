@extends('_exports.pdf.masterPage.masterPdf')

@section('pdf-main')
    <p class="page">
        <div class="row">
            <span class="floatleft">
                <dl class="dl-horizontal">
                    <dt>{{ trans('client.descCli') }}</dt>
                    <dd>
                        <big><strong>{{$client->descrizion}}</strong></big><br>
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
                
                <hr class="divider">
                
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

                <hr class="divider">
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

                    <hr class="divider">

                    <dt>{{ trans('client.phone') }}</dt>
                    <dd>{{$client->telefono}}</dd>

                    <dt>{{ trans('client.fax') }}</dt>
                    <dd>{{$client->fax}}</dd>

                    <dt>{{ trans('client.phone2') }}</dt>
                    <dd>{{$client->telex}}</dd>

                    <hr class="divider">

                    <dt>{{ trans('client.email') }}</dt>
                    <dd>{{$client->email}}</dd>
                </dl>
            </span>
        </div>

        <div><hr class="dividerPage"></div>
        <div class="row">
            <div class="contentTitle">Turnover Situation</div>
            @include('_exports.pdf.schedaCli.tblFatt', [
            'fatturato' => $fatThisYear,
            'target' => $fatPrevYear,
            'prevMonth' => \Carbon\Carbon::now()->month,
            'thisYear' => $thisYear,
            'prevYear' => $prevYear
            ])
            {{-- <div class="chart" id="revenue-chart"></div> --}}
        </div>

        <div><hr class="dividerPage"></div>        
        <div class="row">
            <div class="contentTitle">{{ trans('client.paymentCli') }}</div>
            @include('_exports.pdf.schedaCli.tblPayment', $scads)
        </div>
   
        @if ($client->anagNote)
            <div><hr class="dividerPage"></div>
            <div class="row">
                <div class="contentTitle">Commercial Notes</div>
            
                <div class="box-body">
                    <strong>{!! $client->anagNote->note !!}</strong>
                </div>
            </div>
        @endif

        @if (!$visits->isEmpty())
            <div><hr class="dividerPage"></div>
            <div class="row">
                <div class="contentTitle">Agent Reports</div>
        
                @include('_exports.pdf.schedaCli.timeline', [
                'visits' => $visits,
                'codcli' => $client->codice,
                'dateNow' => $dateNow,
                ])
            </div>
        @endif    
    </p>
    
    <p class="page">
        @if (!$listProds->isEmpty())
            <div class="row">
                <div class="contentTitle">Custom Price List</div>
            
                @include('_exports.pdf.schedaCli.tblListProd', [
                'ListProds' => $listProds,
                'customer' => $client->codice,
                'customerDesc' => $client->descrizion,
                'noCli'=> false
                ])
            </div>
        @endif
        <div><hr class="dividerPage"></div>
        <div class="row">
            <div class="contentTitle">Abc Items</div>

            @include('_exports.pdf.schedaCli.tblAbc', [
                'AbcProds' => $AbcItems,
                'thisYear' => $thisYear,
                'prevYear' => $prevYear,
                'thisMonth' => $thisMonth
                ])
        </div>
    </p>
@endsection

{{-- @push('scripts')
  <!-- Morris.js charts -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
  <script src="{{ asset('/plugins/morris/morris.min.js') }}"></script>
  <script>
  $( document ).ready(function () {
    "use strict";
    // AREA CHART
    var months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
    var data = {!! $stats !!};
    var revenueLabel = "{!! trans('stFatt.revenue') !!}";
    var targetLabel = "{!! trans('stFatt.target') !!}";
    var config = {
      resize: true,
      data: data,
      xkey: 'm',
      ykeys: ['a', 'b'],
      labels: [revenueLabel, targetLabel],
      lineColors: ['#227a03', '#cd6402'],
      hideHover: 'auto',
      xLabels: 'month',
      xLabelFormat: function(x) { // <--- x.getMonth() returns valid index
        var month = months[x.getMonth()];
        return month;
      },
      dateFormat: function(x) {
        var month = months[new Date(x).getMonth()];
        return month;
      },
    };
    config.element = 'revenue-chart';
    var area = new Morris.Line(config);
  });
</script>
@endpush --}}