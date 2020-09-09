@extends('layouts.app')

@section('htmlheader_title')
    - {{ trans('stFatt.headTitle_agt') }}
@endsection

@section('contentheader_title')
    {{ trans('stFatt.contentTitle_agt') }}
@endsection

@section('contentheader_breadcrumb')
    
@endsection

@push('css-head')
@endpush

@section('main-content')
<div class="row">
  <div class="col-lg-3">
        <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title" data-widget="collapse">{{ trans('doc.filter') }}</h3>
        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          {{-- <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button> --}}
        </div>
      </div>
      <div class="box-body">
        @include('stAbc.partials.formIndex', ['gruppi' => $gruppi, 'agente' => '', 'customer' => '', 'route' => 'stAbc::idxArt'])
      </div>
    </div>
    
    {{-- <div class="box box-default collapsed-box">
      <div class="box-header with-border">
        <h3 class="box-title" data-widget="collapse"><i class='fa fa-cloud-download'> </i> Download</h3>
        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
        </div>
      </div>
      <div class="box-body">
        <a type="button" class="btn btn-default btn-block" target="_blank" href="{{ route('schedaAbc::PDF', $agente) }}">Scheda Abc PDF</a>
      </div>
    </div> --}}

  </div>

  <div class="col-lg-9">
    <div class="nav-tabs-custom">
      <ul class="nav nav-tabs pull-right">
        <li class="active"><a href="#statAbc" data-toggle="tab" aria-expanded="true">{{ strtoupper(trans('stFatt.total')) }}</a></li>
        <li class="pull-left header"><i class="fa fa-th"></i> {{ trans('stFatt.statsTitle') }}</li>
      </ul>
      <div class="tab-content">
        <div class="tab-pane active" id="statAbc">
        @include('stAbc.partials.tblIdxArt', [
          'AbcProds' => $AbcProds,
          'thisYear' => $thisYear,
          'prevYear' => $prevYear,
          'thisMonth' => $thisMonth,
        ])
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

@push('script-footer')

@endpush
