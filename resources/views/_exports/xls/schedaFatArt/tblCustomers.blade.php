<table class="table table-hover table-striped dtTbls_stat{{$yearBack+1}}" id="statFattTot">
  <col width="10">
  <!--Cliente-->
  <col>
  <!--Rag.Soc-->
  @if($yearBack==4)
  <col width="150">
  <!--Val N-4--> @endif
  @if($yearBack>=3)
  <col width="150">
  <!--Val N-3--> @endif
  @if($yearBack>=2)
  <col width="150">
  <!--Val N-2--> @endif
  <col width="150">
  <col width="150">
  <!--Val N-->
  <col width="100">

  <thead>
    <tr>
      <th colspan="2">&nbsp;</th>
      <th colspan="{!!1+$yearBack!!}" style="text-align: center;">{{ trans('stFatt.revenue')}}</th>
    </tr>
    <tr>
      <th style="text-align: center;">Cliente</th>
      <th style="text-align: center;">Ragione Sociale</th>
      @if($yearBack==4) <th style="text-align: center;">{!! $thisYear-4 !!}</th> @endif
      @if($yearBack>=3) <th style="text-align: center;">{!! $thisYear-3 !!}</th> @endif
      @if($yearBack>=2) <th style="text-align: center;">{!! $thisYear-2 !!}</th> @endif
      <th style="text-align: center;">{!! $thisYear-1 !!}</th>
      <th style="text-align: center;">{!! $thisYear !!}
        @if(!$pariperiodo && !$onlyMese)
        ({{ trans('stFatt.'.strtolower(Carbon\Carbon::createFromDate(null, $mese, 25)->format('F'))) }})
        @endif</th>
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
      <td><a href="{{ route('client::detail', $fatCustomer->codicecf ) }}">{{$fatCustomer->codicecf}}</a></td>
      <td>{{$fatCustomer->ragionesociale}}</td>
      @if($yearBack==4) <td>{{ $fatCustomer->fatN4 }}</td>@endif
      @if($yearBack>=3) <td>{{ $fatCustomer->fatN3 }}</td>@endif
      @if($yearBack>=2) <td>{{ $fatCustomer->fatN2 }}</td>@endif
      <td>{{ $fatCustomer->fatN1 }}</td>
      <td>{{ $fatCustomer->fatN }}</td>
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
      <th colspan="2">{{ strtoupper(trans('stFatt.granTot')) }}</th>
      @if($yearBack==4) <td><strong>{{ $fat_TotN4 }}</strong></td>@endif
      @if($yearBack>=3) <td><strong>{{ $fat_TotN3 }}</strong></td>@endif
      @if($yearBack>=2) <td><strong>{{ $fat_TotN2 }}</strong></td>@endif
      <td><strong>{{ $fat_TotN1 }}</strong></td>
      <td><strong>{{ $fat_TotN }}</strong></td>
    </tr>
  </tfoot>
</table>