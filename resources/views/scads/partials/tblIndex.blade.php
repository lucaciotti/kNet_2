<table class="table table-hover table-condensed dtTbls_light">
  <thead>
    <th>{{ trans('scad.datePay_condensed') }}</th>
    <th>{{ trans('scad.statusPayment') }}</th>
    <th>{{ trans('scad.numInvoice') }}</th>
    <th>{{ trans('scad.dateInvoice') }}</th>
    <th>{{ trans('scad.client') }}</th>
    <th>{{ trans('scad.typePayment') }}</th>
    <th>{{ trans('scad.valueToPay') }}</th>
    <th>{{ trans('scad.valuePayed') }}</th>
    <th>Prov. Maturate{{-- {{ trans('scad.valueToPay') }} --}}</th>
    <th>Prov. Liquidate{{-- {{ trans('scad.valuePayed') }} --}}</th>
  </thead>
  <tbody>
    @if($scads->count()>0)
      @foreach ($scads as $scad)
        @if(($scad->insoluto==1 || $scad->u_insoluto==1) && $scad->pagato==0)
        <tr class="danger">
        @elseif($scad->datascad < \Carbon\Carbon::now() && $scad->pagato==0)
        <tr class="warning">
        @else
        <tr>
        @endif
          <td>
            <span>{{$scad->datascad->format('Ymd')}}</span>
            {{-- <a href="{{ route('scad::detail', $scad->id ) }}"> {{ $scad->datascad->format('d-m-Y') }}</a> --}}
            {{ $scad->datascad->format('d-m-Y') }}
          </td>
          <td>
            @if($scad->pagato==1)
              {{ trans('scad.payedStatus') }}
            @elseif($scad->insoluto==1)
              {{ trans('scad.unsolvedStatus') }}
            @elseif($scad->u_insoluto==1)
              {{ trans('scad.defaultingStatus') }}
            @else

            @endif
          </td>
          <td>
            <a href="{{ route('doc::detail', $scad->id_doc ) }}">
              {{ $scad->tipomod }} {{ $scad->numfatt }}
            </a>
          </td>
          <td><span>{{$scad->datafatt->format('Ymd')}}</span>{{ $scad->datafatt->format('d-m-Y') }}</td>
          <td>
            @if($scad->client)
              <a href="{{ route('client::detail', $scad->codcf ) }}">
                {{ $scad->client->descrizion }} [{{$scad->codcf}}]
              </a>
            @endif
          </td>
          <td>
            {{ $scad->desc_pag }}
            {{-- @if($scad->idragg>0)
              <a href="#"> {{ trans('scad.merged') }}</a>
            @endif --}}
          </td>
          <td>{{ $scad->impeffval }}</td>
          <td>{{ $scad->importopag }}</td>
          <td>
            @if($scad->pagato==1 && $scad->liquidate==0)
              {{ $scad->impprovlit }}
            @else
              0
            @endif
          </td>
          <td>{{  $scad->impprovliq }}</td>
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
