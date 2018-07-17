<table>
  <col width="50">
  <col width="100">
  <col width="50">
  <col width="80">
  <col width="80">
  <col width="80">
  <col width="80">
  <col width="80">
  <col width="80">
  <col width="80">
  <col width="80">
  <col width="80">
  <col width="80">
  <col width="80">
  <col width="80">
    <thead>
        <tr style="text-align: center;">
            <th>Zona</th>
            <th>Cliente</th>
            <th></th>
            <th>Gennaio</th>
            <th>Febbraio</th>
            <th>Marzo</th>
            <th>Aprile</th>
            <th>Maggio</th>
            <th>Giugno</th>
            <th>Luglio</th>
            <th>Agosto</th>
            <th>Settembre</th>
            <th>Ottobre</th>
            <th>Novembre</th>
            <th>Dicembre</th>
            <th>TOT.</th>
        </tr>
    </thead>
    @php
        $i = 1;
    @endphp
    @foreach($fatZone as $fatZona)
        @php
            $descZona = ($fatZona->first()->client) ? $fatZona->first()->client->detZona->descrizion : "NO ZONA";
            $i++;
        @endphp
        <tbody>
            <tr class="clickable" data-toggle="collapse" data-target="#zona-{{$i}}" aria-expanded="false" aria-controls="#zona-{{$i}}">
            <td colspan="16"><h3>--> {{$descZona}}</h3><hr></td>
            </tr>
        </tbody>
        
        <tbody id="zona-{{$i}}" class="collapse">
            @foreach($fatZona as $fatCli)
                @if($fatCli)
                @php
                    $customer = ($fatCli->client) ? $fatCli->codicecf." - ".$fatCli->client->descrizion : "NO CLIENTE";
                    $settore =  ($fatCli->client) ? $fatCli->client->settore." - ".$fatCli->client->detSect->descrizion : "NO SETTORE";
                    
                    $trim_TY_1 = $fatCli->val_TY_1+$fatCli->val_TY_2+$fatCli->val_TY_3;
                    $trim_TY_2 = $fatCli->val_TY_4+$fatCli->val_TY_5+$fatCli->val_TY_6;
                    $trim_TY_3 = $fatCli->val_TY_7+$fatCli->val_TY_8+$fatCli->val_TY_9;
                    $trim_TY_4 = $fatCli->val_TY_10+$fatCli->val_TY_11+$fatCli->val_TY_12;
                    $tot_TY =$trim_TY_1+$trim_TY_2+$trim_TY_3+$trim_TY_4;
                    
                    $trim_PY_1 = $fatCli->val_PY_1+$fatCli->val_PY_2+$fatCli->val_PY_3;
                    $trim_PY_2 = $fatCli->val_PY_4+$fatCli->val_PY_5+$fatCli->val_PY_6;
                    $trim_PY_3 = $fatCli->val_PY_7+$fatCli->val_PY_8+$fatCli->val_PY_9;
                    $trim_PY_4 = $fatCli->val_PY_10+$fatCli->val_PY_11+$fatCli->val_PY_12;
                    $tot_PY =$trim_PY_1+$trim_PY_2+$trim_PY_3+$trim_PY_4;

                @endphp
                <tr>
                    <td rowspan="4"></td>
                    <td rowspan="4"><strong>{{ $customer }}</strong> <br> ({{$settore}})</td>
                </tr>
                <tr style="text-align: right;">
                    <td style="text-align: center;">{{ $thisYear }}</td>
                    <td><strong>{{ currency($fatCli->val_TY_1) }}</strong></td>
                    <td><strong>{{ currency($fatCli->val_TY_2) }}</strong></td>
                    <td><strong>{{ currency($fatCli->val_TY_3) }}</strong></td>
                    <td><strong>{{ currency($fatCli->val_TY_4) }}</strong></td>
                    <td><strong>{{ currency($fatCli->val_TY_5) }}</strong></td>
                    <td><strong>{{ currency($fatCli->val_TY_6) }}</strong></td>
                    <td><strong>{{ currency($fatCli->val_TY_7) }}</strong></td>
                    <td><strong>{{ currency($fatCli->val_TY_8) }}</strong></td>
                    <td><strong>{{ currency($fatCli->val_TY_9) }}</strong></td>
                    <td><strong>{{ currency($fatCli->val_TY_10) }}</strong></td>
                    <td><strong>{{ currency($fatCli->val_TY_11) }}</strong></td>
                    <td><strong>{{ currency($fatCli->val_TY_12) }}</strong></td>
                    <td><strong>{{ currency($tot_TY) }}</strong></td>                    
                </tr>
                <tr style="text-align: right;">
                    <td style="text-align: center;">{{ $prevYear }}</td>
                    <td>{{ currency($fatCli->val_PY_1) }}</td>
                    <td>{{ currency($fatCli->val_PY_2) }}</td>
                    <td>{{ currency($fatCli->val_PY_3) }}</td>
                    <td>{{ currency($fatCli->val_PY_4) }}</td>
                    <td>{{ currency($fatCli->val_PY_5) }}</td>
                    <td>{{ currency($fatCli->val_PY_6) }}</td>
                    <td>{{ currency($fatCli->val_PY_7) }}</td>
                    <td>{{ currency($fatCli->val_PY_8) }}</td>
                    <td>{{ currency($fatCli->val_PY_9) }}</td>
                    <td>{{ currency($fatCli->val_PY_10) }}</td>
                    <td>{{ currency($fatCli->val_PY_11) }}</td>
                    <td>{{ currency($fatCli->val_PY_12) }}</td>
                    <td><strong>{{ currency($tot_PY) }}</strong></td> 
                </tr>
                <tr class="bg-gray" style="text-align: right;">
                    <td style="text-align: center;">Delta</td>
                    <td>{{ currency($fatCli->val_TY_1-$fatCli->val_PY_1) }}</td>
                    <td>{{ currency($fatCli->val_TY_2-$fatCli->val_PY_2) }}</td>
                    <td>{{ currency($fatCli->val_TY_3-$fatCli->val_PY_3) }}</td>
                    <td>{{ currency($fatCli->val_TY_4-$fatCli->val_PY_4) }}</td>
                    <td>{{ currency($fatCli->val_TY_5-$fatCli->val_PY_5) }}</td>
                    <td>{{ currency($fatCli->val_TY_6-$fatCli->val_PY_6) }}</td>
                    <td>{{ currency($fatCli->val_TY_7-$fatCli->val_PY_7) }}</td>
                    <td>{{ currency($fatCli->val_TY_8-$fatCli->val_PY_8) }}</td>
                    <td>{{ currency($fatCli->val_TY_9-$fatCli->val_PY_9) }}</td>
                    <td>{{ currency($fatCli->val_TY_10-$fatCli->val_PY_10) }}</td>
                    <td>{{ currency($fatCli->val_TY_11-$fatCli->val_PY_11) }}</td>
                    <td>{{ currency($fatCli->val_TY_12-$fatCli->val_PY_12) }}</td>
                    <td><strong>{{ currency($tot_TY-$tot_PY) }}</strong></td> 
                </tr>
                <tr>
                    <td></td>
                    <td colspan="15"><hr></td>
                </tr>
                @endif
            @endforeach
        </tbody>
    @endforeach
    <tfoot class="bg-gray">
        @if($fatTot)
        @php            
            $trim_TY_1 = $fatTot->val_TY_1+$fatTot->val_TY_2+$fatTot->val_TY_3;
            $trim_TY_2 = $fatTot->val_TY_4+$fatTot->val_TY_5+$fatTot->val_TY_6;
            $trim_TY_3 = $fatTot->val_TY_7+$fatTot->val_TY_8+$fatTot->val_TY_9;
            $trim_TY_4 = $fatTot->val_TY_10+$fatTot->val_TY_11+$fatTot->val_TY_12;
            $tot_TY =$trim_TY_1+$trim_TY_2+$trim_TY_3+$trim_TY_4;
            
            $trim_PY_1 = $fatTot->val_PY_1+$fatTot->val_PY_2+$fatTot->val_PY_3;
            $trim_PY_2 = $fatTot->val_PY_4+$fatTot->val_PY_5+$fatTot->val_PY_6;
            $trim_PY_3 = $fatTot->val_PY_7+$fatTot->val_PY_8+$fatTot->val_PY_9;
            $trim_PY_4 = $fatTot->val_PY_10+$fatTot->val_PY_11+$fatTot->val_PY_12;
            $tot_PY =$trim_PY_1+$trim_PY_2+$trim_PY_3+$trim_PY_4;

        @endphp
        @php
            $i++;
        @endphp
        <tbody>
            <tr class="clickable" data-toggle="collapse" data-target="#zona-{{$i}}" aria-expanded="false" aria-controls="#zona-{{$i}}">
            <td colspan="16"><h3>--> {{ strtoupper(trans('stFatt.granTot')) }}</h3><hr></td>
            </tr>
        </tbody>
        
        <tbody id="zona-{{$i}}" class="collapse">
            <tr>
                <th rowspan="4" colspan="2"></th>
            </tr>
            <tr style="text-align: right;">
                <td style="text-align: center;">{{ $thisYear }}</td>
                <td><strong>{{ currency($fatTot->val_TY_1) }}</strong></td>
                <td><strong>{{ currency($fatTot->val_TY_2) }}</strong></td>
                <td><strong>{{ currency($fatTot->val_TY_3) }}</strong></td>
                <td><strong>{{ currency($fatTot->val_TY_4) }}</strong></td>
                <td><strong>{{ currency($fatTot->val_TY_5) }}</strong></td>
                <td><strong>{{ currency($fatTot->val_TY_6) }}</strong></td>
                <td><strong>{{ currency($fatTot->val_TY_7) }}</strong></td>
                <td><strong>{{ currency($fatTot->val_TY_8) }}</strong></td>
                <td><strong>{{ currency($fatTot->val_TY_9) }}</strong></td>
                <td><strong>{{ currency($fatTot->val_TY_10) }}</strong></td>
                <td><strong>{{ currency($fatTot->val_TY_11) }}</strong></td>
                <td><strong>{{ currency($fatTot->val_TY_12) }}</strong></td>
                <td><strong>{{ currency($tot_TY) }}</strong></td>                    
            </tr>
            <tr style="text-align: right;">
                <td style="text-align: center;">{{ $prevYear }}</td>
                <td>{{ currency($fatTot->val_PY_1) }}</td>
                <td>{{ currency($fatTot->val_PY_2) }}</td>
                <td>{{ currency($fatTot->val_PY_3) }}</td>
                <td>{{ currency($fatTot->val_PY_4) }}</td>
                <td>{{ currency($fatTot->val_PY_5) }}</td>
                <td>{{ currency($fatTot->val_PY_6) }}</td>
                <td>{{ currency($fatTot->val_PY_7) }}</td>
                <td>{{ currency($fatTot->val_PY_8) }}</td>
                <td>{{ currency($fatTot->val_PY_9) }}</td>
                <td>{{ currency($fatTot->val_PY_10) }}</td>
                <td>{{ currency($fatTot->val_PY_11) }}</td>
                <td>{{ currency($fatTot->val_PY_12) }}</td>
                <td><strong>{{ currency($tot_PY) }}</strong></td> 
            </tr>
            <tr class="bg-gray" style="text-align: right;">
                <td style="text-align: center;">Delta</td>
                <td>{{ currency($fatTot->val_TY_1-$fatTot->val_PY_1) }}</td>
                <td>{{ currency($fatTot->val_TY_2-$fatTot->val_PY_2) }}</td>
                <td>{{ currency($fatTot->val_TY_3-$fatTot->val_PY_3) }}</td>
                <td>{{ currency($fatTot->val_TY_4-$fatTot->val_PY_4) }}</td>
                <td>{{ currency($fatTot->val_TY_5-$fatTot->val_PY_5) }}</td>
                <td>{{ currency($fatTot->val_TY_6-$fatTot->val_PY_6) }}</td>
                <td>{{ currency($fatTot->val_TY_7-$fatTot->val_PY_7) }}</td>
                <td>{{ currency($fatTot->val_TY_8-$fatTot->val_PY_8) }}</td>
                <td>{{ currency($fatTot->val_TY_9-$fatTot->val_PY_9) }}</td>
                <td>{{ currency($fatTot->val_TY_10-$fatTot->val_PY_10) }}</td>
                <td>{{ currency($fatTot->val_TY_11-$fatTot->val_PY_11) }}</td>
                <td>{{ currency($fatTot->val_TY_12-$fatTot->val_PY_12) }}</td>
                <td><strong>{{ currency($tot_TY-$tot_PY) }}</strong></td> 
            </tr>
            <tr>
                <td></td>
                <td colspan="15"><hr></td>
            </tr>
        </tbody>
        @endif
  </tfoot>
</table>