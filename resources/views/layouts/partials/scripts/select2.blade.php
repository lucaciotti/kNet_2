<!-- Select2 -->
{{-- <link href="{{ asset('/plugins_old/select2/select2.css') }}" rel="stylesheet" type="text/css" /> --}}
<link href="{{ asset('/plugins/my_select2.css') }}" rel="stylesheet" type="text/css" />

<!-- Select2 -->
<script src="{{ asset('/plugins/select2/js/select2.full.min.js') }}" type="text/javascript"></script>

<script>
    $(function () {
        $('.select2').select2();
      });
</script>
