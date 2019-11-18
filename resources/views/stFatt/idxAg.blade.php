@extends('layouts.app')

@section('htmlheader_title')
    - {{ trans('stFatt.headTitle_agt') }}
@endsection

@section('contentheader_title')
    {{ trans('stFatt.contentTitle_agt') }}
@endsection

@section('contentheader_breadcrumb')
    {{-- {!! Breadcrumbs::render('agentStFat', $agente) !!} --}}
@endsection

@push('css-head')
  <!-- Morris charts -->
  {{-- <link rel="stylesheet" href="{{ asset('/plugins/morris/morris.css') }}"> --}}
@endpush

@section('main-content')
<div class="row">
  <div class="col-lg-3">
    <form action="{{ route('stFatt::idxAg') }}" method="post">
    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title" data-widget="collapse">{{ trans('stFatt.agent') }}</h3>
        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          {{-- <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button> --}}
        </div>
      </div>
      <div class="box-body">
        {{-- <form action="{{ route('stFatt::idxAg') }}" method="post"> --}}
          <input type="hidden" name="_token" value="{{ csrf_token() }}">
          <div class="form-group">
            <label>{{ trans('stFatt.selAgent') }}</label>
            <select name="codag[]" class="form-control select2 selectAll" multiple="multiple" data-placeholder="Select Agents"
              style="width: 100%;">
              @foreach ($agentList as $agent)
              <option value="{{ $agent->codice }}"
                {{-- @if($agent->codice==$agente && strlen($agent->codice)==strlen($agente)) --}} @if(isset($fltAgents) &&
                in_array($agent->codice, $fltAgents, true))
                selected
                @endif
                >{{ $agent->descrizion or "Error $agent->codice - No Description" }}</option>
              @endforeach
            </select>
          </div>
          <div class="form-group">
            <label>&nbsp;
              <input type="checkbox" id="checkbox" class="selectAll"> &nbsp; Select All
            </label>
          </div>
          {{-- <div>
            <button type="submit" class="btn btn-primary">{{ trans('_message.submit') }}</button>
          </div>
        </form> --}}
      </div>
    </div>

    <div class="box box-default collapsed-box">
      <div class="box-header with-border">
      <h3 class="box-title" data-widget="collapse">{{ trans('doc.filter') }}</h3>
      <div class="box-tools pull-right">
        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        {{-- <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button> --}}
      </div>
      </div>
      <div class="box-body">
        @include('stFatt.partials.formIndex', ['gruppi' => $gruppi, 'agent' => $fltAgents, 'route' => 'stFatt::idxAg'])
      </div>
    </div>

    <div class="box box-default">
      <div class="box-body">
        <div>
          <button type="submit" class="btn btn-primary btn-block">{{ trans('_message.submit') }}</button>
        </div>
      </div>
    </div>
    </form>
  {{-- </div>


  <div class="col-lg-3"> --}}
    <div class="box box-default collapsed-box">
      <div class="box-header with-border">
        <h3 class="box-title" data-widget="collapse"><i class='fa fa-cloud-download'> </i> Download</h3>
        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
        </div>
      </div>
      <div class="box-body">
        <a type="button" class="btn btn-default btn-block" target="_blank" href="{{ route('schedaFat::PDF', ['codAg'=> null, 'fltAgents[]' => $fltAgents]) }}">Scheda Fatturato PDF</a>
        <a type="button" class="btn btn-default btn-block" target="_blank" href="{{ route('schedaFat::ZonePDF', ['codAg'=> null, 'fltAgents[]' => $fltAgents]) }}">Scheda Fatturato Zone PDF</a>
      </div>
    </div>

    <div class="box box-default">
      {{-- <div class="box-header with-border">
        <h3 class="box-title" data-widget="collapse">% Target</h3>
        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        </div>
      </div> --}}
      <div class="box-body text-center">
        @php
        $valMese = 'valore' . $prevMonth;
        $tgtMese = $fat_PY->isEmpty() ? 0 : $fat_PY->first()->$valMese;
        $fatMese = $fat_TY->isempty() ? 0 : $fat_TY->first()->$valMese;
        $deltaProg = $tgtMese==0 ? 0 : round((($fatMese) / $tgtMese) * 100,2);
        $deltaProg = $deltaProg > 100 ? 100 : $deltaProg;
        $colorDelta = ($deltaProg < 33) ? 'red' : ($deltaProg > 33 && $deltaProg < 66) ? 'orange' : 'green';
        @endphp
          <input type="text" class="knob" data-thickness="0.2" data-angleArc="250" data-angleOffset="-125" value="{{ $deltaProg }}" data-width="120" data-height="120" data-fgColor="{{ $colorDelta }}">

          <div class="knob-label"><strong>{{ trans('stFatt.targetGraph') }}</strong></div>
      </div>
    </div>
  </div>

  <div class="col-lg-9">
    <div class="nav-tabs-custom">
      <ul class="nav nav-tabs pull-right">
        <li class="active"><a href="#StatTot" data-toggle="tab" aria-expanded="true">{{ strtoupper(trans('stFatt.total')) }}</a></li>
        {{-- <li class=""><a href="#StatDet" data-toggle="tab" aria-expanded="false">{{ trans('stFatt.detailed') }}</a></li> --}}
      <li class="pull-left header"><i class="fa fa-th"></i> {{ trans('stFatt.statsTitle') }} - {{ $descrAg or "..." }}</li>
      </ul>
      <div class="tab-content">
        <div class="tab-pane active" id="StatTot">
        @include('stFatt.partials.tblTotAg', [
          'fat_TY' => $fat_TY,
          'fat_PY' => $fat_PY,
          'prevMonth' => $prevMonth,
          'thisYear' => $thisYear,
          'prevYear' => $prevYear
        ])
        </div>

        {{-- <div class="tab-pane" id="StatDet">

          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#Krona" data-toggle="tab" aria-expanded="true">Krona</a></li>
              <li class=""><a href="#Koblenz" data-toggle="tab" aria-expanded="false">Koblenz</a></li>
              <li class=""><a href="#Kubica" data-toggle="tab" aria-expanded="false">Kubica</a></li>
              <li class=""><a href="#Grass" data-toggle="tab" aria-expanded="false">Grass</a></li>
              <li class=""><a href="#Altro" data-toggle="tab" aria-expanded="false">{{ trans('stFatt.otherGroup') }}</a></li>
            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="Krona">
                @include('stFatt.partials.tblDetAg', [
                  'fatturato' => $fatDet->where('prodotto', 'KRONA'),
                ])
              </div>

              <div class="tab-pane" id="Koblenz">
                @include('stFatt.partials.tblDetAg', [
                  'fatturato' => $fatDet->where('prodotto', 'KOBLENZ'),
                ])
              </div>

              <div class="tab-pane" id="Kubica">
                @include('stFatt.partials.tblDetAg', [
                  'fatturato' => $fatDet->where('prodotto', 'KUBIKA'),
                ])
              </div>

              <div class="tab-pane" id="Grass">
                @include('stFatt.partials.tblDetAg', [
                  'fatturato' => $fatDet->where('prodotto', 'GRASS'),
                ])
              </div>

              <div class="tab-pane" id="Altro">
                @include('stFatt.partials.tblDetAg', [
                  'fatturato' => $fatDet->where('prodotto', 'ALTRO'),
                ])
              </div>
            </div>
          </div>

        </div> --}}
      </div>
    </div>
  </div>

  <div class="col-lg-12">
    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title" data-widget="collapse">{{ trans('stFatt.graphTitle') }}</h3>
        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        </div>
      </div>
      <div class="box-body chart-responsive">
        <div class="chart" id="revenue-chart" style="height: 300px;"></div>
      </div>
    </div>
  </div>

</div>
@endsection

@section('extra_script')
  @include('layouts.partials.scripts.iCheck')
  @include('layouts.partials.scripts.select2')
  @include('layouts.partials.scripts.datePicker')
  @include('layouts.partials.scripts.knob')
@endsection

@push('script-footer')
  
@endpush
