@extends('layouts.app')

@section('htmlheader_title')
    - Scadenza
@endsection

@section('contentheader_title')
    Scadenza
@endsection

@section('contentheader_breadcrumb')
  {!! Breadcrumbs::render('scads') !!}
@endsection

@section('main-content')
<div class="row">
  <div class="col-lg-7">
    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title" data-widget="collapse">Scadenza</h3>
        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        </div>
      </div>
      <div class="box-body">
        <table class="table table-hover table-condensed dtTbls_light">
          <thead>
            <th>Data Scad.</th>
            <th>Stato</th>
            <th>Num. Fatt.</th>
            <th>Data Fatt.</th>
            <th>Cliente</th>
            <th>Tipologia</th>
            <th>Tipo Pag.</th>
            <th>Imp. Scad.</th>
            <th>Imp. Pagato</th>
          </thead>
          <tbody>
            @if(($scad->insoluto==1 || $scad->u_insoluto==1) && $scad->pagato==0)
            <tr class="danger">
            @elseif($scad->datascad < \Carbon\Carbon::now() && $scad->pagato==0)
            <tr class="warning">
            @else
            <tr>
            @endif
              <td>
                <span>{{$scad->datascad->format('Ymd')}}</span>
                <a href="{{ route('scad::detail', $scad->id ) }}"> {{ $scad->datascad->format('d-m-Y') }}</a>
              </td>
              <td>
                @if($scad->pagato==1)
                  Pagato
                @elseif($scad->insoluto==1)
                  Insoluto
                @elseif($scad->u_insoluto==1)
                  Moroso
                @else
                  Non Pagato
                @endif
              </td>
              <td>
                <a href="{{ route('doc::detail', $scad->id_doc ) }}">
                  {{ $scad->tipomod }} {{ $scad->numfatt }}
                </a>
              </td>
              <td><span>{{$scad->datafatt->format('Ymd')}}</span>{{ $scad->datafatt->format('d-m-Y') }}</td>
              <td>
                @if($scad->client)
                  <a href="{{ route('client::detail', $scad->codcf ) }}">
                    {{ $scad->client->descrizion }} [{{$scad->codcf}}]
                  </a>
                @endif
              </td>
              <td>
                @if($scad->idragg>0)
                  <a href="{{ route('scad::detail', $scad->idragg ) }}"> Accorpata</a>
                @endif
              </td>
              <td>{{ $scad->desc_pag }}</td>
              <td>{{ $scad->impeffval }}</td>
              <td>{{ $scad->importopag }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection
