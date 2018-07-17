
@extends('layouts.app')

@section('htmlheader_title')
    - {{ trans('stFatt.headTitle_agt') }}
@endsection

@section('contentheader_title')
    Area Statistics
@endsection

@section('contentheader_breadcrumb')
    {!! Breadcrumbs::render('agentStFat', $agente) !!}
@endsection

@section('main-content')
<div class="row">
  <div class="col-lg-3">
    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title" data-widget="collapse">{{ trans('stFatt.agent') }}</h3>
        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          {{-- <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button> --}}
        </div>
      </div>
      <div class="box-body">
        <form action="{{ route('stFatt::idxZone') }}" method="post">
          <input type="hidden" name="_token" value="{{ csrf_token() }}">
          <div class="form-group">
            <label>{{ trans('stFatt.selAgent') }}</label>
            <select name="codag" class="form-control select2" style="width: 100%;">
              {{-- <option value=""> </option> --}}
              @foreach ($agents as $agent)
                <option value="{{ $agent->codice }}"
                  @if($agent->codice==$agente)
                      selected
                  @endif
                  >{{ $agent->descrizion }}</option>
              @endforeach
            </select>
          </div>
          <div>
            <button type="submit" class="btn btn-primary">{{ trans('_message.submit') }}</button>
          </div>
        </form>
      </div>
    </div>

    <div class="box box-default collapsed-box">
      <div class="box-header with-border">
        <h3 class="box-title" data-widget="collapse"><i class='fa fa-cloud-download'> </i> Download</h3>
        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
        </div>
      </div>
      <div class="box-body">
        <a type="button" class="btn btn-default btn-block" target="_blank" href="{{ route('schedaFat::PDF', $agente) }}">Scheda Fatturato PDF</a>
        <a type="button" class="btn btn-default btn-block" target="_blank" href="{{ route('schedaFat::ZonePDF', $agente) }}">Scheda Fatturato Zone PDF</a>
      </div>
    </div>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title" data-widget="collapse">{{ trans('doc.filter') }}</h3>
        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          {{-- <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button> --}}
        </div>
      </div>
      <div class="box-body">
        @include('stFatt.partials.formIndex', ['gruppi' => $gruppi, 'agent' => $agent, 'route' => 'stFatt::idxZone'])
      </div>
    </div>

  </div>

  <div class="col-lg-9">
    <div class="nav-tabs-custom">
      <ul class="nav nav-tabs pull-right">
        <li class="active"><a href="#StatTot" data-toggle="tab" aria-expanded="true">{{ strtoupper(trans('stFatt.total')) }}</a></li>
        {{-- <li class=""><a href="#StatDet" data-toggle="tab" aria-expanded="false">{{ trans('stFatt.detailed') }}</a></li> --}}
        <li class="pull-left header"><i class="fa fa-th"></i> {{ trans('stFatt.statsTitle') }} - Area</li>
      </ul>
      <div class="tab-content">
        <div class="tab-pane active" id="StatTot">
        @include('stFatt.partials.tblZone', [
          'fatZone' => $fatZone,
          'fatTot' => $fatTot,
          'thisYear' => $thisYear,
          'prevYear' => $prevYear
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


