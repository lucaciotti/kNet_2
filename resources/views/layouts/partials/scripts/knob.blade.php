<!-- Morris charts -->
<link rel="stylesheet" href="{{ asset('/plugins/morris/morris.css') }}">
<script src="{{ asset('/plugins/jquery-knob/jquery.knob.min.js') }}"></script>
<!-- Morris.js charts -->
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script> --}}
<script src="{{ asset('/plugins/morris/morris.min.js') }}"></script>

<script src="{{ asset('/plugins/raphael/raphael.min.js') }}"></script>

<script>
    $( document ).ready(function () {
    /* jQueryKnob */
    $(".knob").knob({
      /*change : function (value) {
       //console.log("change : " + value);
       },
       release : function (value) {
       console.log("release : " + value);
       },
       cancel : function () {
       console.log("cancel : " + this.value);
       },*/
      draw: function () {}
    });
    /* END JQUERY KNOB */

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