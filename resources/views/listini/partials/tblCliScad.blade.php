<table class="table table-hover table-condensed dtTbls_full" id="listCliScad">
    <thead>
        <tr>
            <th style="text-align: center;">Cod. Cli.</th>
            <th style="text-align: center;">Descrizione</th>

            <th style="text-align: center;">Link Listino</th>
            
            <th colspan="1">|</th>

            <th style="text-align: center;">n° Cod.Art.</th>

            <th colspan="1">|</th>
            <th style="text-align: center;">n° Gruppo Prod.</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($customers as $customer)
        <tr>
            <td><a href="{{ route('client::detail', ['codice'=>$customer->codclifor]) }}" target="_blank"> {{ $customer->codclifor }} </a></td>
            <td>{{ $customer->client->descrizion or '' }}</td>
            
            <td><a href="{{ route('listini::idxCli', $customer->codclifor) }}" target="_blank"> Listino Personalizzato </a></td>
            
            <th colspan="1">|</th>

            <td style="text-align: center;">{{ $customer->nCodArt }}</td>
           
            <th colspan="1">|</th>
            <td style="text-align: center;">
                {{ $customer->nGrpMag }}</td>

        </tr>
        @endforeach
    </tbody>
</table>