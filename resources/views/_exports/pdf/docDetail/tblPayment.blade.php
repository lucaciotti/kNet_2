<table>
    <col width="100">
    <col width="100">
    <col width="100">
    <col width="80">
    <col width="80">
    @if (!in_array(RedisUser::get('role'), ['client']))
        <col width="80">
        <col width="80">
        <col width="80">        
    @endif
    <thead>
        <tr style="text-align: center;">
            <th>{{ trans('scad.datePay_condensed') }}</th>
            <th>{{ trans('scad.statusPayment') }}</th>
            <th>{{ trans('scad.typePayment') }}</th>
            <th>{{ trans('scad.valueToPay') }}</th>
            <th>{{ trans('scad.valuePayed') }}</th>
            @if (!in_array(RedisUser::get('role'), ['client']))                
            <th>Prov. Da Maturare{{-- {{ trans('scad.valueToPay') }} --}}</th>
            <th>Prov. Maturate{{-- {{ trans('scad.valueToPay') }} --}}</th>
            <th>Prov. Liquidate{{-- {{ trans('scad.valuePayed') }} --}}</th>
            @endif
        </tr>
    </thead>
    <tbody>
        @if ($scads->count()>0)
        @php
            $totScad=0;
            $totPag=0;
            $totNotMat=0;
            $totMat=0;
            $totLiq=0;
        @endphp
            @foreach ($scads as $scad)
                <tr>
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
                        {{ $scad->desc_pag }}
                        {{-- {{ trans('scad.merged') }} --}}
                    </td>
                    <td style="text-align: right;">{{ currency($scad->impeffval) }}</td>
                    <td style="text-align: right;">{{ currency($scad->importopag) }}</td>
                
                    @if (!in_array(RedisUser::get('role'), ['client']))
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
                    @endif
                </tr>        
                @php
                    $totScad=$totScad+$scad->impeffval;
                    $totPag=$totPag+$scad->importopag;
                    $totNotMat=$totNotMat+(($scad->pagato==0 && $scad->liquidate==0) ? $scad->impprovlit : 0);
                    $totMat=$totMat+(($scad->pagato==1 && $scad->liquidate==0) ? $scad->impprovlit : 0);
                    $totLiq=$totLiq+(($scad->liquidate==1) ? $scad->impprovlit : 0);  
                @endphp        
            @endforeach
        @endif
    </tbody>
    <tfoot>
        <tr>
            <th colspan="3" style="text-align:right">Total:</th>
            <th style="text-align: right;">{{ currency($totScad) }}</th>
            <th style="text-align: right;">{{ currency($totPag) }}</th>
            @if (!in_array(RedisUser::get('role'), ['client']))
            <th style="text-align: right;">{{ currency($totNotMat) }}</th>
            <th style="text-align: right;">{{ currency($totMat) }}</th>
            <th style="text-align: right;">{{ currency($totLiq) }}</th>
            @endif
        </tr>
    </tfoot>
</table>