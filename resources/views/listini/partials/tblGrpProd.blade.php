<form action="{{ route('listini::wListOk') }}" method="post">
  <input type="hidden" name="_token" value="{{ csrf_token() }}">
  <table class="table table-hover table-condensed dtTbls_light" id="listProdTable">
    <thead>
      <tr>
        <th colspan="3">&nbsp;</th>
        <th colspan="5" style="text-align: center;">Listino Personalizzato</th>
      </tr>
      <tr>
        <th style="text-align: center;">Gruppo Prodotto</th>
        <th style="text-align: center;">Descrizione</th>
        <th colspan="1">|</th>

        <th style="text-align: center;">Prezzo</th>
        <th style="text-align: center;">Sconto</th>
        <th></th>
        <th style="text-align: center;">Fine Validità</th>
        <th colspan="1">|</th>
        <th style="text-align: center;">Fasce Qta</th>
        @if(!in_array(RedisUser::get('role'), ['agent', 'client']))
          <th colspan="1">|</th>
          <th style="text-align: center;">Estendi a 2021</th>
        @endif
      </tr>
    </thead>
    <tbody>
      @foreach ($ListGrpProds as $list)
        <tr>
          <td>{{ $list->gruppomag }}</td>
          @php
              $descrizion = ($list->grpProd) ? $list->grpProd->descrizion : $list->masterProd->descrizion; 
          @endphp
          <td>{{ $descrizion or '' }}</td>
          <th colspan="1">|</th>
          
          <td>{{ ($list->prezzo>0) ? currency($list->prezzo) : '-' }}</td>
          <td style="text-align: center;">{{ $list->sconto }}</td>
          <td>
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
              $goOn = $list->datafine && ($list->datafine<=( $endOfYear)); // dd($endOfYear);
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
          @endif {{-- <input type="hidden" name="list_id_{{ $list->id }}" value="{{ $list->id }}"> --}}
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