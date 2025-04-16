<table class="table table-hover table-striped" id="statFattTot" style="text-align: center;">
  <col width="80">
  <col width="50">
  <col width="50">
  <col width="50">
  <col width="50">
  <col width="50">
  <col width="50">
  <col width="50">
  <col width="50">
  <thead>
    <tr>
      <th rowspan="2">&nbsp;</th>
      <th colspan="4" style="text-align: center;">{{ trans('stFatt.monthly') }} {{ trans('stFatt.revenue')}}</th>
      <th colspan="4" style="text-align: center;">{{ trans('stFatt.cumulative') }} {{ trans('stFatt.revenue')}}</th>
    </tr>
    <tr>
      <th style="text-align: center;">{{ $thisYear or ""}}</th>
      <th style="text-align: center;">{{ $prevYear or "" }}</th>
      <th style="text-align: center;">Target</th>
      <th style="text-align: center;">%</th>
      
      <th style="text-align: center;">{{ $thisYear or "" }}</th>
      <th style="text-align: center;">{{ $prevYear or "" }}</th>
      <th style="text-align: center;">Target</th>
      <th style="text-align: center;">%</th>
    </tr>
  </thead>
  <tbody>
    @php
      $target = $target->first() ? $target->sum('targetko') : 0;
      $targetProg = 0;
      $fat = $fat_TY->first();
      $fatPy = $fat_PY->first();
      $fatMese = empty($fat) ? 0 : $fat->valore1;
      $fatPyMese = empty($fatPy) ? 0 : $fatPy->valore1;
      $fatProg = $fatMese;
      $fatPyProg = $fatPyMese;
      // $deltaMese = $fatPyMese==0 ? 0 : round((($fatPyMese-$fatMese) / $fatPyMese) * 100,2);
      // $deltaProg = $fatPyProg==0 ? 0 : round((($fatPyProg-$fatProg) / $fatPyProg) * 100,2);
      $deltaMese = $fatPyMese==0 ? 0 : round((($fatMese-$fatPyMese) / $fatPyMese) * 100,2);
      $deltaProg = $fatPyProg==0 ? 0 : round((($fatProg-$fatPyProg) / $fatPyProg) * 100,2);
      // MOLTO BRUTTO MA AGGIUNGO CODICE TARGET QUA SOTTO
      $perc = $perc_mese ? $perc_mese->where('mese',1)->first()->perc : 0;
      $targetMese = empty($target) ? 0 : round($target*($perc/100),2);
      $targetProg += $targetMese;
      // sovrascrivo deltaProd
      if ($targetProg>0){
        $deltaMese = $targetProg==0 ? 0 : round((($fatMese-$targetProg) / $targetProg) * 100,0);
        $deltaProg = $targetProg==0 ? 0 : round((($fatProg-$targetProg) / $targetProg) * 100,0);
      }
    @endphp
    <tr>
      <th>{{ trans('stFatt.january') }}
        @if ($prevMonth==1)
          &nbsp; >>
        @endif
      </th>
      <td><strong>{{ currency($fatMese) }}</strong></td>
      <td>{{ currency($fatPyMese) }}</td>
      <td>{{ currency($targetMese) }}</td>
      <td><strong>{{ $deltaMese }} %</strong></td>
      <td><strong>{{ currency($fatProg) }}</strong></td>
      <td>{{ currency($fatPyProg) }}</td>
      <td>{{ currency($targetProg) }}</td>
      <td><strong>{{ $deltaProg }} %</strong></td>
    </tr>
    @php
      $fatMese = empty($fat) ? 0 : $fat->valore2;
      $fatPyMese = empty($fatPy) ? 0 : $fatPy->valore2;
      $fatProg += $fatMese;
      $fatPyProg += $fatPyMese;
      // $deltaMese = $fatPyMese==0 ? 0 : round((($fatPyMese-$fatMese) / $fatPyMese) * 100,2);
      // $deltaProg = $fatPyProg==0 ? 0 : round((($fatPyProg-$fatProg) / $fatPyProg) * 100,2);
      $deltaMese = $fatPyMese==0 ? 0 : round((($fatMese-$fatPyMese) / $fatPyMese) * 100,2);
      $deltaProg = $fatPyProg==0 ? 0 : round((($fatProg-$fatPyProg) / $fatPyProg) * 100,2);
      // MOLTO BRUTTO MA AGGIUNGO CODICE TARGET QUA SOTTO
      $perc = $perc_mese ? $perc_mese->where('mese',2)->first()->perc : 0;
      $targetMese = empty($target) ? 0 : round($target*($perc/100),2);
      $targetProg += $targetMese;
      // sovrascrivo deltaProd
      if ($targetProg>0){
        $deltaMese = $targetProg==0 ? 0 : round((($fatMese-$targetProg) / $targetProg) * 100,2);
        $deltaProg = $targetProg==0 ? 0 : round((($fatProg-$targetProg) / $targetProg) * 100,2);
      }
    @endphp
    <tr>
      <th>{{ trans('stFatt.february') }}
        @if ($prevMonth==2)
          &nbsp; >>
        @endif
      </th><td><strong>{{ currency($fatMese) }}</strong></td>
      <td>{{ currency($fatPyMese) }}</td>
      <td>{{ currency($targetMese) }}</td>
      <td><strong>{{ $deltaMese }} %</strong></td>
      <td><strong>{{ currency($fatProg) }}</strong></td>
      <td>{{ currency($fatPyProg) }}</td>
      <td>{{ currency($targetProg) }}</td>
      <td><strong>{{ $deltaProg }} %</strong></td>
    </tr>
    @php
      $fatMese = empty($fat) ? 0 : $fat->valore3;
      $fatPyMese = empty($fatPy) ? 0 : $fatPy->valore3;
      $fatProg += $fatMese;
      $fatPyProg += $fatPyMese;
      // $deltaMese = $fatPyMese==0 ? 0 : round((($fatPyMese-$fatMese) / $fatPyMese) * 100,2);
      // $deltaProg = $fatPyProg==0 ? 0 : round((($fatPyProg-$fatProg) / $fatPyProg) * 100,2);
      $deltaMese = $fatPyMese==0 ? 0 : round((($fatMese-$fatPyMese) / $fatPyMese) * 100,2);
      $deltaProg = $fatPyProg==0 ? 0 : round((($fatProg-$fatPyProg) / $fatPyProg) * 100,2);
      // MOLTO BRUTTO MA AGGIUNGO CODICE TARGET QUA SOTTO
      $perc = $perc_mese ? $perc_mese->where('mese',3)->first()->perc : 0;
      $targetMese = empty($target) ? 0 : round($target*($perc/100),2);
      $targetProg += $targetMese;
      // sovrascrivo deltaProd
      if ($targetProg>0){
        $deltaMese = $targetProg==0 ? 0 : round((($fatMese-$targetProg) / $targetProg) * 100,2);
        $deltaProg = $targetProg==0 ? 0 : round((($fatProg-$targetProg) / $targetProg) * 100,2);
      }
    @endphp
    <tr>
      <th>{{ trans('stFatt.march') }}
        @if ($prevMonth==3)
          &nbsp; >>
        @endif
      </th>
      <td><strong>{{ currency($fatMese) }}</strong></td>
      <td>{{ currency($fatPyMese) }}</td>
      <td>{{ currency($targetMese) }}</td>
      <td><strong>{{ $deltaMese }} %</strong></td>
      <td><strong>{{ currency($fatProg) }}</strong></td>
      <td>{{ currency($fatPyProg) }}</td>
      <td>{{ currency($targetProg) }}</td>
      <td><strong>{{ $deltaProg }} %</strong></td>
    </tr>
    @php
      $fatMese = empty($fat) ? 0 : $fat->valore4;
      $fatPyMese = empty($fatPy) ? 0 : $fatPy->valore4;
      $fatProg += $fatMese;
      $fatPyProg += $fatPyMese;
      // $deltaMese = $fatPyMese==0 ? 0 : round((($fatPyMese-$fatMese) / $fatPyMese) * 100,2);
      // $deltaProg = $fatPyProg==0 ? 0 : round((($fatPyProg-$fatProg) / $fatPyProg) * 100,2);
      $deltaMese = $fatPyMese==0 ? 0 : round((($fatMese-$fatPyMese) / $fatPyMese) * 100,2);
      $deltaProg = $fatPyProg==0 ? 0 : round((($fatProg-$fatPyProg) / $fatPyProg) * 100,2);
      // MOLTO BRUTTO MA AGGIUNGO CODICE TARGET QUA SOTTO
      $perc = $perc_mese ? $perc_mese->where('mese',4)->first()->perc : 0;
      $targetMese = empty($target) ? 0 : round($target*($perc/100),2);
      $targetProg += $targetMese;
      // sovrascrivo deltaProd
      if ($targetProg>0){
        $deltaMese = $targetProg==0 ? 0 : round((($fatMese-$targetProg) / $targetProg) * 100,2);
        $deltaProg = $targetProg==0 ? 0 : round((($fatProg-$targetProg) / $targetProg) * 100,2);
      }
    @endphp
    <tr>
      <th>{{ trans('stFatt.april') }}
        @if ($prevMonth==4)
          &nbsp; >>
        @endif
      </th>
      <td><strong>{{ currency($fatMese) }}</strong></td>
      <td>{{ currency($fatPyMese) }}</td>
      <td>{{ currency($targetMese) }}</td>
      <td><strong>{{ $deltaMese }} %</strong></td>
      <td><strong>{{ currency($fatProg) }}</strong></td>
      <td>{{ currency($fatPyProg) }}</td>
      <td>{{ currency($targetProg) }}</td>
      <td><strong>{{ $deltaProg }} %</strong></td>
    </tr>
    @php
      $fatMese = empty($fat) ? 0 : $fat->valore5;
      $fatPyMese = empty($fatPy) ? 0 : $fatPy->valore5;
      $fatProg += $fatMese;
      $fatPyProg += $fatPyMese;
      // $deltaMese = $fatPyMese==0 ? 0 : round((($fatPyMese-$fatMese) / $fatPyMese) * 100,2);
      // $deltaProg = $fatPyProg==0 ? 0 : round((($fatPyProg-$fatProg) / $fatPyProg) * 100,2);
      $deltaMese = $fatPyMese==0 ? 0 : round((($fatMese-$fatPyMese) / $fatPyMese) * 100,2);
      $deltaProg = $fatPyProg==0 ? 0 : round((($fatProg-$fatPyProg) / $fatPyProg) * 100,2);
      // MOLTO BRUTTO MA AGGIUNGO CODICE TARGET QUA SOTTO
      $perc = $perc_mese ? $perc_mese->where('mese',5)->first()->perc : 0;
      $targetMese = empty($target) ? 0 : round($target*($perc/100),2);
      $targetProg += $targetMese;
      // sovrascrivo deltaProd
      if ($targetProg>0){
        $deltaMese = $targetProg==0 ? 0 : round((($fatMese-$targetProg) / $targetProg) * 100,2);
        $deltaProg = $targetProg==0 ? 0 : round((($fatProg-$targetProg) / $targetProg) * 100,2);
      }
    @endphp
    <tr>
      <th>{{ trans('stFatt.may') }}
        @if ($prevMonth==5)
          &nbsp; >>
        @endif
      </th>
      <td><strong>{{ currency($fatMese) }}</strong></td>
      <td>{{ currency($fatPyMese) }}</td>
      <td>{{ currency($targetMese) }}</td>
      <td><strong>{{ $deltaMese }} %</strong></td>
      <td><strong>{{ currency($fatProg) }}</strong></td>
      <td>{{ currency($fatPyProg) }}</td>
      <td>{{ currency($targetProg) }}</td>
      <td><strong>{{ $deltaProg }} %</strong></td>
    </tr>
    @php
      $fatMese = empty($fat) ? 0 : $fat->valore6;
      $fatPyMese = empty($fatPy) ? 0 : $fatPy->valore6;
      $fatProg += $fatMese;
      $fatPyProg += $fatPyMese;
      // $deltaMese = $fatPyMese==0 ? 0 : round((($fatPyMese-$fatMese) / $fatPyMese) * 100,2);
      // $deltaProg = $fatPyProg==0 ? 0 : round((($fatPyProg-$fatProg) / $fatPyProg) * 100,2);
      $deltaMese = $fatPyMese==0 ? 0 : round((($fatMese-$fatPyMese) / $fatPyMese) * 100,2);
      $deltaProg = $fatPyProg==0 ? 0 : round((($fatProg-$fatPyProg) / $fatPyProg) * 100,2);
      // MOLTO BRUTTO MA AGGIUNGO CODICE TARGET QUA SOTTO
      $perc = $perc_mese ? $perc_mese->where('mese',6)->first()->perc : 0;
      $targetMese = empty($target) ? 0 : round($target*($perc/100),2);
      $targetProg += $targetMese;
      // sovrascrivo deltaProd
      if ($targetProg>0){
        $deltaMese = $targetProg==0 ? 0 : round((($fatMese-$targetProg) / $targetProg) * 100,2);
        $deltaProg = $targetProg==0 ? 0 : round((($fatProg-$targetProg) / $targetProg) * 100,2);
      }
    @endphp
    <tr>
      <th>{{ trans('stFatt.june') }}
        @if ($prevMonth==6)
          &nbsp; >>
        @endif
      </th>
      <td><strong>{{ currency($fatMese) }}</strong></td>
      <td>{{ currency($fatPyMese) }}</td>
      <td>{{ currency($targetMese) }}</td>
      <td><strong>{{ $deltaMese }} %</strong></td>
      <td><strong>{{ currency($fatProg) }}</strong></td>
      <td>{{ currency($fatPyProg) }}</td>
      <td>{{ currency($targetProg) }}</td>
      <td><strong>{{ $deltaProg }} %</strong></td>
    </tr>
    @php
      $fatMese = empty($fat) ? 0 : $fat->valore7;
      $fatPyMese = empty($fatPy) ? 0 : $fatPy->valore7;
      $fatProg += $fatMese;
      $fatPyProg += $fatPyMese;
      // $deltaMese = $fatPyMese==0 ? 0 : round((($fatPyMese-$fatMese) / $fatPyMese) * 100,2);
      // $deltaProg = $fatPyProg==0 ? 0 : round((($fatPyProg-$fatProg) / $fatPyProg) * 100,2);
      $deltaMese = $fatPyMese==0 ? 0 : round((($fatMese-$fatPyMese) / $fatPyMese) * 100,2);
      $deltaProg = $fatPyProg==0 ? 0 : round((($fatProg-$fatPyProg) / $fatPyProg) * 100,2);
      // MOLTO BRUTTO MA AGGIUNGO CODICE TARGET QUA SOTTO
      $perc = $perc_mese ? $perc_mese->where('mese',7)->first()->perc : 0;
      $targetMese = empty($target) ? 0 : round($target*($perc/100),2);
      $targetProg += $targetMese;
      // sovrascrivo deltaProd
      if ($targetProg>0){
        $deltaMese = $targetProg==0 ? 0 : round((($fatMese-$targetProg) / $targetProg) * 100,2);
        $deltaProg = $targetProg==0 ? 0 : round((($fatProg-$targetProg) / $targetProg) * 100,2);
      }
    @endphp
    <tr>
      <th>{{ trans('stFatt.july') }}
        @if ($prevMonth==7)
          &nbsp; >>
        @endif
      </th>
      <td><strong>{{ currency($fatMese) }}</strong></td>
      <td>{{ currency($fatPyMese) }}</td>
      <td>{{ currency($targetMese) }}</td>
      <td><strong>{{ $deltaMese }} %</strong></td>
      <td><strong>{{ currency($fatProg) }}</strong></td>
      <td>{{ currency($fatPyProg) }}</td>
      <td>{{ currency($targetProg) }}</td>
      <td><strong>{{ $deltaProg }} %</strong></td>
    </tr>
    @php
      $fatMese = empty($fat) ? 0 : $fat->valore8;
      $fatPyMese = empty($fatPy) ? 0 : $fatPy->valore8;
      $fatProg += $fatMese;
      $fatPyProg += $fatPyMese;
      // $deltaMese = $fatPyMese==0 ? 0 : round((($fatPyMese-$fatMese) / $fatPyMese) * 100,2);
      // $deltaProg = $fatPyProg==0 ? 0 : round((($fatPyProg-$fatProg) / $fatPyProg) * 100,2);
      $deltaMese = $fatPyMese==0 ? 0 : round((($fatMese-$fatPyMese) / $fatPyMese) * 100,2);
      $deltaProg = $fatPyProg==0 ? 0 : round((($fatProg-$fatPyProg) / $fatPyProg) * 100,2);
      // MOLTO BRUTTO MA AGGIUNGO CODICE TARGET QUA SOTTO
      $perc = $perc_mese ? $perc_mese->where('mese',8)->first()->perc : 0;
      $targetMese = empty($target) ? 0 : round($target*($perc/100),2);
      $targetProg += $targetMese;
      // sovrascrivo deltaProd
      if ($targetProg>0){
        $deltaMese = $targetProg==0 ? 0 : round((($fatMese-$targetProg) / $targetProg) * 100,2);
        $deltaProg = $targetProg==0 ? 0 : round((($fatProg-$targetProg) / $targetProg) * 100,2);
      }
    @endphp
    <tr>
      <th>{{ trans('stFatt.august') }}
        @if ($prevMonth==8)
          &nbsp; >>
        @endif
      </th>
      <td><strong>{{ currency($fatMese) }}</strong></td>
      <td>{{ currency($fatPyMese) }}</td>
      <td>{{ currency($targetMese) }}</td>
      <td><strong>{{ $deltaMese }} %</strong></td>
      <td><strong>{{ currency($fatProg) }}</strong></td>
      <td>{{ currency($fatPyProg) }}</td>
      <td>{{ currency($targetProg) }}</td>
      <td><strong>{{ $deltaProg }} %</strong></td>
    </tr>
    @php
      $fatMese = empty($fat) ? 0 : $fat->valore9;
      $fatPyMese = empty($fatPy) ? 0 : $fatPy->valore9;
      $fatProg += $fatMese;
      $fatPyProg += $fatPyMese;
      // $deltaMese = $fatPyMese==0 ? 0 : round((($fatPyMese-$fatMese) / $fatPyMese) * 100,2);
      // $deltaProg = $fatPyProg==0 ? 0 : round((($fatPyProg-$fatProg) / $fatPyProg) * 100,2);
      $deltaMese = $fatPyMese==0 ? 0 : round((($fatMese-$fatPyMese) / $fatPyMese) * 100,2);
      $deltaProg = $fatPyProg==0 ? 0 : round((($fatProg-$fatPyProg) / $fatPyProg) * 100,2);
      // MOLTO BRUTTO MA AGGIUNGO CODICE TARGET QUA SOTTO
      $perc = $perc_mese ? $perc_mese->where('mese',9)->first()->perc : 0;
      $targetMese = empty($target) ? 0 : round($target*($perc/100),2);
      $targetProg += $targetMese;
      // sovrascrivo deltaProd
      if ($targetProg>0){
        $deltaMese = $targetProg==0 ? 0 : round((($fatMese-$targetProg) / $targetProg) * 100,2);
        $deltaProg = $targetProg==0 ? 0 : round((($fatProg-$targetProg) / $targetProg) * 100,2);
      }
    @endphp
    <tr>
      <th>{{ trans('stFatt.september') }}
        @if ($prevMonth==9)
          &nbsp; >>
        @endif
      </th>
      <td><strong>{{ currency($fatMese) }}</strong></td>
      <td>{{ currency($fatPyMese) }}</td>
      <td>{{ currency($targetMese) }}</td>
      <td><strong>{{ $deltaMese }} %</strong></td>
      <td><strong>{{ currency($fatProg) }}</strong></td>
      <td>{{ currency($fatPyProg) }}</td>
      <td>{{ currency($targetProg) }}</td>
      <td><strong>{{ $deltaProg }} %</strong></td>
    </tr>
    @php
      $fatMese = empty($fat) ? 0 : $fat->valore10;
      $fatPyMese = empty($fatPy) ? 0 : $fatPy->valore10;
      $fatProg += $fatMese;
      $fatPyProg += $fatPyMese;
      // $deltaMese = $fatPyMese==0 ? 0 : round((($fatPyMese-$fatMese) / $fatPyMese) * 100,2);
      // $deltaProg = $fatPyProg==0 ? 0 : round((($fatPyProg-$fatProg) / $fatPyProg) * 100,2);
      $deltaMese = $fatPyMese==0 ? 0 : round((($fatMese-$fatPyMese) / $fatPyMese) * 100,2);
      $deltaProg = $fatPyProg==0 ? 0 : round((($fatProg-$fatPyProg) / $fatPyProg) * 100,2);
      // MOLTO BRUTTO MA AGGIUNGO CODICE TARGET QUA SOTTO
      $perc = $perc_mese ? $perc_mese->where('mese',10)->first()->perc : 0;
      $targetMese = empty($target) ? 0 : round($target*($perc/100),2);
      $targetProg += $targetMese;
      // sovrascrivo deltaProd
      if ($targetProg>0){
        $deltaMese = $targetProg==0 ? 0 : round((($fatMese-$targetProg) / $targetProg) * 100,2);
        $deltaProg = $targetProg==0 ? 0 : round((($fatProg-$targetProg) / $targetProg) * 100,2);
      }
    @endphp
    <tr>
      <th>{{ trans('stFatt.october') }}
        @if ($prevMonth==10)
          &nbsp; >>
        @endif
      </th>
      <td><strong>{{ currency($fatMese) }}</strong></td>
      <td>{{ currency($fatPyMese) }}</td>
      <td>{{ currency($targetMese) }}</td>
      <td><strong>{{ $deltaMese }} %</strong></td>
      <td><strong>{{ currency($fatProg) }}</strong></td>
      <td>{{ currency($fatPyProg) }}</td>
      <td>{{ currency($targetProg) }}</td>
      <td><strong>{{ $deltaProg }} %</strong></td>
    </tr>
    @php
      $fatMese = empty($fat) ? 0 : $fat->valore11;
      $fatPyMese = empty($fatPy) ? 0 : $fatPy->valore11;
      $fatProg += $fatMese;
      $fatPyProg += $fatPyMese;
      // $deltaMese = $fatPyMese==0 ? 0 : round((($fatPyMese-$fatMese) / $fatPyMese) * 100,2);
      // $deltaProg = $fatPyProg==0 ? 0 : round((($fatPyProg-$fatProg) / $fatPyProg) * 100,2);
      $deltaMese = $fatPyMese==0 ? 0 : round((($fatMese-$fatPyMese) / $fatPyMese) * 100,2);
      $deltaProg = $fatPyProg==0 ? 0 : round((($fatProg-$fatPyProg) / $fatPyProg) * 100,2);
      // MOLTO BRUTTO MA AGGIUNGO CODICE TARGET QUA SOTTO
      $perc = $perc_mese ? $perc_mese->where('mese',11)->first()->perc : 0;
      $targetMese = empty($target) ? 0 : round($target*($perc/100),2);
      $targetProg += $targetMese;
      // sovrascrivo deltaProd
      if ($targetProg>0){
        $deltaMese = $targetProg==0 ? 0 : round((($fatMese-$targetProg) / $targetProg) * 100,2);
        $deltaProg = $targetProg==0 ? 0 : round((($fatProg-$targetProg) / $targetProg) * 100,2);
      }
    @endphp
    <tr>
      <th>{{ trans('stFatt.november') }}
        @if ($prevMonth==11)
          &nbsp; >>
        @endif
        </th>
      <td><strong>{{ currency($fatMese) }}</strong></td>
      <td>{{ currency($fatPyMese) }}</td>
      <td>{{ currency($targetMese) }}</td>
      <td><strong>{{ $deltaMese }} %</strong></td>
      <td><strong>{{ currency($fatProg) }}</strong></td>
      <td>{{ currency($fatPyProg) }}</td>
      <td>{{ currency($targetProg) }}</td>
      <td><strong>{{ $deltaProg }} %</strong></td>
    </tr>
    @php
      $fatMese = empty($fat) ? 0 : $fat->valore12;
      $fatPyMese = empty($fatPy) ? 0 : $fatPy->valore12;
      $fatProg += $fatMese;
      $fatPyProg += $fatPyMese;
      // $deltaMese = $fatPyMese==0 ? 0 : round((($fatPyMese-$fatMese) / $fatPyMese) * 100,2);
      // $deltaProg = $fatPyProg==0 ? 0 : round((($fatPyProg-$fatProg) / $fatPyProg) * 100,2);
      $deltaMese = $fatPyMese==0 ? 0 : round((($fatMese-$fatPyMese) / $fatPyMese) * 100,2);
      $deltaProg = $fatPyProg==0 ? 0 : round((($fatProg-$fatPyProg) / $fatPyProg) * 100,2);
      // MOLTO BRUTTO MA AGGIUNGO CODICE TARGET QUA SOTTO
      $perc = $perc_mese ? $perc_mese->where('mese',12)->first()->perc : 0;
      $targetMese = empty($target) ? 0 : round($target*($perc/100),2);
      $targetProg += $targetMese;
      // sovrascrivo deltaProd
      if ($targetProg>0){
        $deltaMese = $targetProg==0 ? 0 : round((($fatMese-$targetProg) / $targetProg) * 100,2);
        $deltaProg = $targetProg==0 ? 0 : round((($fatProg-$targetProg) / $targetProg) * 100,2);
      }
    @endphp
    <tr>
      <th>{{ trans('stFatt.december') }}
        @if ($prevMonth==12)
          &nbsp; >>
        @endif
        </th>
      <td><strong>{{ currency($fatMese) }}</strong></td>
      <td>{{ currency($fatPyMese) }}</td>
      <td>{{ currency($targetMese) }}</td>
      <td><strong>{{ $deltaMese }} %</strong></td>
      <td><strong>{{ currency($fatProg) }}</strong></td>
      <td>{{ currency($fatPyProg) }}</td>
      <td>{{ currency($targetProg) }}</td>
      <td><strong>{{ $deltaProg }} %</strong></td>
    </tr>
  </tbody>
  <tfoot class="bg-gray">
    <tr>
      <th>{{ strtoupper(trans('stFatt.granTot')) }}</th>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td><strong>{{ currency($fatProg) }}</strong></td>
      <td><strong>{{ currency($fatPyProg) }}</strong></td>
      <td><strong>{{ currency($targetProg) }}</strong></td>
      <td><strong>{{ $deltaProg }} %</strong></td>
    </tr>
  </tfoot>
</table>
