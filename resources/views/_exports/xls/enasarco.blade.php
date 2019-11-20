@php
$tipoAgente = ($ritana->tipoage == 1 ? "Monomandatario" : "Plurimandatario");
$sum_totfattura = 0;
$sum_compensi = 0;
$sum_impendit = 0;
$sum_prog = 0;
$sum_res = 0;
$sum_impenage = 0;
$sum_prog = 0;
$sum_res = $ritana->impmax;
@endphp
<table class="table table-hover table-striped" id="Enasarco" style="text-align: center;">
    <thead>
        <tr>
            <th colspan="2">Info generali</th>
            <th>Data doc.</th>
            <th>Numero</th>
            <th>Tot. Fattura</th>
            <th>Imponibile</th>
            <th>Progr. Imp.</th>
            <th>% Ditta</th>
            <th>Importo Ditta</th>
            <th>% Agente</th>
            <th>Importo Agente</th>
            <th>Progressivo</th>
            {{-- <th>Residuo</th> --}}
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Agente</td>
            <td>{{$user->codag}} - {{$user->agent->descrizion}}</td>
            <td colspan="10"></td>
        </tr>
        <tr>
            <td>Tipo Agente</td>
            <td>{{ $tipoAgente }}</td>
            <td colspan="10"></td>
        </tr>
        <tr>
            <td>Minimo Imponibile</td>
            <td>{{ currency($ritana->impmin) }}</td>
            <td colspan="10"></td>
        </tr>
        <tr>
            <td>Massimo Imponibile</td>
            <td>{{ currency($ritana->impmax) }}</td>
            <td colspan="10"></td>
        </tr>
        @foreach ($ritmov as $mov)
        @php
        $sum_totfattura += $mov->totfattura;
        $sum_compensi += $mov->compensi;
        $sum_impendit += $mov->impendit;
        $sum_prog += $mov->impendit;
        $sum_res -= $mov->impendit;
        $sum_impenage += (float) $mov->impenage;
        $sum_prog += $mov->impenage;
        $sum_res -= $mov->impenage;
        @endphp
        <tr>
            <td colspan="2"></td>
            <td>{{ $mov->ftdatadoc->format('d-m-Y') }}</td>
            <td>{{ $mov->ftnumdoc }} </td>
            <td>{{ currency($mov->totfattura) }}</td>
            <td>{{ currency($mov->compensi) }}</td>
            @if($sum_compensi > $ritana->impmax)
            <td class="danger">{{ currency($sum_compensi) }}</td>
            @else
            <td>{{ currency($sum_compensi) }}</td>
            @endif
            <td>{{ $mov->perendit }}</td>
            <td>{{ currency($mov->impendit) }}</td>
            <td>{{ $mov->perenage }}</td>
            <td>{{ currency($mov->impenage) }}</td>
            <td>{{ currency($sum_prog) }}</td>
            {{-- <td>{{ currency($sum_res) }}</td> --}}
        </tr>
        @endforeach
    </tbody>
    <tfoot class="bg-gray">
        <tr>
            <td colspan="2"></td>
            <td>TOTALE</td>
            <td>&nbsp;</td>
            <td>{{ currency($sum_totfattura) }}</td>
            <td>{{ currency($sum_compensi) }}</td>
            <td>{{ currency($sum_compensi) }}</td>
            <td>&nbsp;</td>
            <td>{{ currency($sum_impendit) }}</td>
            <td>&nbsp;</td>
            <td>{{ currency($sum_impenage) }}</td>
            <td>{{ currency($sum_prog) }}</td>
            {{-- <td>{{ currency($sum_res) }}</td> --}}
        </tr>
    </tfoot>
</table>