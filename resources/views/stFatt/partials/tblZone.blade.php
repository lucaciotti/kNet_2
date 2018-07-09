<table class="table table-responsive table-hover table-striped">
  <col width="50">
  <col width="80">
  <col width="50">
  <col width="50">
  <col width="50">
  <col width="50">
  <col width="50">
  <col width="50">
    <thead>
        <tr>
            <th>Zona</th>
            <th>Cliente</th>
            <th></th>
            <th>I Trimestre</th>
            <th>II Trimestre</th>
            <th>III Trimestre</th>
            <th>IV Trimeste</th>
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
            <td colspan="8"><i class="fa fa-plus" aria-hidden="true" style="font-size:12px;"></i>&nbsp; {{$descZona}}</td>
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
                <tr>
                    <td>{{ $thisYear }}</td>
                    <td>{{ currency($trim_TY_1) }}</td>
                    <td>{{ currency($trim_TY_2) }}</td>
                    <td>{{ currency($trim_TY_3) }}</td>
                    <td>{{ currency($trim_TY_4) }}</td>
                    <td>{{ currency($tot_TY) }}</td>                    
                </tr>
                <tr>
                    <td>{{ $prevYear }}</td>
                    <td>{{ currency($trim_PY_1) }}</td>
                    <td>{{ currency($trim_PY_2) }}</td>
                    <td>{{ currency($trim_PY_3) }}</td>
                    <td>{{ currency($trim_PY_4) }}</td>
                    <td>{{ currency($tot_PY) }}</td> 
                </tr>
                <tr class="bg-gray">
                    <td>Delta</td>
                    <td>{{ currency($trim_TY_1-$trim_PY_1) }}</td>
                    <td>{{ currency($trim_TY_2-$trim_PY_2) }}</td>
                    <td>{{ currency($trim_TY_3-$trim_PY_3) }}</td>
                    <td>{{ currency($trim_TY_4-$trim_PY_4) }}</td>
                    <td>{{ currency($tot_TY-$tot_PY) }}</td> 
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
            $descZona = ($fatZona->first()->client) ? $fatZona->first()->client->detZona->descrizion : "NO ZONA";
            $i++;
        @endphp
        <tbody>
            <tr class="clickable" data-toggle="collapse" data-target="#zona-{{$i}}" aria-expanded="false" aria-controls="#zona-{{$i}}">
            <td colspan="8"><i class="fa fa-plus" aria-hidden="true" style="font-size:12px;"></i>&nbsp;<strong>{{ strtoupper(trans('stFatt.granTot')) }}</strong></td>
            </tr>
        </tbody>
        
        <tbody id="zona-{{$i}}" class="collapse">
            <tr>
                <th rowspan="4" colspan="2"></th>
            </tr>
            <tr>
                <td>{{ $thisYear }}</td>
                <td>{{ currency($trim_TY_1) }}</td>
                <td>{{ currency($trim_TY_2) }}</td>
                <td>{{ currency($trim_TY_3) }}</td>
                <td>{{ currency($trim_TY_4) }}</td>
                <td>{{ currency($tot_TY) }}</td>                    
            </tr>
            <tr>
                <td>{{ $prevYear }}</td>
                <td>{{ currency($trim_PY_1) }}</td>
                <td>{{ currency($trim_PY_2) }}</td>
                <td>{{ currency($trim_PY_3) }}</td>
                <td>{{ currency($trim_PY_4) }}</td>
                <td>{{ currency($tot_PY) }}</td> 
            </tr>
            <tr class="bg-gray">
                <td>Delta</td>
                <td>{{ currency($trim_TY_1-$trim_PY_1) }}</td>
                <td>{{ currency($trim_TY_2-$trim_PY_2) }}</td>
                <td>{{ currency($trim_TY_3-$trim_PY_3) }}</td>
                <td>{{ currency($trim_TY_4-$trim_PY_4) }}</td>
                <td>{{ currency($tot_TY-$tot_PY) }}</td> 
            </tr>
        </tbody>
        @endif
  </tfoot>
</table>