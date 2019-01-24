<!-- iCheck -->
{{--
<link href="{{ asset('/plugins/iCheck/square/_all.css') }}" rel="stylesheet" type="text/css" /> --}}
<link href="{{ asset('/plugins/iCheck/flat/_all.css') }}" rel="stylesheet" type="text/css" />

<!-- iCheck -->
<script src="{{ asset('/plugins/iCheck/icheck.min.js') }}" type="text/javascript"></script>

<script>
    $(function () {
        $('input').iCheck({
            checkboxClass: 'icheckbox_flat-aero',
            radioClass: 'iradio_flat-aero',
            increaseArea: '20%' // optional
        });

        $('#noDate').on('ifChecked', function(event){
          // alert(event.type + ' callback');
          $('.daterange-btn span').html('');
          $('.daterange-btn').prop('disabled', true);
          $('input[name="startDate"]').val('');
          $('input[name="endDate"]').val('');
        }).on('ifUnchecked',  function(event){
          // alert(event.type + ' callback');
          $('.daterange-btn span').html('Seleziona Data');
          $('.daterange-btn').prop('disabled', false);
        });
    });
</script>
