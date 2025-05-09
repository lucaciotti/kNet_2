@extends('layouts.app')

@section('htmlheader_title')
- Portfolio
@endsection

@section('contentheader_title')
Portfolio - Gruppo Prodotti
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

          <div class="form-group">
            <label>&nbsp;
              <input type="checkbox" id="cumulativo" name="cumulativo" @if($cumulativo) checked @endif> &nbsp; Cumulativo (Tutti i mesi)
            </label>
          </div>
        </div>
      </div>

      <div class="box box-default">
        <div class="box-body">
          <button type="submit" class="btn btn-primary btn-block">{{ trans('_message.submit') }}</button>
        </div>
      </div>
    
    </form>

    <div class="box box-default {{-- collapsed-box --}}">
      <div class="box-header with-border">
        <h3 class="box-title" data-widget="collapse"><i class='fa fa-cloud-download'> </i> Download</h3>
        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
        </div>
      </div>
      <div class="box-body">
        <a type="button" class="btn btn-default btn-block" target="_blank" href="{{ route('Portfolio::portfolioListOCandXC', [
                                            'codag' => $fltAgents,
                                            'year' => $thisYear,
                                            'cumulativo' => $cumulativo,
                                            'mese' => $mese,
                                            ]) }}">PDF Portafoglio Doc (da Evadere)</a>
      </div>
    </div>

    <div class="box box-default {{-- collapsed-box --}}">
      <div class="box-body">
        {{-- <a type="button" class="btn btn-default btn-block" target="_blank" href="{{ route('Portfolio::idxAg') }}">Portfolio - Gruppo Prodotti</a> --}}
        <a type="button" class="btn btn-default btn-block" href="{{ route('Portfolio::portfolioAgByCustomer', [
                                            'codag' => $fltAgents,
                                            'year' => $thisYear,
                                            'cumulativo' => $cumulativo,
                                            'mese' => $mese,
                                            ]) }}">Portfolio - Dettaglio Clienti</a>
      </div>
    </div>
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
                <th style="text-align: center;"><a href="{{$urlInvoicesPrec}}" target="_blank">Tot. Invoice</a></th>
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
              @if ($OCBonusKrona!=0 || $BOBonusKrona!=0 || $FTBonusKrona!=0 || $FTPrevBonusKrona!=0)
                <tr>
                  <th>&nbsp -> Bonus Krona</th>
                  <td> {{ currency($OCBonusKrona) }} </td>
                  <td> {{ currency($BOBonusKrona) }} </td>
                  <td> {{ currency($FTBonusKrona) }} </td>
                  <td> {{ currency($OCBonusKrona+$BOBonusKrona+$FTBonusKrona) }} </td>
                  <th colspan="1">|</th>
                  <td> {{ currency($FTPrevBonusKrona) }} </td>
                </tr>                  
              @endif
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
              @if ($OCBonusKoblenz!=0 || $BOBonusKoblenz!=0 || $FTBonusKoblenz!=0 || $FTPrevBonusKoblenz!=0)
                <tr>
                  <th>&nbsp -> Bonus Koblenz</th>
                  <td> {{ currency($OCBonusKoblenz) }} </td>
                  <td> {{ currency($BOBonusKoblenz) }} </td>
                  <td> {{ currency($FTBonusKoblenz) }} </td>
                  <td> {{ currency($OCBonusKoblenz+$BOBonusKoblenz+$FTBonusKoblenz) }} </td>
                  <th colspan="1">|</th>
                  <td> {{ currency($FTPrevBonusKoblenz) }} </td>
                </tr>                  
              @endif
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
              $totOC = $OCKrona+$OCBonusKrona+$OCKoblenz+$OCBonusKoblenz+$OCKubica+$OCAtomika+$OCPlanet;
              $totBO = $BOKrona+$BOKoblenz+$BOBonusKrona+$BOBonusKoblenz+$BOKubica+$BOAtomika+$BOPlanet;
              $totFT = $FTKrona+$FTKoblenz+$FTBonusKrona+$FTBonusKoblenz+$FTKubica+$FTAtomika+$FTPlanet;
              $totPrevFT = $FTPrevKrona+$FTPrevKoblenz+$FTPrevKubica+$FTPrevAtomika+$FTPrevPlanet+$FTPrevBonusKrona+$FTPrevBonusKoblenz;
              @endphp
              <tr>
                <th>TOTALE PORTFOLIO PRODOTTO</th>
                <td> {{ currency($totOC) }} </td>
                <td> {{ currency($totBO) }} </td>
                <td> {{ currency($totFT) }} </td>
                <td> {{ currency($totOC+$totBO+$totFT) }} </td>
                <th colspan="1">|</th>
                <td> {{ currency($totPrevFT) }} </td>
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
                <th colspan="7"> <strong>&nbsp&nbsp -> Escluso da Calcolo Portfolio</strong> </th>
              </tr>
              <tr>
                <th>Spinoff</th>
                <td> {{ currency($OCSpinOff) }} </td>
                <td> {{ currency($BOSpinOff) }} </td>
                <td> {{ currency($FTSpinOff) }} </td>
                <td> {{ currency($OCSpinOff+$BOSpinOff+$FTSpinOff) }} </td>
                <th colspan="1">|</th>
                <td> {{ currency($FTPrevSpinOff) }} </td>
              </tr>
              <tr>
                <th>Diciture (es. Acconti,..)</th>
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
            <tfoot class="bg-gray">
              @php
              $totOC = $totOC+$OCDIC+$OCSpinOff;
              $totBO = $totBO+$BODIC+$BOSpinOff;
              $totFT = $totFT+$FTDIC+$FTSpinOff;
              $totPrevFT = $totPrevFT+$FTPrevDIC+$FTPrevSpinOff;
              @endphp
              <tr>
                <th>TOTALE GENERALE</th>
                <td> {{ currency($totOC) }} </td>
                <td> {{ currency($totBO) }} </td>
                <td> {{ currency($totFT) }} </td>
                <td> {{ currency($totOC+$totBO+$totFT) }} </td>
                <th colspan="1">|</th>
                <td> {{ currency($totPrevFT) }} </td>
              </tr>
            </tfoot>
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