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
  <div class="col-lg-8">
    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title" data-widget="collapse">{{ trans('scad.listScads') }}</h3>
        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        </div>
      </div>
      <div class="box-body">
        @if(RedisUser::get('role')=='client')
          @include('scads.partials.tblIndexCli', $scads)
        @else
          @include('scads.partials.tblIndex', $scads)
        @endif
      </div>
    </div>
  </div>

  <div class="col-lg-4">
    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title" data-widget="collapse">{{ trans('scad.filter') }}</h3>
        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        </div>
      </div>
      <div class="box-body">
        @include('scads.partials.formIndex')
      </div>
    </div>

  </div>
</div>
@endsection

@section('extra_script')
  @include('layouts.partials.scripts.select2')
  @include('layouts.partials.scripts.datePicker')
    @include('layouts.partials.scripts.iCheck')
@endsection
