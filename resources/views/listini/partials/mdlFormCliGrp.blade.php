{{-- <a data-toggle="modal" data-target=".bs-modal">Dettaglio Fascie</a> --}}
{{-- <button class="btn btn-success btn-block" data-toggle="modal" data-target=".bs-modal">Dettaglio Fascie</button> --}}

<div class="modal fade bs-modal_{{$id}}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Lista Clienti Associati</h4>
      </div>
      <div class="modal-body">
        <table class="table table-hover table-condensed dtTbls_light" id="listProdTable">
          <thead>
            <tr>
              <th style="text-align: center;">Cod.Cli.</th>
              <th style="text-align: center;">Descrizione</th>
              <th colspan="1">|</th>
        
              <th style="text-align: center;">Link Listino Pers.</th>
              <th colspan="1">|</th>
              <th style="text-align: center;">Link Abc Articoli</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($customersList as $customers)
            @if($customers instanceof \Illuminate\Support\Collection)
              @foreach ($customers as $client)
              <tr>
                <td><a href="{{ route('client::detail', ['codicecf' => $client->codice]) }}"> {{ $client->codice }} </a></td>
                <td>{{ $client->descrizion or '' }}</td>
                <th colspan="1">|</th>
                <td><a href="{{ route('listini::idxCli', ['codicecf' => $client->codice]) }}"> Listino Pers. </a></td>
                <th colspan="1">|</th>
                <td><a href="{{ route('stAbc::idxCli', ['codicecf' => $client->codice]) }}"> Abc Articoli </a></td>
              </tr>
              @endforeach
            @else
              <tr>
                <td><a href="{{ route('client::detail', ['codicecf' => $customers->codice]) }}"> {{ $customers->codice }} </a></td>
                <td>{{ $customers->descrizion or '' }}</td>
                <th colspan="1">|</th>
                <td><a href="{{ route('listini::idxCli', ['codicecf' => $customers->codice]) }}"> Listino Pers. </a></td>
                <th colspan="1">|</th>
                <td><a href="{{ route('stAbc::idxCli', ['codicecf' => $customers->codice]) }}"> Abc Articoli </a></td>
              </tr>
            @endif
            @endforeach
          </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('doc.closePage') }}</button>
      </div>
    </div>
  </div>
</div>
