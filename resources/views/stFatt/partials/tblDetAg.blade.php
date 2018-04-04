<table class="table table-hover table-striped" id="statFattTot" style="text-align: center;">
  <col width="10">
  <col width="80">
  <col width="50">
  <col width="50">
  <thead>
    <tr>
      <th rowspan="2">&nbsp;</th>
      <th rowspan="2">&nbsp;</th>
      <th colspan="2" style="text-align: center;">{{ strtoupper(trans('stFatt.revenue')) }}</th>
    </tr>
    <tr>
      <th style="text-align: center;">{{ trans('stFatt.monthly') }}</th>
      <th style="text-align: center;">{{ trans('stFatt.cumulative') }}</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($fatturato as $val)
      <tr>
        <th colspan="4">{{ $val->gruppo }} - {{ $val->grpProd->descrizion }}</th>
      </tr>
      @php
        $fatMese = $val->valore1;
        $fatProg = $fatMese;
      @endphp
      <tr>
        <th>&nbsp;</th>
        <th>{{ trans('stFatt.january') }}</th>
        <td><strong>{{ currency($fatMese) }}</strong></td>
        <td><strong>{{ currency($fatProg) }}</strong></td>
      </tr>
      @php
        $fatMese = $val->valore2;
        $fatProg += $fatMese;
      @endphp
      <tr>
        <th>&nbsp;</th>
        <th>{{ trans('stFatt.february') }}</th>
        <td><strong>{{ currency($fatMese) }}</strong></td>
        <td><strong>{{ currency($fatProg) }}</strong></td>
      </tr>
      @php
        $fatMese = $val->valore3;
        $fatProg += $fatMese;
      @endphp
      <tr>
        <th>&nbsp;</th>
        <th>{{ trans('stFatt.march') }}</th>
        <td><strong>{{ currency($fatMese) }}</strong></td>
        <td><strong>{{ currency($fatProg) }}</strong></td>
      </tr>
      @php
        $fatMese = $val->valore4;
        $fatProg += $fatMese;
      @endphp
      <tr>
        <th>&nbsp;</th>
        <th>{{ trans('stFatt.april') }}</th>
        <td><strong>{{ currency($fatMese) }}</strong></td>
        <td><strong>{{ currency($fatProg) }}</strong></td>
      </tr>
      @php
        $fatMese = $val->valore5;
        $fatProg += $fatMese;
      @endphp
      <tr>
        <th>&nbsp;</th>
        <th>{{ trans('stFatt.may') }}</th>
        <td><strong>{{ currency($fatMese) }}</strong></td>
        <td><strong>{{ currency($fatProg) }}</strong></td>
      </tr>
      @php
        $fatMese = $val->valore6;
        $fatProg += $fatMese;
      @endphp
      <tr>
        <th>&nbsp;</th>
        <th>{{ trans('stFatt.june') }}</th>
        <td><strong>{{ currency($fatMese) }}</strong></td>
        <td><strong>{{ currency($fatProg) }}</strong></td>
      </tr>
      @php
        $fatMese = $val->valore7;
        $fatProg += $fatMese;
      @endphp
      <tr>
        <th>&nbsp;</th>
        <th>{{ trans('stFatt.july') }}</th>
        <td><strong>{{ currency($fatMese) }}</strong></td>
        <td><strong>{{ currency($fatProg) }}</strong></td>
      </tr>
      @php
        $fatMese = $val->valore8;
        $fatProg += $fatMese;
      @endphp
      <tr>
        <th>&nbsp;</th>
        <th>{{ trans('stFatt.august') }}</th>
        <td><strong>{{ currency($fatMese) }}</strong></td>
        <td><strong>{{ currency($fatProg) }}</strong></td>
      </tr>
      @php
        $fatMese = $val->valore9;
        $fatProg += $fatMese;
      @endphp
      <tr>
        <th>&nbsp;</th>
        <th>{{ trans('stFatt.september') }}</th>
        <td><strong>{{ currency($fatMese) }}</strong></td>
        <td><strong>{{ currency($fatProg) }}</strong></td>
      </tr>
      @php
        $fatMese = $val->valore10;
        $fatProg += $fatMese;
      @endphp
      <tr>
        <th>&nbsp;</th>
        <th>{{ trans('stFatt.october') }}</th>
        <td><strong>{{ currency($fatMese) }}</strong></td>
        <td><strong>{{ currency($fatProg) }}</strong></td>
      </tr>
      @php
        $fatMese = $val->valore11;
        $fatProg += $fatMese;
      @endphp
      <tr>
        <th>&nbsp;</th>
        <th>{{ trans('stFatt.november') }}</th>
        <td><strong>{{ currency($fatMese) }}</strong></td>
        <td><strong>{{ currency($fatProg) }}</strong></td>
      </tr>
      @php
        $fatMese = $val->valore12;
        $fatProg += $fatMese;
      @endphp
      <tr>
        <th>&nbsp;</th>
        <th>{{ trans('stFatt.december') }}</th>
        <td><strong>{{ currency($fatMese) }}</strong></td>
        <td><strong>{{ currency($fatProg) }}</strong></td>
      </tr>
    @endforeach
  </tbody>
  {{-- <tfoot class="bg-gray">
    <tr>
      <th>&nbsp;</th>
      <th>TOTALE</th>
      <td></td>
      <td><strong>{{ currency($fatProg) }}</strong></td>
    </tr>
  </tfoot> --}}
</table>
