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
            <th style="text-align: center;">Valore</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($listDoc as $doc)
        <tr>
            <td style="text-align: left;"><b>{{ $doc->client->descrizion }}</b> [<a
                    href="{{ route('client::detail', $doc->client->codice ) }}" target="_blank">{{ $doc->client->codice }}</a>]</td>
            <td>{{ $doc->client->detZona->descrizion }}</td>
            <td>{{ $doc->client->detSect->descrizion }}</td>
            <td> {{ $doc->tipodoc }} {{ $doc->numerodoc }} </td>
            <td> {{ $doc->datadoc->format('d/m/Y') }} </td>
            <td> {{ currency($doc->totdoc) }} </td>
        </tr>
        @endforeach
    </tbody>
</table>