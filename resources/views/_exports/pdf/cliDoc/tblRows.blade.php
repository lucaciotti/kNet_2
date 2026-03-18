<table class="table table-hover table-condensed" id="cliDoc" style="text-align: center;">
    <col width="50">
    <col width="30">
    <col width="100">
    <col width="250">
    <col width="100">
    <col width="80">
    {{-- @if($head->tipomodulo=='O')
    <col width="80">
    @endif --}}
    <col width="80">
    <col width="80">
    @if($head->tipomodulo=='O')
    <col width="100">
    <col width="100">
    @endif
    <col width="100">
    @if (!in_array(RedisUser::get('role'), ['client']) && ($head->tipomodulo == 'F' || $head->tipomodulo == 'N' || $head->tipodoc == 'PP'))
    <col width="80">
    <col width="80">
    @endif
    <thead>
        <th>#Doc</th>
        <th>DataDoc</th>
        <th>{{ trans('doc.codeArt') }}</th>
        <th>{{ trans('doc.descArt') }}</th>
        <th>{{ trans('doc.codeLot') }}</th>
        <th>{{ trans('doc.quantity_condensed') }} / Res.</th>
        {{-- @if($head->tipomodulo=='O' || $head->tipomodulo=='B') --}}
        {{-- <th>{{ trans('doc.quantity_residual') }}</th> --}}
        {{-- @endif --}}
        <th>{{ trans('doc.unitPrice') }}</th>
        <th>{{ trans('doc.discount') }}</th>
        @if($head->tipomodulo=='O')
        <th>{{ trans('doc.datePronto_condensed') }}</th>
        <th>{{ trans('doc.dateDispach_condensed') }}</th>
        @endif
        <th>{{ trans('doc.totPrice') }}</th>
        @if (!in_array(RedisUser::get('role'), ['client']) && ($head->tipomodulo == 'F' || $head->tipomodulo == 'N' || $head->tipodoc == 'PP'))
        <th>Provv %</th>
        <th>Provv €</th>
        @endif
    </thead>

    <tbody>
        @php
            $totMerce=0;
            $totOmaggio=0;
            // $totIvaOmaggio=0;
            $totProvv=0;
            $provvParziale=0;
            $provv="";
        @endphp

        @foreach ($rows as $row)
        <tr>
            <td style="text-align: left;">{{ $head->tipodoc }}  {{ $head->numerodoc }}</td>
            <td>{{ $head->datadoc->format('d/m/Y') }}</td>
            <td style="text-align: left;">{{ $row->codicearti }}</td>
            <td style="text-align: left;">
                @if (RedisUser::get('lang')=='en' && $row->descrLangEN)
                    {{ $row->descrLangEN->descrizion }}   
                @else 
                    {{ $row->descrizion }}
                @endif                    
            </td>
            <td style="text-align: center;">{{ $row->lotto }}</td>
            <td style="text-align: center;">
                @if (!empty($row->codicearti)) <small>{{ $row->quantitare }} /</small> {{ $row->quantita }} {{ $row->unmisura }} @endif
            </td>
            {{-- @if($head->tipomodulo=='O' || $head->tipomodulo=='B')
                <td style="text-align: center;">{{ $row->quantitare }}</td>
            @endif --}}
            @if($row->ommerce)
                <td colspan="2" style="text-align: center;"><strong> FREE OF CHARGE</strong></td>
            @else
                <td style="text-align: right;">{{ currency($row->prezzoun) }}</td>
                {{-- <td style="text-align: center;">{{ $row->sconti }} [{{ $head->sconti }}, {{ $head->scontocass }}]</td> --}}
                <td style="text-align: center;">{{ $row->sconti }}</td>
            @endif
            @if($head->tipomodulo=='O')
                <td style="text-align: center;">
                    @if($row->u_dtpronto) {{ $row->u_dtpronto->format('d-m-Y') }} @endif
                </td>
                <td style="text-align: center;">
                    @if($row->dataconseg) {{ $row->dataconseg->format('d-m-Y') }} @endif
                </td>
            @endif
            <td style="text-align: right;"><small>{{ currency($row->totRowNetPrice) }} / </small>{{ currency($row->prezzotot) }}</td>
            {{-- <td>{{ currency($row->totRowGrossPrice) }} [{{ currency($row->totRowNetPrice) }}]</td> --}}
            @php
                $totMerce=$totMerce+$row->totRowNetPrice;
                $totOmaggio=$totOmaggio+(($row->ommerce) ? $row->prezzotot : 0);
                // $totIvaOmaggio=$totIvaOmaggio+(($row->omiva) ? $row->prezzotot : 0);
                $provvParziale=($row->prezzotot>0) ? floatval(str_replace(",",".",$row->provv))/100*$row->prezzotot : 0;
                $totProvv=$totProvv+$provvParziale;
                $provv=($row->prezzotot>0) ? (($row->provv!="") ? $row->provv : "0") : "";
            @endphp
            @if (!in_array(RedisUser::get('role'), ['client']) && ($head->tipomodulo == 'F' || $head->tipomodulo == 'N' || $head->tipodoc == 'PP'))
                <td style="text-align: center;">
                    @if($row->prezzotot>0) {{ $provv }} % @endif
                </td>
                <td style="text-align: right;">
                    @if($row->prezzotot>0) {{ currency($provvParziale) }}@endif
                </td>
            @endif
        </tr>
        @endforeach
    </tbody>

    <tfoot>
        <tr>
            <th 
                @if(($head->tipomodulo=='O')) 
                    colspan="10" 
                @else 
                    colspan="8" 
                @endif style="text-align:right">
                Total:
            </th>
            <th style="text-align: right;">
                {{ currency($totMerce) }}
            </th>
            @if (!in_array(RedisUser::get('role'), ['client']) && ($head->tipomodulo == 'F' || $head->tipomodulo == 'N' || $head->tipodoc == 'PP'))
                <th></th>
                <th style="text-align: right;">{{ currency($totProvv) }}</th>
            @endif
        </tr>
    </tfoot>
    
    @if ($head->sconti)
    <tfoot>
        <tr>
            <th @if($head->tipomodulo=='O') colspan="10" @else colspan="8" @endif style="text-align:right">{{
                trans('doc.scontoMerce') }}: {{$head->sconti}} %</th>
            <th style="text-align: right;">{{ currency($head->totmerce) }}</th>
            @if (!in_array(RedisUser::get('role'), ['client']) && ($head->tipomodulo == 'F' || $head->tipomodulo == 'N' || $head->tipodoc == 'PP'))
            <th></th>
            <th style="text-align: right;">{{ currency($totProvv-floatval($head->sconti)/100*$totProvv) }}</th>
            @endif
        </tr>
    </tfoot>
    @endif
    
    @if ($totOmaggio>0)
    <tfoot>
        <tr>
            <th @if($head->tipomodulo=='O') colspan="10" @else colspan="8" @endif style="text-align:right">Total Value of
                Goods Free of Charge: </th>
            <th style="text-align: right;">{{ currency(-$totOmaggio) }}</th>
            @if (!in_array(RedisUser::get('role'), ['client']) && ($head->tipomodulo == 'F' || $head->tipomodulo == 'N' ||
            $head->tipodoc == 'PP'))
            <th></th>
            <th></th>
            @endif
        </tr>
    </tfoot>
    @endif

</table>