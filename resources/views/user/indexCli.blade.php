@extends('layouts.app')

@section('htmlheader_title')
    - {{ trans('user.headTitle_idx') }}
@endsection

@section('contentheader_title')
    {{ trans('user.contentTitle_idx') }}
@endsection

@section('contentheader_breadcrumb')
  {!! Breadcrumbs::render('clients') !!}
@endsection

@section('main-content')
  <div class="row">
    <div class="container">
    <div class="col-lg-12">

      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title" data-widget="collapse">{{ trans('user.listClients') }}</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          </div>
        </div>
        <div class="box-body">
          @include('user.partial.tblIndex', ['users' => $clients])
        </div>
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
