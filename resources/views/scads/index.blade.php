@extends('layouts.app')

@section('htmlheader_title')
    - {{ trans('scad.headTitle_idx') }}
@endsection

@section('contentheader_title')
    {{ trans('scad.contentTitle_idx') }}
@endsection

@section('contentheader_breadcrumb')
  {!! Breadcrumbs::render('scads') !!}
@endsection

@section('main-content')
<div class="row">
  <div class="col-lg-7">
    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title" data-widget="collapse">{{ trans('scad.listScads') }}</h3>
        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        </div>
      </div>
      <div class="box-body">
        @include('scads.partials.tblIndex', $scads)
      </div>
    </div>
  </div>

  <div class="col-lg-5">
    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title" data-widget="collapse">{{ trans('scad.filter') }}</h3>
        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          {{-- <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button> --}}
        </div>
      </div>
      <div class="box-body">
        @include('scads.partials.formIndex')
      </div>
    </div>

    {{-- <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title" data-widget="collapse">Cambia Tipo Documento</h3>
        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        </div>
      </div>
      <div class="box-body">
        <a type="button" class="btn btn-default btn-block" href="{{ route('doc::list', ['']) }}">TUTTI</a>
        <a type="button" class="btn btn-default btn-block" href="{{ route('doc::list', ['P']) }}">Preventivi</a>
        <a type="button" class="btn btn-default btn-block" href="{{ route('doc::list', ['O']) }}">Ordini</a>
        <a type="button" class="btn btn-default btn-block" href="{{ route('doc::list', ['B']) }}">Bolle</a>
        <a type="button" class="btn btn-default btn-block" href="{{ route('doc::list', ['F']) }}">Fatture</a>
        <a type="button" class="btn btn-default btn-block" href="{{ route('doc::list', ['N']) }}">Note di Accredito</a>
      </div>
    </div> --}}
  </div>
</div>
@endsection

@section('extra_script')
  @include('layouts.partials.scripts.select2')
  @include('layouts.partials.scripts.datePicker')
    @include('layouts.partials.scripts.iCheck')
@endsection
