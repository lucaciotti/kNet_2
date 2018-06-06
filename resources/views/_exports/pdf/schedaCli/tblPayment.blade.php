<table>
  <thead>
    <th>{{ trans('scad.datePay_condensed') }}</th>
    <th>{{ trans('scad.numInvoice') }}</th>
    <th>{{ trans('scad.dateInvoice') }}</th>
    <th>{{ trans('scad.merged') }}?</th>
    <th>{{ trans('scad.valueToPay') }}</th>
    <th>{{ trans('scad.valuePayed') }}</th>
  </thead>
  <tbody>
    @if($scads->count()>0)
      @foreach ($scads as $scad)
        @if($scad->insoluto==1 || $scad->u_insoluto==1)
        <tr class="centered danger">
        @elseif($scad->datascad < \Carbon\Carbon::now())
        <tr class="centered warning">
        @else
        <tr class="centered">
        @endif
          <td>{{ $scad->datascad->format('d-m-Y') }}</td>
          <td>
              {{ $scad->tipomod }} {{ $scad->numfatt }}
          </td>
          <td>{{ $scad->datafatt->format('d-m-Y') }}</td>
          <td>@if($scad->idragg>0)
            {{ trans('scad.merged') }}
          @endif</td>
          <td>{{ $scad->impeffval }}</td>
          <td>{{ $scad->importopag }}</td>
        </tr>
      @endforeach
    @endif
  </tbody>
</table>
