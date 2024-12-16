@extends('layouts.app')

@section('htmlheader_title')
- Portfolio
@endsection

@section('contentheader_title')
Portfolio - Dettaglio Clienti
@endsection

{{-- @section('contentheader_breadcrumb')
    {!! Breadcrumbs::render('agentStFat', $agente) !!}
@endsection --}}

@section('main-content')
<div class="row">
  <div class="col-lg-3">
    <form action="{{ route('Portfolio::portfolioAgByCustomer') }}" method="post">
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
        <a type="button" class="btn btn-default btn-block" target="_blank" href="{{ route('Portfolio::portfolioAgByCustomerPDF', [
                                            'codag' => $fltAgents,
                                            'year' => $thisYear,
                                            'cumulativo' => isset($cumulativo),
                                            'mese' => $mese,
                                            ]) }}">PDF Portafoglio Clienti</a>
      </div>
    </div>

    <div class="box box-default {{-- collapsed-box --}}">
      <div class="box-body">
        <a type="button" class="btn btn-default btn-block" target="_blank" href="{{ route('Portfolio::idxAg', [
                                            'codag' => $fltAgents,
                                            'year' => $thisYear,
                                            'cumulativo' => isset($cumulativo),
                                            'mese' => $mese,
                                            ]) }}">Portfolio - Gruppo Prodotti</a>
        {{-- <a type="button" class="btn btn-default btn-block" target="_blank" href="{{ route('Portfolio::portfolioAgByCustomer') }}">Portfolio - Clienti</a> --}}
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
          <table class="table table-hover table-striped dtTbls_portfolio" id="portfolioTbl" style="text-align: center;">
            <col width='22%'>
            <col width='12%'>
            <col width='12%'>
            <col width='10%'>
            <col width='10%'>
            <col width='10%'>
            <col width='10%'>
            <thead>
              <tr>
                <th colspan="3">&nbsp;</th>
                <th colspan="4" style="text-align: center;">
                  @if($cumulativo) {{ \Carbon\Carbon::createFromDate(null, 1, null)->format('F')}} - @endif
                  {{ \Carbon\Carbon::createFromDate(null, $mese, null)->format('F')}} {{ $thisYear }}</th>
              </tr>
              <tr>
                <th colspan="3">&nbsp;</th>
                <th style="text-align: center;">Orders Porfolio</th>
                {{-- {{ link_to_action()} --}}
                <th style="text-align: center;">Ddt</th>
                <th style="text-align: center;">Invoice</th>
                <th style="text-align: center;">Tot.</th>
              </tr>
            </thead>
            <tbody>
              @php
              $fat_TotCustomer_N = 0;
              $OrdAg_N = 0;
              $DdtAg_N = 0;
              $fatAg_N = 0;
              $TotAg_N = 0;
              @endphp
              @foreach ($portfolio as $key => $group)
              @php
              $fat_TotCustomer_N = (isset($group['totOrd']) ? $group['totOrd'] : 0) + (isset($group['totDdt']) ? $group['totDdt'] : 0) + (isset($group['totFat']) ? $group['totFat'] : 0);
              $OrdAg_N += (isset($group['totOrd']) ? $group['totOrd'] : 0);
              $DdtAg_N += (isset($group['totDdt']) ? $group['totDdt'] : 0);
              $fatAg_N += (isset($group['totFat']) ? $group['totFat'] : 0);
              $TotAg_N += $fat_TotCustomer_N;
              @endphp
              <tr>
                <td style="text-align: left;"><b>{{ $group['client']->descrizion }}</b> [<a href="{{ route('client::detail', $key ) }}" target="_blank">{{ $key }}</a>]</td>
                <td>{{ $group['client']->detZona->descrizion }}</td>
                <td>{{ $group['client']->detSect->descrizion }}</td>
                <td> {{ currency($group['totOrd'] ?? 0) }} </td>
                <td> {{ currency($group['totDdt'] ?? 0) }} </td>
                <td> {{ currency($group['totFat'] ?? 0) }} </td>
                <td> {{ currency($fat_TotCustomer_N) }} </td>
              </tr>                  
              @endforeach
            </tbody>
            <tfoot class="bg-gray">
              <tr>
                <th colspan="3">{{ strtoupper(trans('stFatt.granTot')) }}</th>
                <td> {{ currency($OrdAg_N ?? 0) }} </td>
                <td> {{ currency($DdtAg_N ?? 0) }} </td>
                <td> {{ currency($fatAg_N ?? 0) }} </td>
                <td> {{ currency($TotAg_N) }} </td>
              </tr>     
            </tfoot>
          </table>
          <hr>
          {{-- <table class="table table-hover table-striped" id="portfolioTbl" style="text-align: center;">
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
              $totOC = $totOC+$OCDIC;
              $totBO = $totBO+$BODIC;
              $totFT = $totFT+$FTDIC;
              $totPrevFT = $totPrevFT+$FTPrevDIC;
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
          </table> --}}
        </div>
      </div>
    </div>

    @endsection

    @section('extra_script')
    @include('layouts.partials.scripts.iCheck')
    @include('layouts.partials.scripts.select2')
    @include('layouts.partials.scripts.datePicker')
    @endsection