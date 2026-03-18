<table class="table table-hover table-striped dtTbls_portfolio" id="portfolioTbl" style="text-align: center;">
    <col width='22%'>
    <col width='15%'>
    <col width='12%'>
    <col width='10%'>
    <col width='10%'>
    <col width='10%'>
    <thead>
        <tr>
            <th colspan="3">&nbsp;</th>
            <th colspan="3" style="text-align: center;">
                @if($cumulativo) {{ \Carbon\Carbon::createFromDate(null, 1, null)->format('F')}} - @endif
                {{ \Carbon\Carbon::createFromDate(null, $mese, null)->format('F')}} {{ $thisYear }}</th>
        </tr>
        <tr>
            <th colspan="3">&nbsp;</th>
            <th style="text-align: center;">Doc.</th>
            <th style="text-align: center;">Data Doc.</th>
            <th style="text-align: center;">Valore Residuo</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($listDoc as $doc)
        <tr>
            <td style="text-align: left;"><b>{{ $doc['head']->client->descrizion }}</b> [<a
                    href="{{ route('client::detail', $doc['head']->client->codice ) }}" target="_blank">{{ $doc['head']->client->codice }}</a>]</td>
            <td>{{ $doc['head']->client->detZona->descrizion }}</td>
            <td>{{ $doc['head']->client->detSect->descrizion }}</td>
            <td> {{ $doc['head']->tipodoc }} {{ $doc['head']->numerodoc }} </td>
            <td> {{ $doc['head']->datadoc->format('d/m/Y') }} </td>
            <td> {{ currency($doc['rows']->sum('totRowNetPrice')) }} </td>
        </tr>
        @endforeach
    </tbody>
</table>