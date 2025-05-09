<table >
  <col width="10">
  <col width="80">
  <col width="50">
  <col width="50">
  <col width="80">
  <col width="200">
  <col width="100">
  <col width="80">
  <col width="80">
  {{-- <col width="100"> --}}
  <thead>
    <tr style="text-align: center;">
      {{-- <th></th> --}}
      <th colspan="2">{{ trans('scad.datePay_condensed') }}</th>
      <th>{{ trans('scad.statusPayment') }}</th>
      <th>{{ trans('scad.numInvoice') }}</th>
      <th>{{ trans('scad.dateInvoice') }}</th>
      <th>{{ trans('scad.client') }}</th>
      <th>{{ trans('scad.typePayment') }}</th>
      <th>{{ trans('scad.valueToPay') }}</th>
      <th>{{ trans('scad.valuePayed') }}</th>
      {{-- <th>Note</th> --}}
    </tr>
  </thead>
  <tbody>
    @if($scads->count()>0)
      @foreach ($scads as $periodo)
        <tbody>
            <tr class="clickable" data-toggle="collapse" data-target="#mese-{{$periodo->first()->Mese}}" aria-expanded="false" aria-controls="#zona-{{$periodo->first()->Mese}}">
            <td colspan="11"><h3>--> {{__('_monthList.month_'.$periodo->first()->Mese)}}</h3><hr></td>
            </tr>
        </tbody>
        <tbody id="mese-{{$periodo->first()->Mese}}" class="collapse">
        @foreach($periodo as $scad)
          @if($scad->liquidate==1)
          <tr>
          @else
          <tr>
          @endif
            <td></td>            
            <td style="text-align: center;">
              {{ $scad->datascad->format('d-m-Y') }}
            </td>
            <td style="text-align: center;">
              @if($scad->pagato==1)
                {{ trans('scad.payedStatus') }}
              @elseif($scad->insoluto==1)
                {{ trans('scad.unsolvedStatus') }}
              @elseif($scad->u_insoluto==1)
                {{ trans('scad.defaultingStatus') }}
              @else

              @endif
            </td>
            <td style="text-align: center;">
                {{ $scad->tipomod }} {{ $scad->numfatt }}
            </td>
            <td style="text-align: center;">{{ $scad->datafatt->format('d-m-Y') }}</td>
            <td>
              @if($scad->client)
                  {{ $scad->client->descrizion }} [{{$scad->codcf}}]
              @endif
            </td>
            <td style="text-align: center;">
              {{ $scad->desc_pag }}
              {{-- {{ trans('scad.merged') }} --}}
            </td>
            <td style="text-align: right;">{{ currency($scad->impeffval) }}</td>
            <td style="text-align: right;">{{ currency($scad->importopag) }}</td>
          </tr>
          @if (count($scad->storia)>0)
            @foreach ($scad->storia as $storia)
              <tr class='danger'>
                <td colspan="4" style="text-align: right;"><p style="padding: 10px;">
                --> NOTE<br>del {{ $storia->datareg->format('d-m-Y') }}: </p></td>
                <td colspan="5"><strong><p style="padding: 10px;">{!! $storia->note !!}</p></strong></td>
              </tr>
            @endforeach              
          @endif
        @endforeach   
        </tbody>
      @endforeach
    @endif
  </tbody>
</table>
