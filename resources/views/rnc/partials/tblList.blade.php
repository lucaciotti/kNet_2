<table class="table table-hover table-condensed dtTbls_light">
  <col width="50">
  <col width="50">
  <col width="150">
  <col width="50">
  <col width="50">
  <col width="10">
  <col width="50">
  <thead>
    <th># RNC</th>
    <th>Data Apertura</th>
    <th>Cliente</th>
    <th>Tipologia</th>
    <th>Causa</th>
    <th>Gravità</th>
    {{-- <th>Doc.</th> --}}
    <th>Data Chiusura</th>
  </thead>
  <tbody>
    @if($rncs->count()>0)
      @foreach ($rncs as $rnc)
        <tr>
          <td>
            <a href="{{ route('rnc::detail', $rnc->id ) }}">
              {{ $rnc->nummov }} / {{ $rnc->esercizio }}
            </a>
          </td>
          <td>
            <span>{{$rnc->datareg->format('Ymd')}}</span>
            {{ $rnc->datareg->format('d-m-Y') }}
          </td>
          <td>
            @if($rnc->client)
            <a href="{{ route('client::detail', $rnc->codfor ) }}">
              {{ $rnc->client->descrizion }} [{{$rnc->codfor}}]
            </a>
            @endif
          </td>
          <td>{{ ucfirst(strtolower($rnc->rncTipoRapp->descrizion ?? '')) }}</td>
          <td>{{ ucfirst(strtolower($rnc->rncCausa->descrizion ?? '')) }}</td>
          <td>{{ $rnc->severity }}</td>
            {{-- <a href="{{ route('doc::detail', $rnc->id_doc ) }}">
              {{ $rnc->tipomod }} {{ $rnc->numfatt }}
            </a> --}}
            {{-- <td>{{ $rnc->doctip }} {{ $rnc->docnmov }}/{{ $rnc->doceser }}</td> --}}
          <td>
            @if ($rnc->dataend)
            <span>{{$rnc->dataend->format('Ymd')}}</span>
            {{ $rnc->dataend->format('d-m-Y') }}                
            @endif
          </td>
        </tr>
      @endforeach
    @endif
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
