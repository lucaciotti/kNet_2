<table >
  <col width="30">
  <col width="70">
  <col width="50">
  <col width="50">
  <col width="70">
  <col width="200">
  <col width="100">
  <col width="80">
  <col width="80">
  <col width="80">
  <col width="80">
  <col width="80">
  <thead>
    <tr style="text-align: center;">
      <th>Periodo</th>
      <th>{{ trans('scad.datePay_condensed') }}</th>
      <th>{{ trans('scad.statusPayment') }}</th>
      <th>{{ trans('scad.numInvoice') }}</th>
      <th>{{ trans('scad.dateInvoice') }}</th>
      <th>{{ trans('scad.client') }}</th>
      <th>{{ trans('scad.typePayment') }}</th>
      <th>{{ trans('scad.valueToPay') }}</th>
      <th>{{ trans('scad.valuePayed') }}</th>
      <th>Prov. Da Maturare{{-- {{ trans('scad.valueToPay') }} --}}</th>
      <th>Prov. Maturate{{-- {{ trans('scad.valueToPay') }} --}}</th>
      <th>Prov. Liquidate{{-- {{ trans('scad.valuePayed') }} --}}</th>
    </tr>
  </thead>
    @if($provv->count()>0)
          @php
            $granTotCalc = 0;
            $granTotMat = 0;
            $granTotLiq = 0;
            $totCalcolato = 0;
            $totMaturato = 0;
            $totLiquidate = 0;
          @endphp
    <tbody>
      @foreach ($provv as $periodo)
        <tbody>
            <tr class="clickable" data-toggle="collapse" data-target="#mese-{{$periodo->first()->Mese}}" aria-expanded="false" aria-controls="#zona-{{$periodo->first()->Mese}}">
            <td colspan="12"><h3>--> {{__('_monthList.month_'.$periodo->first()->Mese)}}</h3><hr></td>
            </tr>
        </tbody>
        <tbody id="mese-{{$periodo->first()->Mese}}" class="collapse">
        @foreach($periodo as $scad)
          @php
            $totCalcolato += ($scad->pagato==0 && $scad->liquidate==0) ? $scad->impprovlit : 0;
            $totMaturato += ($scad->pagato==1 && $scad->liquidate==0) ? $scad->impprovlit : 0;
            $totLiquidate += $scad->impprovliq ;
          @endphp
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
                <a href="{{ route('doc::downloadPDF', $scad->id_doc) }}"> {{ $scad->tipomod }} {{ $scad->numfatt }} </a>
            </td>
            <td style="text-align: center;">{{ $scad->datafatt->format('d-m-Y') }}</td>
            <td>
              @if($scad->client)
                  {{ htmlspecialchars(strtr(utf8_decode($scad->client->descrizion),'àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ','aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY')) }}
                  [{{$scad->codcf }}]
              @endif
            </td>
            <td style="text-align: center;">
              {{ $scad->desc_pag }}
              {{-- {{ trans('scad.merged') }} --}}
            </td>
            <td style="text-align: right;">{{ currency($scad->impeffval) }}</td>
            <td style="text-align: right;">{{ currency($scad->importopag) }}</td>
            @if($scad->pagato==0 && $scad->liquidate==0)
              @if($scad->insoluto==1 || $scad->u_insoluto==1)
                <td style="text-align: right;" class="red">
                  {{ currency($scad->impprovlit) }}
                </td>
              @else
                <td style="text-align: right;">
                  {{ currency($scad->impprovlit) }}
                </td>
              @endif
            @else
              <td style="text-align: right;">
                {{ currency(0)}}
              </td>                
            @endif
            @if($scad->pagato==1 && $scad->liquidate==0)
              <td style="text-align: right;" class="orange">
                {{ currency($scad->impprovlit) }}
              </td>
            @else
              <td style="text-align: right;">
                {{ currency(0)}}
              </td>
            @endif
            @if($scad->liquidate==1)
              <td style="text-align: right;" class="green">{{ currency($scad->impprovliq) }}</td>
            @else
              <td style="text-align: right;">{{ currency($scad->impprovliq) }}</td>
            @endif
          </tr>
        @endforeach   
        </tbody>
        <tbody>
            <tr>
                <td></td>
                <td colspan="12"><hr></td>
            </tr>     
            <tr>
                <td colspan="5"></td>
                <td><h3>--> {{ strtoupper(trans('stFatt.granTot')) }} {{__('_monthList.month_'.$periodo->first()->Mese)}}</h3></td>
                <td colspan="3"></td>
                <td style="text-align: right;"><strong>{{ currency($totCalcolato) }}</strong></td>
                <td style="text-align: right;"><strong>{{ currency($totMaturato) }}</strong></td>
                <td style="text-align: right;"><strong>{{ currency($totLiquidate) }}</strong></td>
            </tr>   
        </tbody>
          @php
            $granTotCalc += $totCalcolato;
            $granTotMat += $totMaturato;
            $granTotLiq += $totLiquidate;
            $totCalcolato = 0;
            $totMaturato = 0;
            $totLiquidate = 0;
          @endphp
      @endforeach
        <tr>
            <td></td>
            <td colspan="12"><hr></td>
        </tr>
    </tbody>
    <tfoot>        
        <tr>
            <td colspan="5"></td>
            <td><h3>--> Gran {{ strtoupper(trans('stFatt.granTot')) }}</h3></td>
            <td colspan="3"></td>
            <td style="text-align: right;"><strong>{{ currency($granTotCalc) }}</strong></td>
            <td style="text-align: right;"><strong>{{ currency($granTotMat) }}</strong></td>
            <td style="text-align: right;"><strong>{{ currency($granTotLiq) }}</strong></td>
        </tr>
        <tr style="background-color: white;">
          <td colspan="6"></td>
          <td colspan="4" style="text-align:right;"><strong><i>de las cuales, comisiones:</i></strong></td>
          <td style="text-align: right;"><i><strong>{{ currency($granTotMat*0.8) }}</strong><i></td>
          <td style="text-align: right;"></td>
        </tr>
        <tr style="background-color: white;">
          <td colspan="6"></td>
          <td colspan="4" style="text-align:right;"><strong><i>por acuerdo de no competencia:</i></strong></td>
          <td style="text-align: right;"><i><strong>{{ currency($granTotMat*0.2) }}</strong><i></td>
          <td style="text-align: right;"></td>
        </tr>     
    </tfoot>
    @endif
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
