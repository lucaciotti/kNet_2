@extends('layouts.app')

@section('htmlheader_title')
    - {{ trans('rnc.headTitle_idx') }}
@endsection

@section('contentheader_title')
    {{ trans('rnc.contentTitle_idx') }}
@endsection

@section('contentheader_breadcrumb')
  {!! Breadcrumbs::render('rncs') !!}
@endsection

@section('main-content')
<div class="row">
  <div class="col-lg-8">
    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title" data-widget="collapse">{{ trans('rnc.listrncs') }}</h3>
        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        </div>
      </div>
      <div class="box-body">
        @include('rnc.partials.tblList', $rncs)
      </div>
    </div>
  </div>

  <div class="col-lg-4">
    @if(RedisUser::get('role')=='client')
    <div class="box box-default collapsed-box">
    @else
    <div class="box box-default">
    @endif
      <div class="box-header with-border">
        <h3 class="box-title" data-widget="collapse">{{ trans('rnc.filter') }}</h3>
        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        </div>
      </div>
      <div class="box-body">
        @include('rnc.partials.formIndex')
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

@push('script-footer')

@endpush