<table class="table table-hover table-condensed dtTbls_full" id="statAbcTable">
  <thead>
    <tr>
      <th colspan="4">&nbsp;</th>
      <th colspan="2" style="text-align: center;">Quantità Venduta</th>
      <th colspan="1" style="text-align: center;"></th>
    </tr>
    <tr>
      <th style="text-align: center;">Codice</th>
      <th style="text-align: center;">Descrizione</th>
      <th style="text-align: center;">Gruppo Prodotto</th>
      <th style="text-align: center;">U.M.</th>

      <th style="text-align: center;">{{ $thisYear }}</th>
      <th style="text-align: center;">{{ $prevYear }}</th>
      <th style="text-align: center;">Delta Qta</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($AbcProds as $abc)
      <tr>
        <td>{{ $abc->articolo }}</td>
        <td>{{ $abc->product->descrizion or '' }}</td>
        <td>{{ $abc->gruppo or '' }} - {{ $abc->grpProd->descrizion or '' }}</td>
        <td>{{ $abc->product->unmisura or 'PZ' }}</td>

        <td>{{ $abc->qtaN }}</td>
        <td>{{ $abc->qtaN1 }}</td>
        <td>{{ ($abc->qtaN - $abc->qtaN1) }}</td>
      </tr>
    @endforeach
  </tbody>
</table>
