<table>
  <thead>
    <tr>
      <th colspan="3">&nbsp;</th>
      <th colspan="2" style="text-align: center;">Quantità Venduta</th>
      <th colspan="1" style="text-align: center;"></th>
    </tr>
    <tr>
      <th style="text-align: center;">Codice</th>
      <th style="text-align: center;">Descrizione</th>
      {{-- <th style="text-align: center;">Gruppo Prodotto</th> --}}
      <th style="text-align: center;">U.M.</th>

      <th style="text-align: center;">{{ $thisYear }}</th>
      <th style="text-align: center;">{{ $thisYear-1 }}</th>
      <th style="text-align: center;">% {{ trans('stFatt.missing') }}</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($AbcProds as $abc)
      <tr class="fontsmall">
        <td>{{ $abc->articolo }}</td>
        <td>{{ $abc->product->descrizion or '' }}</td>
        {{-- <td class="fontsmall">{{ $abc->gruppo or '' }} - {{ $abc->grpProd->descrizion or '' }}</td> --}}
        <td>{{ $abc->product->unmisura or 'PZ' }}</td>

        <td class="centered">{{ $abc->qtaN }}</td>
        <td class="centered">{{ $abc->qtaN1 }}</td>
        <td class="centered">{{ ($abc->qtaN - $abc->qtaN1) }}</td>
      </tr>
    @endforeach
  </tbody>
</table>