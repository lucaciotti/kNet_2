@extends('_exports.pdf.masterPage.masterPdf')

@section('pdf-main')
    <p class="page">

        <div class="row">
            <div class="contentTitle">Turnover Situation</div>

            @include('_exports.pdf.schedaFat.tblTotAg', [
                'fat_TY' => $fat_TY,
                'fat_PY' => $fat_PY,
                'prevMonth' => $prevMonth,
                'thisYear' => $thisYear,
                'prevYear' => $prevYear
            ])

        </div>

        <div><hr class="dividerPage"></div>

    </p>
    
    <p class="page">
        <div class="row">
            <div class="contentTitle">Zone Turnover Situation</div>

            @include('_exports.pdf.schedaFat.tblZone', [
                    'fatZone' => $fatZone,
                    'fatTot' => $fatTot,
                    'thisYear' => $thisYear,
                    'prevYear' => $prevYear
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