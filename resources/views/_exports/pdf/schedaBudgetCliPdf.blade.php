@extends('_exports.pdf.masterPage.masterPdf')

@section('pdf-main')
    <p class="page">

        <div class="row">
            <div class="contentTitle">Situatione Budget Clienti - {{ $thisYear }}</div>

            <table>
              <col width="250">
              <col width="150">
              <col width="100">
              <col width="70">
              <col width="70">
              <col width="70">
              <col width="70">
              <col width="70">
              <col width="70">
              <col width="70">
              <thead>
                <tr style="text-align: center;">
                  <th>Cliente</th>
                  <th>Agente</th>
                  <th>Periodo</th>
                  <th>Tot. Fatturato</th>
                  <th>Obbiettivo Budget 1</th>
                  <th>Obbiettivo Budget 2</th>
                  <th>Obbiettivo Budget 3</th>
                  <th>Bonus %</th>
                  <th>Importo Premio</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($allCliBudgets as $cliBudget)
                  @php
                      $fattCli = $allFattCli->get($cliBudget->codice);
                      $valFattCli = $fattCli['totFat'] ?? 0;
                      $bonusCli = '';
                      $premioCli = null;
                      if($valFattCli>=$cliBudget->u_budg1){
                        $bonusCli =$cliBudget->u_budg1p;
                      }
                      if($valFattCli>=$cliBudget->u_budg2 && $cliBudget->u_budg2>0){
                        $bonusCli =$cliBudget->u_budg2p;
                      }
                      if($valFattCli>=$cliBudget->u_budg3 && $cliBudget->u_budg3>0){
                        $bonusCli =$cliBudget->u_budg3p;
                      }
                      if ($bonusCli != ''){
                        $premioCli = ($valFattCli * (float) $bonusCli)/100;
                      }
                  @endphp
                  <tr>
                    <td><strong>{{ $cliBudget->codice }} - {{ $cliBudget->client->descrizion }}</strong></td>
                    <td><strong>{{ $cliBudget->client->agente }} - {{ $cliBudget->client->agent->descrizion }}</strong></td>
                    <td>{{ $startDateFT->format('d/m/Y') }} - {{ $endDateFT->format('d/m/Y') }}</td>
                    <td>{{ currency($fattCli['totFat'] ?? 0) }}</td>
                    <td>{{ currency($cliBudget->u_budg1 ?? 0) }}</td>
                    <td>{{ currency($cliBudget->u_budg2 ?? 0) }}</td>
                    <td>{{ currency($cliBudget->u_budg3 ?? 0) }}</td>
                    @if ($bonusCli!='')
                    <td>{{ $bonusCli }} %</td>
                    @else
                    <td>-</td>                        
                    @endif
                    <td>{{ currency($premioCli ?? 0) }}</td>
                  </tr>
                @endforeach
              </tbody>
              {{-- <tbody>
                <tr>
                  <td></td>
                  <td colspan="12">
                    <hr>
                  </td>
                </tr>
                <tr>
                  <td colspan="5"></td>
                  <td>
                    <h3>--> {{ strtoupper(trans('stFatt.granTot')) }} {{__('_monthList.month_'.$periodo->first()->Mese)}}</h3>
                  </td>
                  <td colspan="3"></td>
                  <td style="text-align: right;"><strong>{{ currency($totCalcolato) }}</strong></td>
                  <td style="text-align: right;"><strong>{{ currency($totMaturato) }}</strong></td>
                  <td style="text-align: right;"><strong>{{ currency($totLiquidate) }}</strong></td>
                </tr>
              </tbody> --}}
            </table>

        </div>

        <div><hr class="dividerPage"></div>

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