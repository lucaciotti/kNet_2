@extends('layouts.app')
@section('htmlheader_title')
    - Dettaglio Articolo
@endsection

@section('contentheader_title')
    {{$prod->codice}}
@endsection

@section('contentheader_description')
    {{$prod->descrizion}}
@endsection

@section('contentheader_breadcrumb')
  {{-- {!! Breadcrumbs::render('product', $prod->codice) !!} --}}
@endsection

@section('main-content')
{{-- <div class="container"> --}}
<div class="row">
  <div class="col-lg-5">
    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">Dati Articolo</h3>
        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        </div>
      </div>
      <div class="box-body">
        <dl class="dl-horizontal">

          <dt>Codice Articolo</dt>
          <dd>{{$prod->codice}}</dd>

          <dt>Descrizione</dt>
          <dd>
            <big><strong>{{$prod->descrizion}}</strong></big>
          </dd>

          <dt>Gruppo Prodotto</dt>
          <dd>{{$prod->gruppo}} <br> @if($prod->grpProd) {{$prod->grpProd->descrizion}} @endif</dd>
        </dl>

        <h4> Dettagli di Vendita </h4>
        <hr>
        <dl class="dl-horizontal">

          <dt>U.M. Principale</dt>
          <dd>{{$prod->unmisura}}</dd>

          <dt>Qta Minima di Vendita</dt>
          <dd>{{$prod->qtaconf}}</dd>

          <dt>Prezzo Listino</dt>
          <dd>{{$prod->listino}} €</dd>

          @if($prod->u_maggfm)
            <dt>Maggiorazione <br>per Sconfezionamento</dt>
            <dd><br>{{$prod->u_maggfm}} %</dd>
          @endif
        </dl>
      </div>
    </div>
  </div>

  <div class="col-lg-5">
    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">Certificazioni</h3>
        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        </div>
      </div>
      <div class="box-body">
        <dl class="dl-horizontal">

          <dt>Marchiatura CE</dt>
          <dd>{{$prod->u_ce}}</dd>

          <dt>Certificazione UL/ULC</dt>
          <dd>{{$prod->u_ulc}}</dd>

          <dt>Certificazioni PEFC</dt>
          <dd>{{$prod->u_pefc}}</dd>

          <dt>Certificazioni Ambientale</dt>
          <dd>{{$prod->u_certamb}}</dd>
        </dl>
      </div>
    </div>
  </div>

</div>

<div class="row">
  <div class="col-lg-6">
    <div class="box box-default">  {{-- collapsed-box --}}
      <div class="box-header with-border">
        <h3 class="box-title">Annotazioni</h3>
        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        </div>
      </div>
      <div class="box-body">
      </div>
    </div>
  </div>

  <div class="col-lg-4">
    <div class="box box-default collapsed-box">
      <div class="box-header with-border">
        <h3 class="box-title" data-widget="collapse">Documenti del Cliente</h3>
        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
        </div>
      </div>
      <div class="box-body">

      </div>
    </div>

  </div>
</div>

{{-- </div> --}}
@endsection
