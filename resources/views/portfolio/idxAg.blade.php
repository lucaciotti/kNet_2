@extends('layouts.app')

@section('htmlheader_title')
- Portfolio
@endsection

@section('contentheader_title')
Agents Portfolio
@endsection

{{-- @section('contentheader_breadcrumb')
    {!! Breadcrumbs::render('agentStFat', $agente) !!}
@endsection --}}

@section('main-content')
<div class="row">
  <div class="col-lg-3">
    <form action="{{ route('Portfolio::idxAg') }}" method="post">
      <input type="hidden" name="_token" value="{{ csrf_token() }}">

      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title" data-widget="collapse">{{ trans('stFatt.agent') }}</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          </div>
        </div>
        <div class="box-body">
          <div class="form-group">
            <label>{{ trans('stFatt.selAgent') }}</label>
            <select name="codag[]" class="form-control select2 selectAll" multiple="multiple" data-placeholder="Select Agents"
              style="width: 100%;">
              @foreach ($agents as $agente)
              <option value="{{ $agente->codice }}" @if(isset($fltAgents) && in_array($agente->codice, $fltAgents,
                true))
                selected
                @endif
                >[{{ $agente->codice }}] {{ $agente->descrizion or "Error $agent->agente - No Description" }}</option>
              @endforeach
            </select>
          </div>
          <div class="form-group">
            <label>&nbsp;
              <input type="checkbox" id="checkbox" class="selectAll"> &nbsp; Select All
            </label>
          </div>
        </div>
      </div>

      <div class="box box-default collapsed-box">
        <div class="box-header with-border">
          <h3 class="box-title" data-widget="collapse">Parametri Stampa</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            {{-- <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button> --}}
          </div>
        </div>
        <div class="box-body">
          <div class="form-group">
            <label>Select Month</label>
            <select name="mese" class="form-control select2" data-placeholder="Select Mese" style="width: 100%;">
              <option value="1" @if($mese==1) selected @endif>{{ __('stFatt.january')}}</option>
              <option value="2" @if($mese==2) selected @endif>{{ __('stFatt.february')}}</option>
              <option value="3" @if($mese==3) selected @endif>{{ __('stFatt.march')}}</option>
              <option value="4" @if($mese==4) selected @endif>{{ __('stFatt.april')}}</option>
              <option value="5" @if($mese==5) selected @endif>{{ __('stFatt.may')}}</option>
              <option value="6" @if($mese==6) selected @endif>{{ __('stFatt.june')}}</option>
              <option value="7" @if($mese==7) selected @endif>{{ __('stFatt.july')}}</option>
              <option value="8" @if($mese==8) selected @endif>{{ __('stFatt.august')}}</option>
              <option value="9" @if($mese==9) selected @endif>{{ __('stFatt.september')}}</option>
              <option value="10" @if($mese==10) selected @endif>{{ __('stFatt.october')}}</option>
              <option value="11" @if($mese==11) selected @endif>{{ __('stFatt.november')}}</option>
              <option value="12" @if($mese==12) selected @endif>{{ __('stFatt.december')}}</option>
            </select>
          </div>
          <div class="form-group">
            <label>Select Year</label>
            @php
            $year = (Carbon\Carbon::now()->year)-1;
            @endphp
            <select name="year" class="form-control select2" data-placeholder="Select Year" style="width: 100%;">
              @for ($i = 0; $i < 3; $i++) <option value="{{$year+$i}}" @if($thisYear==$year+$i) selected @endif>
                {{ $year+$i }}</option>
                @endfor
            </select>
          </div>
        </div>
      </div>

      <div class="box box-default">
        <div class="box-body">
          <button type="submit" class="btn btn-primary btn-block">{{ trans('_message.submit') }}</button>
        </div>
      </div>
    
    </form>
  </div>

  <div class="col-lg-9">
    <div class="nav-tabs-custom">
      <ul class="nav nav-tabs pull-right">
        <li class="active"><a href="#StatTot" data-toggle="tab"
            aria-expanded="true">{{ strtoupper(trans('stFatt.total')) }}</a></li>
        {{-- <li class=""><a href="#StatDet" data-toggle="tab" aria-expanded="false">{{ trans('stFatt.detailed') }}</a>
        </li> --}}
        <li class="pull-left header"><i class="fa fa-th"></i> Situazione Portfolio</li>
      </ul>
      <div class="tab-content">
        <div class="tab-pane active" id="portfolioStats">
          <table class="table table-hover table-striped" id="portfolioTbl" style="text-align: center;">
            <col width='22%'>
            <col width='15%'>
            <col width='15%'>
            <col width='15%'>
            <col width='15%'>
            <col width='3%'>
            <col width='15%'>
            <thead>
              <tr>
                <th colspan="1">&nbsp;</th>
                <th colspan="4" style="text-align: center;">
                  {{ \Carbon\Carbon::createFromDate(null, $mese, null)->format('F')}} {{ $thisYear }}</th>
                <th colspan="1">&nbsp;</th>
                <th colspan="1" style="text-align: center;">
                  {{ \Carbon\Carbon::createFromDate(null, $mese, null)->format('F')}} {{ $prevYear }}</th>
              </tr>
              <tr>
                <th colspan="1">&nbsp;</th>
                <th style="text-align: center;"> <a href="{{$urlOrders}}" target="_blank">Orders Porfolio</a></th>
                {{-- {{ link_to_action()} --}}
                <th style="text-align: center;"><a href="{{$urlDdts}}" target="_blank">Ddt</a></th>
                <th style="text-align: center;"><a href="{{$urlInvoices}}" target="_blank">Invoice</a></th>
                <th style="text-align: center;">Tot.</th>
                <th colspan="1">|</th>
                <th style="text-align: center;">Tot. Invoice</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <th>Krona</th>
                <td> {{ currency($OCKrona) }} </td>
                <td> {{ currency($BOKrona) }} </td>
                <td> {{ currency($FTKrona) }} </td>
                <td> {{ currency($OCKrona+$BOKrona+$FTKrona) }} </td>
                <th colspan="1">|</th>
                <td> {{ currency($FTPrevKrona) }} </td>
              </tr>
              <tr>
                <th>Koblenz</th>
                <td> {{ currency($OCKoblenz) }} </td>
                <td> {{ currency($BOKoblenz) }} </td>
                <td> {{ currency($FTKoblenz) }} </td>
                <td> {{ currency($OCKoblenz+$BOKoblenz+$FTKoblenz) }} </td>
                <th colspan="1">|</th>
                <td> {{ currency($FTPrevKoblenz) }} </td>
              </tr>
              <tr>
                <th>Kubica</th>
                <td> {{ currency($OCKubica) }} </td>
                <td> {{ currency($BOKubica) }} </td>
                <td> {{ currency($FTKubica) }} </td>
                <td> {{ currency($OCKubica+$BOKubica+$FTKubica) }} </td>
                <th colspan="1">|</th>
                <td> {{ currency($FTPrevKubica) }} </td>
              </tr>
              <tr>
                <th>Atomika</th>
                <td> {{ currency($OCAtomika) }} </td>
                <td> {{ currency($BOAtomika) }} </td>
                <td> {{ currency($FTAtomika) }} </td>
                <td> {{ currency($OCAtomika+$BOAtomika+$FTAtomika) }} </td>
                <th colspan="1">|</th>
                <td> {{ currency($FTPrevAtomika) }} </td>
              </tr>
              @if(RedisUser::get('ditta_DB')=='kNet_es')
              <tr>
                <th>Planet</th>
                <td> {{ currency($OCPlanet) }} </td>
                <td> {{ currency($BOPlanet) }} </td>
                <td> {{ currency($FTPlanet) }} </td>
                <td> {{ currency($OCPlanet+$BOPlanet+$FTPlanet) }} </td>
                <th colspan="1">|</th>
                <td> {{ currency($FTPrevPlanet) }} </td>
              </tr>
              @endif
            </tbody>
            <tfoot class="bg-gray">
              @php
              $totOC = $OCKrona+$OCKoblenz+$OCKubica+$OCAtomika+$OCPlanet;
              $totBO = $BOKrona+$BOKoblenz+$BOKubica+$BOAtomika+$BOPlanet;
              $totFT = $FTKrona+$FTKoblenz+$FTKubica+$FTAtomika+$FTPlanet;

              @endphp
              <tr>
                <th>TOTALE</th>
                <td> {{ currency($totOC) }} </td>
                <td> {{ currency($totBO) }} </td>
                <td> {{ currency($totFT) }} </td>
                <td> {{ currency($totOC+$totBO+$totFT) }} </td>
                <th colspan="1">|</th>
                <td> {{ currency($FTPrevKrona+$FTPrevKoblenz+$FTPrevKubica+$FTPrevAtomika+$FTPrevPlanet) }} </td>
              </tr>
            </tfoot>
          </table>
          <hr>
          <table class="table table-hover table-striped" id="portfolioTbl" style="text-align: center;">
            <col width='22%'>
            <col width='15%'>
            <col width='15%'>
            <col width='15%'>
            <col width='15%'>
            <col width='3%'>
            <col width='15%'>
            <tbody>
              <tr>
                <th colspan="7"> --> Escluso da Calcolo Portfolio </th>
              </tr>
              <tr>
                <th>Diciture (es. Acconti)</th>
                <td> {{ currency($OCDIC) }} </td>
                <td> {{ currency($BODIC) }} </td>
                <td> {{ currency($FTDIC) }} </td>
                <td> {{ currency($OCDIC+$BODIC+$FTDIC) }} </td>
                <th colspan="1">|</th>
                <td> {{ currency($FTPrevDIC) }} </td>
              </tr>
              <tr>
                <td colspan="7"></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    @endsection

    @section('extra_script')
    @include('layouts.partials.scripts.iCheck')
    @include('layouts.partials.scripts.select2')
    @include('layouts.partials.scripts.datePicker')
    @endsection