@extends('layouts.app')

@section('htmlheader_title')
    - {{ trans('rnc.headTitle_dtl') }}
@endsection

@section('contentheader_title')
    RNC # {{ $rnc->nummov }}
@endsection

@section('contentheader_description')
    [{{ $rnc->esercizio ?? ''}}]
@endsection

{{-- @section('contentheader_breadcrumb')
  {!! Breadcrumbs::render('rncs', $rnc->nummov) !!}
@endsection --}}

@section('main-content')
{{-- <div class="container"> --}}
<div class="row">

  <div class="col-lg-4">
    <div class="nav-tabs-custom">
      <ul class="nav nav-tabs">
        <li class="active"><a href="#Gen" data-toggle="tab" aria-expanded="true">Generale</a></li>
        {{-- <li class=""><a href="#Costi" data-toggle="tab" aria-expanded="false">Costi</a></li> --}}
        @if(RedisUser::get('role')!='client')
          <li class=""><a href="#Costi" data-toggle="tab" aria-expanded="false">Costi</a></li>
          <li class=""><a href="#Ref" data-toggle="tab" aria-expanded="false">Referenti KK</a></li>
        @endif
      </ul>
      <div class="tab-content">

        <div class="tab-pane active" id="Gen">
          <dl class="dl-horizontal">
            <dt>RNC #</dt>
            <dd>
              <big><strong>{{$rnc->nummov}}</strong></big>
              <small>/{{$rnc->esercizio}}</small>
            </dd>

            <dt>Cliente</dt>
            <dd>
              @if($rnc->client)
              <a href="{{ route('client::detail', $rnc->codfor ) }}">
                [{{ $rnc->client->codice ?? '' }}] {{$rnc->client->descrizion ?? ''}}
              </a>
              @endif
            </dd>

            <dt>Data Registrazione</dt>
            <dd>{{$rnc->datareg->format('d-m-Y')}}</dd>

            <dt>Data Chiusura</dt>
            <dd>@if ($rnc->dataend) {{$rnc->dataend->format('d-m-Y')}} @endif</dd>
          </dl>

          <h4><strong> Dettagli </strong> </h4>
          <hr style="padding-top: 0; margin-top:0;">
          <dl class="dl-horizontal">

            <dt>Tipologia</dt>
            <dd>{{ ucfirst(strtolower($rnc->rncTipoRapp->descrizion ?? '')) }}</dd>

            <dt>Causa</dt>
            <dd>{{ ucfirst(strtolower($rnc->rncCausa->descrizion ?? '')) }}</dd>

            <dt>Gravità</dt>
            <dd>{{ $rnc->severity }}</dd>
          </dl>
        </div>

        <div class="tab-pane" id="Costi">
          <dl class="dl-horizontal">

            <dt>Ore Spese</dt>
            <dd>{{$rnc->oreperse ?? 0}}</dd>

            <dt>Costo</dt>
            <dd>{{$rnc->costo ?? 0}}</dd>

            <hr>

            <dt>Ore manodopera</dt>
            <dd>{{$rnc->oreman ?? 0}}</dd>

            <dt>Costo manodopera</dt>
            <dd>{{$rnc->costoman ?? 0}}</dd>

            <hr>

            <dt>Vettore</dt>
            <dd>{{$rnc->vettore->descrizion ?? '-'}}</dd>

            <dt>Trasporto a Carico</dt>
            <dd>{{$rnc->trasporto ?? ''}}</dd>

          </dl>
        </div>
     
        <div class="tab-pane" id="Ref">
          <dl class="dl-horizontal">        
            <dt>Apertura</dt>
            <dd>{{$rnc->dipApertura->descrizion ?? ''}}</dd>

            <dt>Analisi</dt>
            <dd>{{$rnc->dipAnalisi->descrizion ?? ''}}</dd>

            <dt>Attività Correttiva</dt>
            <dd>{{$rnc->dipAttCorr->descrizion ?? ''}}</dd>
        
            <dt>Chiusura</dt>
            <dd>{{$rnc->dipChiusura->descrizion ?? ''}}</dd>        
          </dl>
        </div>
      </div>
    </div>
  </div>

  <div class="col-lg-2">
    <div class="nav-tabs-custom">
      <ul class="nav nav-tabs">
        <li class="active"><a href="#Doc" data-toggle="tab" aria-expanded="true">Documenti collegati</a></li>
      </ul>
      <div class="tab-content">
  
        <div class="tab-pane active" id="Doc">
          <ul>
            @if (count($rncDocRif))
            <li>
              @if ($rncDocRif['id'])
                <a href="{{ route('doc::detail', $rncDocRif['id'] ) }}">
                  {{ $rncDocRif['tipodoc'] }} {{ $rncDocRif['numerodoc'] }} / {{ $rncDocRif['esercizio'] }}
                </a>
                @else
                  {{ $rncDocRif['tipodoc'] }} {{ $rncDocRif['numerodoc'] }} / {{ $rncDocRif['esercizio'] }}
              @endif
            </li>                
            @endif
            
            @foreach ($rnc->rncDocs as $doc)<li>
                <a href="{{ route('doc::detail', $doc->id_doc) }}">
                  {{ $doc->tipodoc }} {{ $doc->numerodoc }} / {{ $doc->datadoc->year }}
                </a>
            </li>                
            @endforeach
          </ul>
        </div>
      </div>
    </div> 
  </div>

  <div class="col-lg-6">

    <div class="nav-tabs-custom">
      <ul class="nav nav-tabs">
        <li class="active"><a href="#Art" data-toggle="tab" aria-expanded="true">Articoli collegati</a></li>
      </ul>
      <div class="tab-content">
    
        <div class="tab-pane active" id="Art">
          <table class='table table-hover table-condensed'> 
            <col width="50">
            <col width="150">
            <col width="50">
            <col width="10">
            <col width="50">
            <head>
              <th>Codice</th>
              <th>Descrizione</th>
              <th>Lotto</th>
              <th>UM</th>
              <th>Quantita</th>
            </head>   
            <body>              
            @foreach ($rnc->rncArts as $art)
            <tr>
              <th>{{ $art->codicearti }}</th>
              <td>{{ $art->descrizion }}</td>
              <td>{{ $art->lotto }}</td>
              <td>{{ $art->unmisura }}</td>
              <td>{{ $art->quantita }}</td>
            </tr>
            @endforeach
            </body>
          </table>
        </div>
      </div>
    </div>

  </div>

</div>

<div class="row">

  <div class="col-lg-6">
    <div class="box box-default {{-- collapsed-box --}}">
      <div class="box-header with-border">
        <h3 class="box-title" data-widget="collapse">Dettaglio</h3>
        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
        </div>
      </div>
      <div class="box-body">        
        <strong>{!! $rnc->dettaglio !!}</strong>
      </div>
    </div>
  </div>

  <div class="col-lg-6">
    <div class="box box-default {{-- collapsed-box --}}"> 
      <div class="box-header with-border">
        <h3 class="box-title" data-widget="collapse">Azione</h3>
        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
        </div>
      </div>
      <div class="box-body">
        <strong>{!! $rnc->azione !!}</strong>
      </div>
    </div>

  </div>
</div>


{{-- </div> --}}
@endsection
