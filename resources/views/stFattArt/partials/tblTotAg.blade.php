<table class="table table-hover table-striped dtTbls_stat" id="statFattTot" style="text-align: center;">
  <col width="180"> <!--Cliente-->
  @if($yearBack==4) <col width="50"> <!--Val N-4--> @endif
  @if($yearBack>=3) <col width="50"> <!--Val N-3--> @endif
  @if($yearBack>=2) <col width="50"> <!--Val N-2--> @endif
  <col width="50">
  <col width="50"> <!--Val N-->
  <thead>
    <tr>
      <th rowspan="1">&nbsp;</th>
      <th colspan="{!!1+$yearback!!}" style="text-align: center;">{{ trans('stFatt.revenue')}}</th>
    </tr>
    <tr>
      <th style="text-align: center;">Cliente</th>
      @if($yearBack==4) <th style="text-align: center;">{!! $thisYear-4 !!}</th> @endif
      @if($yearBack>=3) <th style="text-align: center;">{!! $thisYear-3 !!}</th> @endif
      @if($yearBack>=2) <th style="text-align: center;">{!! $thisYear-2 !!}</th> @endif
      <th style="text-align: center;">{!! $thisYear-1 !!}</th>
      <th style="text-align: center;">{!! $thisYear !!}</th>
    </tr>
  </thead>
  <tbody>
    @php
    $fat_TotN4 = 0;
    $fat_TotN3 = 0;
    $fat_TotN2 = 0;
    $fat_TotN1 = 0;
    $fat_TotN = 0;
    @endphp
    @foreach ($fatList as $fatCustomer)
    <tr>
      <th>{{$fatCustomer->codicecf}} - {{$fatCustomer->ragionesociale}}</th>
      @if($yearBack==4) <td><strong>{{ currency($fatCustomer->fatN4) }}</strong></td>@endif
      @if($yearBack>=3) <td><strong>{{ currency($fatCustomer->fatN3) }}</strong></td>@endif
      @if($yearBack>=2) <td><strong>{{ currency($fatCustomer->fatN2) }}</strong></td>@endif
      <td><strong>{{ currency($fatCustomer->fatN1) }}</strong></td>
      <td><strong>{{ currency($fatCustomer->fatN) }}</strong></td>      
    </tr>
    @php
    $fat_TotN4 += ($yearBack==4) ? $fatCustomer->fatN4 : 0;
    $fat_TotN3 += ($yearBack>=3) ? $fatCustomer->fatN3 : 0;
    $fat_TotN2 += ($yearBack>=2) ? $fatCustomer->fatN2 : 0;
    $fat_TotN1 += $fatCustomer->fatN1;
    $fat_TotN += $fatCustomer->fatN;
    @endphp
    @endforeach
  </tbody>
  <tfoot class="bg-gray">
    <tr>
      <th>{{ strtoupper(trans('stFatt.granTot')) }}</th>
      @if($yearBack==4) <td><strong>{{ currency($fat_TotN4) }}</strong></td>@endif
      @if($yearBack>=3) <td><strong>{{ currency($fat_TotN3) }}</strong></td>@endif
      @if($yearBack>=2) <td><strong>{{ currency($fat_TotN2) }}</strong></td>@endif
      <td><strong>{{ currency($fat_TotN1) }}</strong></td>
      <td><strong>{{ currency($fat_TotN) }}</strong></td>
    </tr>
  </tfoot>
</table>
