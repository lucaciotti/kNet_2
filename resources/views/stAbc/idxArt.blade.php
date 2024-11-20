@extends('layouts.app')

@section('htmlheader_title')
    - {{ trans('_menu.AbcArt') }}
@endsection

@section('contentheader_title')
    {{ trans('_menu.AbcArt') }}
@endsection

@section('contentheader_breadcrumb')
    
@endsection

@push('css-head')
@endpush

@section('main-content')
<div class="row">
  <div class="col-lg-3">
        @include('stAbc.partials.formIndex', 
        [
          'grpPrdList' => $gruppi, 
          'grpPrdSelected' => $gruppo, 
          'optTipoProd' => $optTipoProd,
          'agentList' => $agentList, 
          'fltAgents' => $fltAgents, 
          'customerList' => $customerList,'customerSelected' => $customerSelected,
          'zoneList' => $zoneList,'zoneSelected' => $zoneSelected,
          'settoriList' => $settoriList,'settoreSelected' => $settoreSelected,
          'route' => 'stAbc::idxArt'])

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
          'fltAgents' => $fltAgents, 
          'customerSelected' => $customerSelected,
          'zoneSelected' => $zoneSelected,
          'settoreSelected' => $settoreSelected,
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
