@extends('layouts.app')

@section('htmlheader_title')
    - {{ trans('doc.headTitle_idx') }}
@endsection

@section('contentheader_title')
    {{ trans('doc.contentTitle_idx', ['tipoDoc' => $descModulo]) }}
@endsection

@section('contentheader_breadcrumb')
  @if($tipomodulo)
    {!! Breadcrumbs::render('docsTipo', $tipomodulo) !!}
  @else
    {!! Breadcrumbs::render('docs') !!}
  @endif
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
    @if(RedisUser::get('role')=='client')
    <div class="box box-default collapsed-box">
    @else
    <div class="box box-default"> 
    @endif
      <div class="box-header with-border">
        <h3 class="box-title" data-widget="collapse">{{ trans('doc.filter') }}</h3>
        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          {{-- <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button> --}}
        </div>
      </div>
      <div class="box-body">
        @include('docs.partials.formIndex')
      </div>
    </div>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title" data-widget="collapse">{{ trans('doc.changeDoc') }}</h3>
        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        </div>
      </div>
      <div class="box-body">
        <a type="button" class="btn btn-default btn-block" href="{{ route('doc::list', ['']) }}">{{ strtoupper(trans('client.allDocs')) }}</a>
        <a type="button" class="btn btn-default btn-block" href="{{ route('doc::list', ['P']) }}">{{ trans('client.quotes') }}</a>
        <a type="button" class="btn btn-default btn-block" href="{{ route('doc::list', ['O']) }}">{{ trans('client.orders') }}</a>
        <a type="button" class="btn btn-default btn-block" href="{{ route('doc::list', ['B']) }}">{{ trans('client.ddt') }}</a>
        <a type="button" class="btn btn-default btn-block" href="{{ route('doc::list', ['F']) }}">{{ trans('client.invoice') }}</a>
        <a type="button" class="btn btn-default btn-block" href="{{ route('doc::list', ['N']) }}">{{ trans('client.notecredito') }}</a>
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
