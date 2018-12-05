{{-- <a data-toggle="modal" data-target=".bs-modal">Dettaglio Fascie</a> --}}
{{-- <button class="btn btn-success btn-block" data-toggle="modal" data-target=".bs-modal">Dettaglio Fascie</button> --}}

<div class="modal fade bs-modal-{{$list->id}}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Dettaglio Fasce per Qta</h4>
      </div>
      <div class="modal-body">
        <dl class="dl-horizontal">
            <dt>Cliente</dt>
            <dd>{{$customer}} - {{$customerDesc}}</dd>

            @if($list->codicearti)
              <dt>Cod.Art.</dt>
              <dd>{{$list->codicearti}}</dd>
            @endif

            @if($list->gruppomag)
              <dt>Gruppo Prodotto</dt>
              <dd>{{$list->gruppomag}}</dd>
            @endif

            <hr>

            <dt>Fascia 1:</dt>
            <dd>Qta: <strong>{{$list->u_budg1}} {{$list->product->unmisura or 'PZ'}}</strong></dd>
            <dd>Prezzo: <strong>{{ ($list->u_budg1n>0) ? currency($list->u_budg1n) : '-'}}</strong></dd>
            <dd>Sconto: <strong>{{$list->u_budg1p}}</strong></dd>

            @if($list->u_budg2>0)
            <hr>            
              <dt>Fascia 2:</dt>
              <dd>Qta: <strong>{{$list->u_budg2}} {{$list->product->unmisura or 'PZ'}}</strong></dd>
              <dd>Prezzo: <strong>{{ ($list->u_budg2n>0) ? currency($list->u_budg2n) : '-'}}</strong></dd>
              <dd>Sconto: <strong>{{$list->u_budg2p}}</strong></dd>
            @endif

            @if($list->u_budg3>0)
              <hr>
              <dt>Fascia 3:</dt>
              <dd>Qta: <strong>{{$list->u_budg3}} {{$list->product->unmisura or 'PZ'}}</strong></dd>
              <dd>Prezzo: <strong>{{ ($list->u_budg3n>0) ? currency($list->u_budg3n) : '-'}}</strong></dd>
              <dd>Sconto: <strong>{{$list->u_budg3p}}</strong></dd>
            @endif
        </dl>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('doc.closePage') }}</button>
      </div>
    </div>
  </div>
</div>
