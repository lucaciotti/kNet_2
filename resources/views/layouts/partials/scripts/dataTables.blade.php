<!-- DataTables -->
<link href="{{ asset('/plugins/datatables/dataTables.bootstrap.min.css') }}" rel="stylesheet" type="text/css" />

<script src="{{ asset('/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('/plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
<script src='https://cdn.datatables.net/plug-ins/1.10.19/sorting/currency.js'></script>

<script>
  $(function () {
    $(".dtTbls_full").DataTable({
      "iDisplayLength": 25,
      "aaSorting": [],
    });
    $('.dtTbls_light').DataTable({
      "iDisplayLength": 25,
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "aaSorting": []
    });
    $(".dtTbls_full_Tot").DataTable({
      "iDisplayLength": 25,
      "paging": true,
      "lengthChange": true,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "aaSorting": [],
      "footerCallback": function ( row, data, start, end, display ) {
          var api = this.api(), data;

          // Remove the formatting to get integer data for summation
          var intVal = function ( i ) {
              return typeof i === 'string' ?
                  i.replace(/[\$,]/g, '')*1 :
                  typeof i === 'number' ?
                      i : 0;
          };

          // Total over all pages
          total = api
              .column( 5 )
              .data()
              .reduce( function (a, b) {
                  return intVal(a) + intVal(b);
              }, 0 );

          // Total over this page
          pageTotal = api
              .column( 5, { page: 'current'} )
              .data()
              .reduce( function (a, b) {
                  return intVal(a) + intVal(b);
              }, 0 );

          // Update footer
          $( api.column( 5 ).footer() ).html(
              pageTotal.toFixed(2) +' €'              
          );
      }
    });
    $('.dtTbls_total').DataTable({
      "iDisplayLength": 15,
      "paging": true,
      "lengthChange": true,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "aaSorting": [],
      "footerCallback": function ( row, data, start, end, display ) {
          var api = this.api(), data;

          // Remove the formatting to get integer data for summation
          var intVal = function ( i ) {
              return typeof i === 'string' ?
                  i.replace(/[\$,]/g, '')*1 :
                  typeof i === 'number' ?
                      i : 0;
          };

          // Total over all pages
          total = api
              .column( 7 )
              .data()
              .reduce( function (a, b) {
                  return intVal(a) + intVal(b);
              }, 0 );

          // Total over this page
          pageTotal = api
              .column( 7, { page: 'current'} )
              .data()
              .reduce( function (a, b) {
                  return intVal(a) + intVal(b);
              }, 0 );

          // Update footer
          console.log(pageTotal);
          if(api.page.info().page == api.page.info().pages-1){
            $( api.column( 7 ).footer() ).html(
                total.toFixed(2) +' €'//+' ['+ total +' € Tot.Doc.]'
            );
          } else {
            $( api.column( 7 ).footer() ).html(
                "<i class='fa fa-arrow-right'> Last Page</i> "
            );
          }
      }
    });
    $('.dtTbls_stat').DataTable({
        "iDisplayLength": 25,
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        'aoColumnDefs': [
            { 'sType': 'currency', 'aTargets': [1] } // In this case 5th column will be sorted on currency basis.
        ],
        // "aaSorting": [[0, "desc"]],
        // "bStateSave": false
    });
  });
</script>

<style>
.dtTbls_light span {
    display:none;
}
.dtTbls_full span {
    display:none;
}
.dtTbls_full_Tot span {
    display:none;
}
.dtTbls_total span {
    display:none;
}
</style>
