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

{{-- <script>
  $.fn.select2.amd.require([
    'select2/utils',
    'select2/dropdown',
    'select2/dropdown/attachBody'
    ], function (Utils, Dropdown, AttachBody) {
        function SelectAll() { }
  
        SelectAll.prototype.render = function (decorated) {
          var $rendered = decorated.call(this);
          var self = this;

          var $selectAll = $(
            '<button type="button">Select All</button>'
          );

          $rendered.find('.select2-dropdown').prepend($selectAll);

          $selectAll.on('click', function (e) {
            var $results = $rendered.find('.select2-results__option[aria-selected=false]');

            // Get all results that aren't selected
            $results.each(function () {
              var $result = $(this);

              // Get the data object for it
              var data = $result.data('data');
              
              // Trigger the select event
              self.trigger('select', {
                data: data
              });
            });
    
            self.trigger('close');
          });
  
          return $rendered;
        };
  
        $(".select2All").select2({
          placeholder: "Select Option(s)...",
          dropdownAdapter: Utils.Decorate(
          Utils.Decorate(
          Dropdown,
          AttachBody
          ),
          SelectAll
          ),
        });
  });
</script> --}}
