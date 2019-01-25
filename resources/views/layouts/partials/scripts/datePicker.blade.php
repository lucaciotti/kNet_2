<!-- DatePicket -->
<link href="{{ asset('/plugins/datepicker/css/bootstrap-datepicker3.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('/plugins/daterangepicker/daterangepicker.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('/plugins/timepicker/bootstrap-timepicker.min.css') }}" rel="stylesheet" type="text/css" />
<!-- datePicker -->
<script src="{{ asset('/plugins/moment/moment.min.js') }}" type="text/javascript"></script>

<script src="{{ asset('/plugins/datepicker/js/bootstrap-datepicker.js') }}" type="text/javascript"></script>
<script src="{{ asset('/plugins/daterangepicker/daterangepicker.js') }}" type="text/javascript"></script>
<script src="{{ asset('/plugins/timepicker/bootstrap-timepicker.min.js') }}" type="text/javascript"></script>

<script>
    $(function () {
      //Date range picker
      $('.daterange').daterangepicker();
      //Date range picker with time picker
      $('.daterange-time').daterangepicker({timePicker: true, timePickerIncrement: 30, format: 'MM/DD/YYYY h:mm A'});
      //Date range as a button
      $('.daterange-btn').daterangepicker(
          {
            ranges: {
              'Today': [moment(), moment()],
              'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
              'Last 7 Days': [moment().subtract(6, 'days'), moment()],
              'Last 30 Days': [moment().subtract(29, 'days'), moment()],
              'This Month': [moment().startOf('month'), moment().endOf('month')],
              'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            },
            // startDate: moment().subtract(29, 'days'),
            // endDate: moment()
          },
          function (start, end) {
            $('.daterange-btn span').html(start.format('D/MM/YYYY') + ' - ' + end.format('D/MM/YYYY'));
            $('input[name="startDate"]').val(start.format('D/MM/YYYY'));
            $('input[name="endDate"]').val(end.format('D/MM/YYYY'));
          }
      );

      function setDataRange(start, end) {
          $('.daterange-btn span').html(start.format('D/MM/YYYY') + ' - ' + end.format('D/MM/YYYY'));
          $('input[name="startDate"]').val(start.format('D/MM/YYYY'));
          $('input[name="endDate"]').val(end.format('D/MM/YYYY'));
      };

      function setVoidRange() {
        $('#noDate').iCheck('check', function(event){
          // alert(event.type + ' callback');
          $('.daterange-btn span').html('');
          $('.daterange-btn').prop('disabled', true);
          $('input[name="startDate"]').val('');
          $('input[name="endDate"]').val('');
        });
      };

      @if(isset($startDate) || isset($endDate))
        var start = moment('{{$startDate}}');
        var end = moment('{{$endDate}}');
        setDataRange(start, end);
      @else
        // $('.daterange-btn span').html('Seleziona Data');
        setVoidRange();
      @endif
      //Date picker
      $('.datepicker').datepicker({
        autoclose: true
      });
    });
</script>
