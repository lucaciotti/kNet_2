<table class="table table-hover table-condensed dtTbls_light" id="listProdTable">
    <thead>
        <tr>
            <th colspan="5">&nbsp;</th>
            <th colspan="6" style="text-align: center;">Custom Price List</th>
        </tr>
        <tr>
            <th style="text-align: center;">Cod. Art.</th>
            <th style="text-align: center;">Descrizione</th>
            <th style="text-align: center;">Gruppo Prodotto</th>
            <th style="text-align: center;">U.M.</th>
            {{-- <th style="text-align: center;">Listino</th> --}}
            <th colspan="1">|</th>

            <th style="text-align: center;">Prezzo</th>
            <th style="text-align: center;">Sconto</th>
            <th style="text-align: center;">Fine Validità</th>
            <th colspan="1">|</th>
            <th style="text-align: center;">Listino Lordo</th>
            <th style="text-align: center;">Listino Netto</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($ListProds as $list)
        <tr>
            
            <td>{{ $list->codicearti }}</td>
            
            <td>{{ $list->product->descrizion or '' }}</td>
            @php
            $gruppo = ($list->product) ? $list->product->gruppo : '';
            $gruppoDesc = ($gruppo) ? (($list->product->grpProd) ? $list->product->grpProd->descrizion : '') : '';
            @endphp
            <td>{{ $gruppo or '' }} - {{ $gruppoDesc or '' }}</td>
            <td>{{ $list->product->unmisura or 'PZ' }}</td>
            {{-- <td>{{ currency($list->product->listino) }}</td> --}}
            <th colspan="1">|</th>

            <td>{{ ($list->prezzo>0) ? currency($list->prezzo) : '-' }}</td>
            <td style="text-align: center;">{{ $list->sconto }}</td>
            @php
            $prezzo = ($list->prezzo>0) ? $list->prezzo : $list->product->listino;
            $dateSpan = ($list->datafine) ? $list->datafine->format('Ymd') : new Carbon\Carbon(2019,1,1);
            $dateCol = ($list->datafine) ? $list->datafine->format('d-m-Y') : '';
            @endphp
            <td style="text-align: center;">{{ $dateCol}}</td>
            <th colspan="1">|</th>
            
            <td>
                {{ ($list->product) ? currency($list->product->listino) : 0 }}
            </td>
            
            <td>
                {{currency( knet\Helpers\Utils::scontaDel($prezzo, $list->sconto, 2)) }}
                @if($list->u_noprzmin)
                <i class="fa fa-exclamation-triangle" style="color:darkred"></i>
                @endif
            </td>

        </tr>
        @endforeach
    </tbody>
</table>