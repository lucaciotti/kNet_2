@extends('layouts.app')

@section('htmlheader_title')
    - {{$head->tipodoc}} {{$head->numerodoc}}
@endsection

@section('contentheader_title')
    {{$head->tipodoc}} {{$head->numerodoc}}
@endsection

@section('contentheader_description')
    {{-- di {{$head->client->descrizion}} [{{$head->codicecf}}] --}}
    {{ trans('doc.contentDesc_dtl', ['date' => $head->datadoc->format('d/m/Y')]) }}
@endsection

@section('contentheader_breadcrumb')
    {!! Breadcrumbs::render('docsDetail', $head) !!}
@endsection

@section('main-content')
<div class="row">
  <div class="col-lg-5">
    <div class="nav-tabs-custom">
      <ul class="nav nav-tabs">
        <li class="active"><a href="#DatiDoc" data-toggle="tab" aria-expanded="true">{{ trans('doc.dataDoc') }}</a></li>
        <li class=""><a href="#Sped" data-toggle="tab" aria-expanded="false">{{ trans('doc.dataSped') }}</a></li>
        <li class=""><a href="#Val" data-toggle="tab" aria-expanded="false">{{ trans('doc.totsDoc') }}</a></li>
      </ul>
      <div class="tab-content">
        <div class="tab-pane active" id="DatiDoc">
          <dl class="dl-horizontal">
            <dt>{{ trans('doc.document') }}</dt>
            <dd>{{$head->tipodoc}} {{$head->numerodoc}}</dd>

            <dt>{{ trans('doc.client') }}</dt>
            <dd><strong>{{$head->client->descrizion}} [{{$head->codicecf}}]</strong></dd>

            <dt>{{ trans('doc.dateDoc') }}</dt>
            <dd>{{$head->datadoc->format('d/m/Y')}}</dd>

            @if($head->tipomodulo == 'O')
              <dt>{{ trans('doc.deliverType') }}</dt>
              <dd>{{$head->u_tipocons}}</dd>

              {{-- <dt>Prevista Consegna</dt>
              <dd>{{$head->datacons->format('d/m/Y')}}</dd> --}}
            @endif

            <dt>{{ trans('doc.referenceDoc') }}</dt>
            <dd>{{$head->numerodocf}}</dd>

            <hr>

            <dt>{{ trans('doc.totDoc') }}</dt>
            <dd><strong>{{$head->totdoc}} €</strong></dd>

            @if($head->tipomodulo == 'F' || $head->tipomodulo == 'N')
              <br>

              @if($head->scontocass)
              <dt>{{ trans('doc.scontoCass') }}</dt>
              <dd>{{$head->scontocass}} %</dd>
              @endif

              <dt>{{ trans('doc.totPayment') }}</dt>
              <dd>{{$head->totdoc}} €</dd>
            @endif
          </dl>
        </div>
        <!-- /.tab-pane -->
        <div class="tab-pane" id="Sped">
          @if($head->tipomodulo == 'B' || $head->tipodoc == 'BV' || $head->tipodo == 'EQ')
          <dl class="dl-horizontal">
            <dt>{{ trans('doc.nColli') }}</dt>
            <dd>{{$head->colli}}</dd>

            <dt>{{ trans('doc.goodsAspect') }}</dt>
            <dd>@if($head->detBeni) {{$head->detBeni->descrizion}} @endif</strong></dd>

            <dt>{{ trans('doc.volume') }}</dt>
            <dd>{{$head->volume}} mc</dd>

            <dt>{{ trans('doc.weightNet') }}</dt>
            <dd>{{$head->pesonetto}} Kg</dd>

            <dt>{{ trans('doc.weightGross') }}</dt>
            <dd>{{$head->pesolordo}} Kg</dd>

            <hr>

            <dt>{{ trans('doc.carrier') }}</dt>
            <dd>@if($head->vettore) {{$head->vettore->descrizion}} @endif</dd>

            <dt>{{ trans('doc.carrierDelivery') }}</dt>
            <dd>@if($head->v1data) {{$head->v1data->format('d/m/Y')}} - {{$head->v1ora}} @else -- @endif</dd>

            <br>
            @if($destinaz)
            <dt>{{ trans('doc.goodsDestination') }}</dt>
            <dd>{{$destinaz->ragionesoc}}</dd>
            <dd>{{$destinaz->cap}}, {{$destinaz->localita}} ({{$destinaz->pv}}) - {{$destinaz->u_nazione}}</dd>
            <dd>{{$destinaz->indirizzo}}</dd>
            <dd>{{$destinaz->telefono}}</dd>
            @endif

            <hr>
            @if (empty($ddtOk))
              @include('docs.partials.mdlFormDdtOk', ['head' => $head])
            @else
              <dt>{{ trans('doc.dataReceived') }}</dt>
              <dd>{{$ddtOk->created_at->format('d/m/Y')}}</dd>

              <dt>{{ trans('doc.signReceived') }}</dt>
              <dd>{{$ddtOk->firma}}</dd>

              <dt>{{ trans('doc.noteReceived') }}</dt>
              <dd>{{$ddtOk->note or ''}}</dd>
            @endif

            @if (!empty($head->patrasf))
              <hr>
              <dt>ID Tracking: </dt>
              <dd>{{$head->patrasf}}</dd>
              <br>
              
              <a type="button" class="btn btn-default btn-block" target="_blank" href={!! str_replace('<IDCOLLO>', $head->patrasf, $head->vettore->note) !!}>
                <strong> Link Tracking </strong>
              </a>                
            @endif
          @else
            <div class="callout callout-danger">
              <p>{{ trans('doc.noDeliveryMessage') }}</p>
            </div>
          </dl>
          @endif
        </div>
        <!-- /.tab-pane -->
        <div class="tab-pane" id="Val">
          <dl class="dl-horizontal">
            @if($head->sconti)
            <dt>{{ trans('doc.scontoMerce') }}</dt>
            <dd>{{$head->sconti}} %</dd>
            @endif

            <br>

            <dt>{{ trans('doc.totMerce') }}</dt>
            <dd>{{$head->totmerce}} €</dd>

            <dt>{{ trans('doc.deliveryCost') }}</dt>
            <dd>{{$head->speseim + $head->spesetr}} €</dd>

            <br>

            <dt>{{ trans('doc.totImp') }}</dt>
            <dd>{{$head->totimp}} €</dd>

            <dt>{{ trans('doc.totVat') }}</dt>
            <dd>{{$head->totiva}} €</dd>

            <hr>

            <dt>{{ trans('doc.totDoc_condensed') }}</dt>
            <dd><strong>{{$head->totdoc}} €</strong></dd>

            @if($head->tipomodulo == 'F' || $head->tipomodulo == 'N')
              <br>

              @if($head->scontocass)
              <dt>{{ trans('doc.scontoCassa') }}</dt>
              <dd>{{$head->scontocass}} %</dd>
              @endif

              <dt>{{ trans('doc.totPayment') }}</dt>
              <dd>{{$head->totdoc}} €</dd>
            @endif
          </dl>
        </div>
        <!-- /.tab-pane -->
      </div>
      <!-- /.tab-content -->
    </div>

    <div class="box box-default collapsed-box">
      <div class="box-header with-border">
        <h3 class="box-title" data-widget="collapse"><i class='fa fa-cloud-download'> </i> Download</h3>
        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
        </div>
      </div>
      <div class="box-body">
        <a type="button" class="btn btn-default btn-block" target="_blank" href="{{ route('doc::downloadXML', $head->id) }}">
         <strong> XML File</strong>
        </a>
        <hr>
        <a type="button" class="btn btn-default btn-block" target="_blank" href="{{ route('doc::downloadXLS', $head->id) }}">
          <strong> Excel File</strong>
        </a>
        <hr>
        <a type="button" class="btn btn-primary btn-block" target="_blank" href="{{ route('doc::downloadPDF', $head->id) }}">
          <strong> PDF File</strong>
        </a>
      </div>
    </div>

    @if($head->tipomodulo == 'F')
      <div class="box box-warning">
        <div class="box-header with-border">
          <h3 class="box-title" data-widget="collapse">{{ trans('doc.payment') }}</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          </div>
        </div>
        <div class="box-body">
          @if(!empty($head->scadenza))
            <h4>{{ trans('doc.lnkPayment') }}</h4>
            <table class="table table-hover table-condensed dtTbls_light">
              <thead>
                <th>{{ trans('doc.datePay') }}</th>
                <th>{{ trans('doc.numInvoice') }}</th>
                <th>{{ trans('doc.dateInvoice') }}</th>
                <th>{{ trans('doc.merged') }}?</th>
                <th>{{ trans('doc.valueToPay') }}</th>
                <th>{{ trans('doc.valuePayed') }}</th>
              </thead>
              <tbody>
                    @if ($head->scadenza->count()>0)
                        @foreach ($head->scadenza as $scad)
                            @if($scad->insoluto==1 || $scad->u_insoluto==1)
                            <tr class="danger">
                              @elseif($scad->datascad < \Carbon\Carbon::now()) <tr class="warning">
                                @else
                            <tr>
                              @endif
                              <td>
                                <span>{{$scad->datascad->format('Ymd')}}</span>
                                <a href=""> {{ $scad->datascad->format('d-m-Y') }}</a>
                              </td>
                              <td>{{ $scad->numfatt }}</td>
                              <td><span>{{$scad->datafatt->format('Ymd')}}</span>{{ $scad->datafatt->format('d-m-Y') }}</td>
                              <td>@if($scad->idragg>0)
                                <a href=""> {{ trans('doc.merged') }}</a>
                                @endif</td>
                              <td>{{ $scad->impeffval }}</td>
                              <td>{{ $scad->importopag }}</td>
                            </tr>
                        @endforeach
                    @endif
              </tbody>
            </table>
          @endif
        </div>
      </div>
    @endif

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title" data-widget="collapse">{{ trans('doc.linkedDocs') }}</h3>
        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        </div>
      </div>
      <div class="box-body">
        @if($prevDocs->count()>0)
          <h4>{{ trans('doc.prevDocs') }}</h4>
            @foreach($prevDocs as $doc)
              <a type="button" class="btn btn-default btn-block" href="{{ route('doc::detail', $doc->id) }}">
                <strong>{{$doc->tipodoc}} {{$doc->numerodoc}} del {{$doc->datadoc->format('d/m/Y')}}</strong>
              </a>
            @endforeach
        @endif
        <hr>
        @if($nextDocs->count()>0)
          <h4>{{ trans('doc.nextDocs') }}</h4>
            @foreach($nextDocs as $doc)
              <a type="button" class="btn btn-primary btn-block" href="{{ route('doc::detail', $doc->id) }}">
                <strong>{{$doc->tipodoc}} {{$doc->numerodoc}} del {{$doc->datadoc->format('d/m/Y')}}</strong>
              </a>
            @endforeach
        @endif
      </div>
    </div>
  </div>

  <div class="col-lg-7">
    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title" data-widget="collapse">{{ trans('doc.listRows') }}</h3>
        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        </div>
      </div>
      <div class="box-body">
        @include('docs.partials.tblDetail', [$rows, $head])
      </div>
    </div>
  </div>
</div>
@endsection

@section('extra_script')
  @include('layouts.partials.scripts.iCheck')
  @include('layouts.partials.scripts.select2')
  @include('layouts.partials.scripts.datePicker')
  <script>
    $(document).ready(function(){
      $('[data-toggle="popover"]').popover();
    });
  </script>
@endsection
