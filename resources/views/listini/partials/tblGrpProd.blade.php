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
      <th style="text-align: center;">Fascie Qta</th>
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