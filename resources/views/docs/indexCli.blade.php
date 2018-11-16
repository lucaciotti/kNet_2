@extends('layouts.app')

@section('htmlheader_title')
    - {{ trans('doc.headTitle_idx') }}
@endsection

@section('contentheader_title')
    {{ trans('doc.contentTitle_idx', ['tipoDoc' => $descModulo]) }}
@endsection

@section('contentheader_description')
    {{$client->descrizion}} [{{$client->codice}}]
@endsection

@section('contentheader_breadcrumb')
    {!! Breadcrumbs::render('clientDocs', $codicecf, $tipomodulo) !!}
@endsection

@section('main-content')
<div class="row">
  <div class="col-lg-7">
    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title" data-widget="collapse">{{ trans('doc.listDocs') }}</h3>
        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        </div>
      </div>
      <div class="box-body">
        @include('docs.partials.tblIndex', [$docs, $tipomodulo])
      </div>
    </div>
  </div>

  <div class="col-lg-5">
    <div class="box box-default collapsed-box">
      <div class="box-header with-border">
        <h3 class="box-title" data-widget="collapse">{{ trans('doc.filter') }}</h3>
        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
          {{-- <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button> --}}
        </div>
      </div>
      <div class="box-body">
        @include('docs.partials.formIndex')
      </div>
    </div>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title" data-widget="collapse">{{ trans('doc.changeDoc') }} {{ trans('doc.forCli', ['client' => $client->descrizion]) }}</h3>
        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        </div>
      </div>
      <div class="box-body">
        <a type="button" class="btn btn-default btn-block" href="{{ route('doc::client', [$client->codice, '']) }}">{{ strtoupper(trans('client.allDocs')) }}</a>
        <a type="button" class="btn btn-default btn-block" href="{{ route('doc::client', [$client->codice, 'P']) }}">{{ trans('client.quotes') }}</a>
        <a type="button" class="btn btn-default btn-block" href="{{ route('doc::client', [$client->codice, 'O']) }}">{{ trans('client.orders') }}</a>
        <a type="button" class="btn btn-default btn-block" href="{{ route('doc::client', [$client->codice, 'B']) }}">{{ trans('client.ddt') }}</a>
        <a type="button" class="btn btn-default btn-block" href="{{ route('doc::client', [$client->codice, 'F']) }}">{{ trans('client.invoice') }}</a>
        <a type="button" class="btn btn-default btn-block" href="{{ route('doc::client', [$client->codice, 'N']) }}">{{ trans('client.notecredito') }}</a>
      </div>
    </div>
  </div>
</div>
@endsection

@section('extra_script')
  @include('layouts.partials.scripts.iCheck')
  @include('layouts.partials.scripts.select2')
  @include('layouts.partials.scripts.datePicker')
@endsection
