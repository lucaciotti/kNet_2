<table class="table table-hover table-condensed dtTbls_light" id="listProdTable">
  <thead>
    <tr>
      <th colspan="5">&nbsp;</th>
      <th colspan="6" style="text-align: center;">Listino Personalizzato</th>
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
      <th></th>
      <th style="text-align: center;">Fine Validità</th>
      <th colspan="1">|</th>
      <th style="text-align: center;">Fascie Qta</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($ListProds as $list)
      <tr>
        <td><a href="{{ route('stAbc::docsArtCli', ['codArt' => $list->codicearti, 'codcli' => $customer]) }}"> {{ $list->codicearti }} </a></td>
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
        @endphp
        <td>
          <a href="#" data-toggle="popover" title="Condizioni" data-trigger="focus"
            data-content="<div>
              Listino Lordo: {{ ($list->product) ? currency($list->product->listino) : 0 }} <br>
              Listino Netto: {{currency( knet\Helpers\Utils::scontaDel($prezzo, $list->sconto, 2)) }}
            </div>" 
            data-placement="right">
            <i class="fa fa-info-circle"> </i>
          </a>
          @if($list->u_noprzmin)
            <a href="#" data-toggle="popover" title="Attenzione!" data-trigger="focus"
              data-content="<div>
                Prezzo Direzionale
              </div>" 
              data-placement="right">
              <i class="fa fa-exclamation-triangle" style="color:darkred"></i>
            </a>
          @endif
        </td>
        @php
            $dateSpan = ($list->datafine) ? $list->datafine->format('Ymd') : new Carbon\Carbon(2019,1,1);
            $dateCol = ($list->datafine) ? $list->datafine->format('d-m-Y') : '';
        @endphp
        <td style="text-align: center;"><span>{{$dateSpan}}</span>{{ $dateCol}}</td>
        <th colspan="1">|</th>
        <td>
          @if($list->u_budg1>0)
            <a href='' data-toggle="modal" data-target=".bs-modal-{{$list->id}}">Dettaglio Fascie</a>
            @include('listini.partials.mdlFormFascie', 
            [
              'customer' => $customer,
              'customerDesc' => $customerDesc,
              'list' => $list
            ])
          @endif
        </td>
      </tr>
    @endforeach
  </tbody>
</table>
