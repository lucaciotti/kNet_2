{{-- @if($tipomodulo)
  <table class="table table-hover table-condensed dtTbls_full_Tot" id="listDocs">
@else --}}
  <table class="table table-hover table-condensed dtTbls_full" id="listDocs">
{{-- @endif --}}
  <thead>
    <th>Tipo</th>
    <th>Data</th>
    <th>Ragione Sociale</th>
    <th>Relatore</th>
    <th>Descrizione</th>
    <th></th>
  </thead>
  
  <tbody>
    @foreach ($visits as $visit)
      @if($visit->rubri_id>0)
      <tr class="warning">
      @else
      <tr>
      @endif
        <td>{{ $visit->tipoVisit }}</td>
        <td width='100'><span>{{$visit->data->format('Ymd')}}</span>{{ $visit->data->format('d-m-Y') }}</td>
        @if($visit->client)
        <td><a href="{{ route('client::detail', $visit->codicecf ) }}">{{ $visit->client->descrizion }} [{{ $visit->codicecf }}]</a></td>
        @else
          @if($visit->supplier)
          <td><a href="{{ route('supplier::detail', $visit->codicecf ) }}">{{ $visit->supplier->descrizion }} [{{ $visit->codicecf
              }}]</a></td>
          @else
          <td><a href="{{ route('rubri::detail', $visit->rubri_id ?? 0 ) }}">{{ $visit->rubri->descrizion ?? 'Error' }} [ Potenziale Cliente ]</a></td>
          @endif
        @endif
        <td>{{ $visit->user->name }}</td>
        <td>{{ $visit->descrizione }}</td>
        <td width='150'>
          @if($visit->supplier)
            <a href='{{ route('visit::editSupplier', ['id'=>$visit->id]) }}' class="btn btn-primary btn-sm" target="_blank">Edit</a>
          @else
            <a href='{{ route('visit::edit', ['id'=>$visit->id]) }}' class="btn btn-primary btn-sm" target="_blank">Edit</a>
          @endif
          @if($visit->client)
          <a class="btn btn-sm btn-default" href="{{ route('visit::show', $visit->codicecf ) }}" target="_blank">
          @else
            @if($visit->supplier)
            <a class="btn btn-sm btn-default" href="{{ route('visit::showSupplier', $visit->codicecf ) }}" target="_blank">
            @else
              <a class="btn btn-sm btn-default" href="{{ route('visit::showRubri', $visit->rubri_id ?? 0 ) }}" target="_blank">
            @endif
          @endif
            <i class="fa fa-external-link text-danger"></i>
          </a>
          <form action="{{ route('visit::delete', ['id'=>$visit->id]) }}" method="post" style="display: inline;" id="delete_visit_form" onsubmit="return deleteVisit()">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <button type='submit' class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
          </form>
        </td>
        {{-- <td>
          <a href="{{ route('doc::detail', $doc->id) }}"> {{ $doc->numerodoc }} </a>
        </td>
        <td><span>{{$doc->datadoc->format('Ymd')}}</span>{{ $doc->datadoc->format('d-m-Y') }}</td>
        <td>{{ $doc->client->descrizion }} [{{ $doc->codicecf }}]</td>
        <td>{{ $doc->numerodocf }}</td>
        <td>@if($doc->agent) {{ $doc->agent->descrizion }} @endif</td>
        <td>{{ $doc->totdoc }}</td>
        <td>
          <a class="btn-sm btn-default" href="{{ route('doc::downloadPDF', $doc->id ) }}" target="_blank">
            <i class="fa fa-file-pdf-o fa-lg text-danger"></i>
          </a>
        </td> --}}
      </tr>
    @endforeach
  </tbody>
</table>
{{--
@push('scripts')
    <script>
    $(document).ready(function() {
      $('#listDocs').DataTable( {
          "order": [[ 3, "desc" ]]
      } );
    } );
    </script>
@endpush --}}

@push('script-footer')
<script>
  function deleteVisit(){
    var result = confirm("Eliminare visita selezionata?");
    if (result) {
      return true;
    } else {
      return false;
    }
  };
</script>
@endpush