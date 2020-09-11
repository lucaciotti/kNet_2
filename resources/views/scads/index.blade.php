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
    @if(RedisUser::get('role')=='client')
    <div class="box box-default collapsed-box">
    @else
    <div class="box box-default">
    @endif
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

    <div class="box box-default collapsed-box">
      <div class="box-header with-border">
        <h3 class="box-title" data-widget="collapse"><i class='fa fa-cloud-download'> </i> Download</h3>
        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
        </div>
      </div>
      <div class="box-body">
        @if(RedisUser::get('role')=='agent')
          <a type="button" class="btn btn-default btn-block" target="_blank" href="{{ route('schedaScad::ProvPDF', [RedisUser::get('codag'), 'year' => Carbon\Carbon::now()->year]) }}"><b>Provvigioni</b> da Fatturare PDF</a>
          <a type="button" class="btn btn-default btn-block" target="_blank" href="{{ route('schedaScad::ProvPDF', [RedisUser::get('codag'), 'year' => (Carbon\Carbon::now()->year)-1]) }}"><b>Provvigioni</b> da Fatturare PDF - <b>Anno Prec.</b></a>
          <hr>
          @if(RedisUser::get('ditta_DB')=='kNet_fr')
          <a type="button" class="btn btn-default btn-block" target="_blank"
            href="{{ route('schedaScad::ProvPP_PDF', [RedisUser::get('codag'), 'year' => Carbon\Carbon::now()->year]) }}">
            <b>Commissions de Plate-forme</b> da Fatturare PDF</a>
          <a type="button" class="btn btn-default btn-block" target="_blank"
            href="{{ route('schedaScad::ProvPP_PDF', [RedisUser::get('codag'), 'year' => (Carbon\Carbon::now()->year)-1]) }}">
            <b>Commissions de Plate-forme</b> da Fatturare PDF - <b>Année Préc.</b></a>
          <hr>
          @endif
        @endif
        <a type="button" class="btn btn-default btn-block" target="_blank" href="{{ route('schedaScad::ScadPDF', RedisUser::get('codag')) }}">Scheda Scadenze PDF</a>
      </div>
    </div>

    @if(!in_array(RedisUser::get('role'), ['client', 'agent', 'superagent']))
      <div class="box box-default collapsed-box">
        <div class="box-header with-border">
          <h3 class="box-title" data-widget="collapse"><i class='fa fa-cloud-download'> </i> Provvigioni Agente</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
          </div>
        </div>
        <div class="box-body">     
          <div class="form-group">
            <label>{{ trans('stFatt.selAgent') }}</label>
            <select id='selectAgent' class="form-control select2" data-placeholder="Select Agents"
              style="width: 100%;">
              <option value='##' selected></option>
              @foreach ($agentList as $agente)
              <option value="{{ $agente->codice }}"
                >[{{ $agente->codice }}] {{ $agente->descrizion or "Error $agent->agente - No Description" }}</option>
              @endforeach
            </select>
          </div>     
          <a type="button" class="btn btn-default btn-block" id="btn_prov" target="_blank"
            href="{{ route('schedaScad::ProvPDF', ['##', 'year' => Carbon\Carbon::now()->year]) }}">
            <b>Provvigioni</b> da Fatturare PDF</a>
          <a type="button" class="btn btn-default btn-block" id="btn_prov2" target="_blank"
            href="{{ route('schedaScad::ProvPDF', ['##', 'year' => (Carbon\Carbon::now()->year)-1]) }}">
          <b>Provvigioni</b> da Fatturare PDF - <b>Anno Prec.</b></a>
          @if(RedisUser::get('ditta_DB')=='kNet_fr')
          <hr>
          <a type="button" class="btn btn-default btn-block" id="btn_prov3" target="_blank"
            href="{{ route('schedaScad::ProvPP_PDF', ['##', 'year' => Carbon\Carbon::now()->year]) }}">
          <b>Commissions de Plate-forme</b> da Fatturare PDF</a>
          <a type="button" class="btn btn-default btn-block" id="btn_prov4" target="_blank"
            href="{{ route('schedaScad::ProvPP_PDF', ['##', 'year' => (Carbon\Carbon::now()->year)-1]) }}">
          <b>Commissions de Plate-forme</b> da Fatturare PDF - <b>Année Préc.</b></a>
          @endif
        </div>
      </div>
    @endif

  </div>
</div>
@endsection

@section('extra_script')
  @include('layouts.partials.scripts.select2')
  @include('layouts.partials.scripts.datePicker')
  @include('layouts.partials.scripts.iCheck')
@endsection

@push('script-footer')
@if(RedisUser::get('ditta_DB')=='kNet_fr')
  <script>
    $(function () {
      $("#selectAgent").on('change', function() {
        var data = $("#selectAgent option:selected").val();
        $('#btn_prov').attr('href',($('#btn_prov').attr('href')).replace(/schedaProvPDF\/([a-zA-Z0-9_#]+)\//g,
        'schedaProvPDF/'+data+'/'));
        console.log('linkProv1 = '+$('#btn_prov').attr('href'));
        $('#btn_prov2').attr('href',($('#btn_prov2').attr('href')).replace(/schedaProvPDF\/([a-zA-Z0-9_#]+)\//g,
        'schedaProvPDF/'+data+'/'));
        console.log('linkProv2 = '+$('#btn_prov2').attr('href'));
        $('#btn_prov3').attr('href',($('#btn_prov3').attr('href')).replace(/schedaProvPP_PDF\/([a-zA-Z0-9_#]+)\//g,
        'schedaProvPP_PDF/'+data+'/'));
        console.log('linkProv3 = '+$('#btn_prov3').attr('href'));
        $('#btn_prov4').attr('href',($('#btn_prov4').attr('href')).replace(/schedaProvPP_PDF\/([a-zA-Z0-9_#]+)\//g,
        'schedaProvPP_PDF/'+data+'/'));
        console.log('linkProv4 = '+$('#btn_prov4').attr('href'));
      });
    });
  </script>
@else
  <script>
    $(function () {
      $("#selectAgent").on('change', function() {
        var data = $("#selectAgent option:selected").val();
        $('#btn_prov').attr('href',($('#btn_prov').attr('href')).replace(/schedaProvPDF\/([a-zA-Z0-9_#]+)\//g,
        'schedaProvPDF/'+data+'/'));
        console.log('linkProv1 = '+$('#btn_prov').attr('href'));
        $('#btn_prov2').attr('href',($('#btn_prov2').attr('href')).replace(/schedaProvPDF\/([a-zA-Z0-9_#]+)\//g,
        'schedaProvPDF/'+data+'/'));
        console.log('linkProv2 = '+$('#btn_prov2').attr('href'));
      });
    });
  </script>
@endif
@endpush