@extends('layouts.app')

@section('htmlheader_title')
    - Report Visite
@endsection

@section('contentheader_title')
    Report Visite
@endsection

@section('contentheader_breadcrumb')
    {{-- {!! Breadcrumbs::render('visit') !!} --}}
@endsection

@section('main-content')
<div class="row">

  <div class="col-lg-3">
    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title" data-widget="collapse">{{ trans('doc.filter') }}</h3>
        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          {{-- <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
          --}}
        </div>
      </div>
      <div class="box-body">
        @include('visit.partials.formReport')
      </div>
    </div>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title" data-widget="collapse"><i class='fa fa-cloud-download'> </i> Download</h3>
        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
        </div>
      </div>
      <div class="box-body">
        <a type="button" class="btn btn-default btn-block" target="_blank"
          href="{{ route('visit::reportPDF', $dataForReport) }}">Report PDF (Relatore)</a>
      </div>
    </div>

  </div>

  <div class="col-lg-9">
    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title" data-widget="collapse">{{ trans('doc.listDocs') }}</h3>
        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        </div>
      </div>
      <div class="box-body">
        @include('visit.partials.tblReport', [$visits])
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
