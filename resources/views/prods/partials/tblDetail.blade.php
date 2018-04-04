<table class="table table-hover table-condensed dtTbls_total">
  <thead>
    <th># Riga</th>
    <th>Codice Articolo</th>
    <th>Descrizione</th>
    <th>Cod.Lotto</th>
    <th>Qta</th>
    <th>Prezzo Un.</th>
    <th>Sconto</th>
    <th>Prezzo Tot</th>
  </thead>
  <tfoot>
    <tr>
      <th colspan="6" style="text-align:right">Totale:</th>
      <th></th>
      <th></th>
    </tr>
  </tfoot>
  <tbody>
    @foreach ($rows as $row)
      @if($head->tipomodulo!='O')
        <tr>
          <td>{{ $row->numeroriga }}</td>
          <td><a href="#"> {{ $row->codicearti }} </a></td>
          <td>{{ $row->descrizion }}</td>
          <td>{{ $row->lotto }}</td>
          <td>{{ $row->quantita }}</td>
          <td>{{ $row->prezzoun }}</td>
          <td>{{ $row->sconti }}</td>
          <td>{{ $row->prezzotot }}</td>
        </tr>
      @elseif($head->tipomodulo=='O' && $row->rifstato!='X')        
          <td>{{ $row->numeroriga }}</td>
          <td><a href="#"> {{ $row->codicearti }} </a></td>
          <td>{{ $row->descrizion }}</td>
          <td>{{ $row->lotto }}</td>
          <td>{{ $row->quantita }}</td>
          <td>{{ $row->prezzoun }}</td>
          <td>{{ $row->sconti }}</td>
          <td>{{ $row->prezzotot }}</td>
        </tr>
      @endif
    @endforeach
  </tbody>
</table>
