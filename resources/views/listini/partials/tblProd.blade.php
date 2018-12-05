<form action="{{ route('listini::wListOk') }}" method="post">
<input type="hidden" name="_token" value="{{ csrf_token() }}">
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
        <th style="text-align: center;">Fasce Qta</th>
        @if(!in_array(RedisUser::get('role'), ['agent', 'client']))
          <th colspan="1">|</th>
          <th style="text-align: center;">Estendi a 2019</th>
        @endif
      </tr>
    </thead>
    <tbody>
      @foreach ($ListProds as $list)
        <tr>
          @if(!$noCli)
            <td><a href="{{ route('stAbc::docsArtCli', ['codArt' => $list->codicearti, 'codcli' => $customer]) }}" target="_blank"> {{ $list->codicearti }} </a></td>
          @else
            <td><a href="{{ route('stAbc::detailArt', ['codArt' => $list->codicearti]) }}" target="_blank"> {{ $list->codicearti }} </a></td>
          @endif
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
              <a href='' data-toggle="modal" data-target=".bs-modal-{{$list->id}}">Dettaglio Fasce</a>
              @include('listini.partials.mdlFormFascie', 
              [
                'customer' => $customer,
                'customerDesc' => $customerDesc,
                'list' => $list
              ])
            @endif
          </td>

          @if(!in_array(RedisUser::get('role'), ['agent', 'client']))
            <td colspan="1">|</td>
            {{-- && $list->datafine.eq($endOfYear) --}}
            @php
                $goOn = $list->datafine && ($list->datafine <= ($endOfYear));
                // dd($endOfYear);
            @endphp
            @if($goOn) 
              @if(!$list->wListOk)
                <td style="text-align: center;">
                  <input type="checkbox" name="estendi[]" id="estendi[]" value="{{ $list->id }}">
                </td>
              @else
                <td style="text-align: center;">
                  <input type="checkbox" name="estese[]" id="estese[]" value="{{ $list->id }}" checked disabled readonly>
                </td>
              @endif
            @else
                <td style="text-align: center;">
                </td>
            @endif
          @endif
          {{-- <input type="hidden" name="list_id_{{ $list->id }}" value="{{ $list->id }}"> --}}

        </tr>
      @endforeach
    </tbody>
  </table>
@if(!$noCli)
  <input type="hidden" name="routeCli" value="{{$customer}}">
@else
  <input type="hidden" name="routeGrp" value="{{$customer}}">
@endif

@if(!in_array(RedisUser::get('role'), ['agent', 'client']))
  <button type="submit" class="btn btn-primary right">Salva Estensione Listini</button>
@endif
</form>