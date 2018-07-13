<table class="table table-hover table-striped" id="statFattTot" style="text-align: center;">
  <col width="80">
  <col width="50">
  <col width="50">
  <col width="50">
  <col width="50">
  <col width="50">
  <col width="50">
  <thead>
    <tr>
      <th rowspan="2">&nbsp;</th>
      <th colspan="3" style="text-align: center;">{{ trans('stFatt.monthly') }} {{ trans('stFatt.revenue')}}</th>
      <th colspan="3" style="text-align: center;">{{ trans('stFatt.cumulative') }} {{ trans('stFatt.revenue')}}</th>
    </tr>
    <tr>
      <th style="text-align: center;">{{ $thisYear or ""}}</th>
      <th style="text-align: center;">{{ $prevYear or "" }}</th>
      <th style="text-align: center;">% {{ trans('stFatt.missing') }}</th>

      <th style="text-align: center;">{{ $thisYear or "" }}</th>
      <th style="text-align: center;">{{ $prevYear or "" }}</th>
      <th style="text-align: center;">% {{ trans('stFatt.missing') }}</th>
    </tr>
  </thead>
  <tbody>
    @php
      $fat = $fat_TY->first();
      $tgt = $fat_PY->first();
      $fatMese = empty($fat) ? 0 : $fat->valore1;
      $tgtMese = empty($tgt) ? 0 : $tgt->valore1;
      $fatProg = $fatMese;
      $tgtProg = $tgtMese;
      $deltaMese = $tgtMese==0 ? 0 : round((($tgtMese-$fatMese) / $tgtMese) * 100,2);
      $deltaProg = $tgtProg==0 ? 0 : round((($tgtProg-$fatProg) / $tgtProg) * 100,2);
    @endphp
    <tr>
      <th>{{ trans('stFatt.january') }}
        @if ($prevMonth==1)
          &nbsp; >>
        @endif
      </th>
      <td><strong>{{ currency($fatMese) }}</strong></td>
      <td>{{ currency($tgtMese) }}</td>
      <td><strong>{{ $deltaMese }} %</strong></td>
      <td><strong>{{ currency($fatProg) }}</strong></td>
      <td>{{ currency($tgtProg) }}</td>
      <td><strong>{{ $deltaProg }} %</strong></td>
    </tr>
    @php
      $fatMese = empty($fat) ? 0 : $fat->valore2;
      $tgtMese = empty($tgt) ? 0 : $tgt->valore2;
      $fatProg += $fatMese;
      $tgtProg += $tgtMese;
      $deltaMese = $tgtMese==0 ? 0 : round((($tgtMese-$fatMese) / $tgtMese) * 100,2);
      $deltaProg = $tgtProg==0 ? 0 : round((($tgtProg-$fatProg) / $tgtProg) * 100,2);
    @endphp
    <tr>
      <th>{{ trans('stFatt.february') }}
        @if ($prevMonth==2)
          &nbsp; >>
        @endif
      </th>
      <td><strong>{{ currency($fatMese) }}</strong></td>
      <td>{{ currency($tgtMese) }}</td>
      <td><strong>{{ $deltaMese }} %</strong></td>
      <td><strong>{{ currency($fatProg) }}</strong></td>
      <td>{{ currency($tgtProg) }}</td>
      <td><strong>{{ $deltaProg }} %</strong></td>
    </tr>
    @php
      $fatMese = empty($fat) ? 0 : $fat->valore3;
      $tgtMese = empty($tgt) ? 0 : $tgt->valore3;
      $fatProg += $fatMese;
      $tgtProg += $tgtMese;
      $deltaMese = $tgtMese==0 ? 0 : round((($tgtMese-$fatMese) / $tgtMese) * 100,2);
      $deltaProg = $tgtProg==0 ? 0 : round((($tgtProg-$fatProg) / $tgtProg) * 100,2);
    @endphp
    <tr>
      <th>{{ trans('stFatt.march') }}
        @if ($prevMonth==3)
          &nbsp; >>
        @endif
      </th>
      <td><strong>{{ currency($fatMese) }}</strong></td>
      <td>{{ currency($tgtMese) }}</td>
      <td><strong>{{ $deltaMese }} %</strong></td>
      <td><strong>{{ currency($fatProg) }}</strong></td>
      <td>{{ currency($tgtProg) }}</td>
      <td><strong>{{ $deltaProg }} %</strong></td>
    </tr>
    @php
      $fatMese = empty($fat) ? 0 : $fat->valore4;
      $tgtMese = empty($tgt) ? 0 : $tgt->valore4;
      $fatProg += $fatMese;
      $tgtProg += $tgtMese;
      $deltaMese = $tgtMese==0 ? 0 : round((($tgtMese-$fatMese) / $tgtMese) * 100,2);
      $deltaProg = $tgtProg==0 ? 0 : round((($tgtProg-$fatProg) / $tgtProg) * 100,2);
    @endphp
    <tr>
      <th>{{ trans('stFatt.april') }}
        @if ($prevMonth==4)
          &nbsp; >>
        @endif
      </th>
      <td><strong>{{ currency($fatMese) }}</strong></td>
      <td>{{ currency($tgtMese) }}</td>
      <td><strong>{{ $deltaMese }} %</strong></td>
      <td><strong>{{ currency($fatProg) }}</strong></td>
      <td>{{ currency($tgtProg) }}</td>
      <td><strong>{{ $deltaProg }} %</strong></td>
    </tr>
    @php
      $fatMese = empty($fat) ? 0 : $fat->valore5;
      $tgtMese = empty($tgt) ? 0 : $tgt->valore5;
      $fatProg += $fatMese;
      $tgtProg += $tgtMese;
      $deltaMese = $tgtMese==0 ? 0 : round((($tgtMese-$fatMese) / $tgtMese) * 100,2);
      $deltaProg = $tgtProg==0 ? 0 : round((($tgtProg-$fatProg) / $tgtProg) * 100,2);
    @endphp
    <tr>
      <th>{{ trans('stFatt.may') }}
        @if ($prevMonth==5)
          &nbsp; >>
        @endif
      </th>
      <td><strong>{{ currency($fatMese) }}</strong></td>
      <td>{{ currency($tgtMese) }}</td>
      <td><strong>{{ $deltaMese }} %</strong></td>
      <td><strong>{{ currency($fatProg) }}</strong></td>
      <td>{{ currency($tgtProg) }}</td>
      <td><strong>{{ $deltaProg }} %</strong></td>
    </tr>
    @php
      $fatMese = empty($fat) ? 0 : $fat->valore6;
      $tgtMese = empty($tgt) ? 0 : $tgt->valore6;
      $fatProg += $fatMese;
      $tgtProg += $tgtMese;
      $deltaMese = $tgtMese==0 ? 0 : round((($tgtMese-$fatMese) / $tgtMese) * 100,2);
      $deltaProg = $tgtProg==0 ? 0 : round((($tgtProg-$fatProg) / $tgtProg) * 100,2);
    @endphp
    <tr>
      <th>{{ trans('stFatt.june') }}
        @if ($prevMonth==6)
          &nbsp; >>
        @endif
      </th>
      <td><strong>{{ currency($fatMese) }}</strong></td>
      <td>{{ currency($tgtMese) }}</td>
      <td><strong>{{ $deltaMese }} %</strong></td>
      <td><strong>{{ currency($fatProg) }}</strong></td>
      <td>{{ currency($tgtProg) }}</td>
      <td><strong>{{ $deltaProg }} %</strong></td>
    </tr>
    @php
      $fatMese = empty($fat) ? 0 : $fat->valore7;
      $tgtMese = empty($tgt) ? 0 : $tgt->valore7;
      $fatProg += $fatMese;
      $tgtProg += $tgtMese;
      $deltaMese = $tgtMese==0 ? 0 : round((($tgtMese-$fatMese) / $tgtMese) * 100,2);
      $deltaProg = $tgtProg==0 ? 0 : round((($tgtProg-$fatProg) / $tgtProg) * 100,2);
    @endphp
    <tr>
      <th>{{ trans('stFatt.july') }}
        @if ($prevMonth==7)
          &nbsp; >>
        @endif
      </th>
      <td><strong>{{ currency($fatMese) }}</strong></td>
      <td>{{ currency($tgtMese) }}</td>
      <td><strong>{{ $deltaMese }} %</strong></td>
      <td><strong>{{ currency($fatProg) }}</strong></td>
      <td>{{ currency($tgtProg) }}</td>
      <td><strong>{{ $deltaProg }} %</strong></td>
    </tr>
    @php
      $fatMese = empty($fat) ? 0 : $fat->valore8;
      $tgtMese = empty($tgt) ? 0 : $tgt->valore8;
      $fatProg += $fatMese;
      $tgtProg += $tgtMese;
      $deltaMese = $tgtMese==0 ? 0 : round((($tgtMese-$fatMese) / $tgtMese) * 100,2);
      $deltaProg = $tgtProg==0 ? 0 : round((($tgtProg-$fatProg) / $tgtProg) * 100,2);
    @endphp
    <tr>
      <th>{{ trans('stFatt.august') }}
        @if ($prevMonth==8)
          &nbsp; >>
        @endif
      </th>
      <td><strong>{{ currency($fatMese) }}</strong></td>
      <td>{{ currency($tgtMese) }}</td>
      <td><strong>{{ $deltaMese }} %</strong></td>
      <td><strong>{{ currency($fatProg) }}</strong></td>
      <td>{{ currency($tgtProg) }}</td>
      <td><strong>{{ $deltaProg }} %</strong></td>
    </tr>
    @php
      $fatMese = empty($fat) ? 0 : $fat->valore9;
      $tgtMese = empty($tgt) ? 0 : $tgt->valore9;
      $fatProg += $fatMese;
      $tgtProg += $tgtMese;
      $deltaMese = $tgtMese==0 ? 0 : round((($tgtMese-$fatMese) / $tgtMese) * 100,2);
      $deltaProg = $tgtProg==0 ? 0 : round((($tgtProg-$fatProg) / $tgtProg) * 100,2);
    @endphp
    <tr>
      <th>{{ trans('stFatt.september') }}
        @if ($prevMonth==9)
          &nbsp; >>
        @endif
      </th>
      <td><strong>{{ currency($fatMese) }}</strong></td>
      <td>{{ currency($tgtMese) }}</td>
      <td><strong>{{ $deltaMese }} %</strong></td>
      <td><strong>{{ currency($fatProg) }}</strong></td>
      <td>{{ currency($tgtProg) }}</td>
      <td><strong>{{ $deltaProg }} %</strong></td>
    </tr>
    @php
      $fatMese = empty($fat) ? 0 : $fat->valore10;
      $tgtMese = empty($tgt) ? 0 : $tgt->valore10;
      $fatProg += $fatMese;
      $tgtProg += $tgtMese;
      $deltaMese = $tgtMese==0 ? 0 : round((($tgtMese-$fatMese) / $tgtMese) * 100,2);
      $deltaProg = $tgtProg==0 ? 0 : round((($tgtProg-$fatProg) / $tgtProg) * 100,2);
    @endphp
    <tr>
      <th>{{ trans('stFatt.october') }}
        @if ($prevMonth==10)
          &nbsp; >>
        @endif
      </th>
      <td><strong>{{ currency($fatMese) }}</strong></td>
      <td>{{ currency($tgtMese) }}</td>
      <td><strong>{{ $deltaMese }} %</strong></td>
      <td><strong>{{ currency($fatProg) }}</strong></td>
      <td>{{ currency($tgtProg) }}</td>
      <td><strong>{{ $deltaProg }} %</strong></td>
    </tr>
    @php
      $fatMese = empty($fat) ? 0 : $fat->valore11;
      $tgtMese = empty($tgt) ? 0 : $tgt->valore11;
      $fatProg += $fatMese;
      $tgtProg += $tgtMese;
      $deltaMese = $tgtMese==0 ? 0 : round((($tgtMese-$fatMese) / $tgtMese) * 100,2);
      $deltaProg = $tgtProg==0 ? 0 : round((($tgtProg-$fatProg) / $tgtProg) * 100,2);
    @endphp
    <tr>
      <th>{{ trans('stFatt.november') }}
        @if ($prevMonth==11)
          &nbsp; >>
        @endif
        </th>
      <td><strong>{{ currency($fatMese) }}</strong></td>
      <td>{{ currency($tgtMese) }}</td>
      <td><strong>{{ $deltaMese }} %</strong></td>
      <td><strong>{{ currency($fatProg) }}</strong></td>
      <td>{{ currency($tgtProg) }}</td>
      <td><strong>{{ $deltaProg }} %</strong></td>
    </tr>
    @php
      $fatMese = empty($fat) ? 0 : $fat->valore12;
      $tgtMese = empty($tgt) ? 0 : $tgt->valore12;
      $fatProg += $fatMese;
      $tgtProg += $tgtMese;
      $deltaMese = $tgtMese==0 ? 0 : round((($tgtMese-$fatMese) / $tgtMese) * 100,2);
      $deltaProg = $tgtProg==0 ? 0 : round((($tgtProg-$fatProg) / $tgtProg) * 100,2);
    @endphp
    <tr>
      <th>{{ trans('stFatt.december') }}
        @if ($prevMonth==12)
          &nbsp; >>
        @endif
        </th>
      <td><strong>{{ currency($fatMese) }}</strong></td>
      <td>{{ currency($tgtMese) }}</td>
      <td><strong>{{ $deltaMese }} %</strong></td>
      <td><strong>{{ currency($fatProg) }}</strong></td>
      <td>{{ currency($tgtProg) }}</td>
      <td><strong>{{ $deltaProg }} %</strong></td>
    </tr>
  </tbody>
  <tfoot class="bg-gray">
    <tr>
      <th>{{ strtoupper(trans('stFatt.granTot')) }}</th>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td><strong>{{ currency($fatProg) }}</strong></td>
      <td><strong>{{ currency($tgtProg) }}</strong></td>
      <td><strong>{{ $deltaProg }} %</strong></td>
    </tr>
  </tfoot>
</table>
