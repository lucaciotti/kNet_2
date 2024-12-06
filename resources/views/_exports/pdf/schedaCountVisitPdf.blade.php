@extends('_exports.pdf.masterPage.masterPdf')

@section('pdf-main')            
    @if (!$visits->isEmpty())
        @foreach ($visits as $agentVisit)
        <p class="page">
            <div class="row">
                <div class="contentTitle">{{ $agentVisit->first()->first()->user->name }} - Conteggio Reports</div>
                @php
                    $agMonth01=0;
                    $agMonth02=0;
                    $agMonth03=0;
                    $agMonth04=0;
                    $agMonth05=0;
                    $agMonth06=0;
                    $agMonth07=0;
                    $agMonth08=0;
                    $agMonth09=0;
                    $agMonth10=0;
                    $agMonth11=0;
                    $agMonth12=0;
                    $agTot=0;
                    $printAgTot=false;
                @endphp
                @foreach ($agentVisit as $i => $typeCustomerVisit)
                    @php
                        $printAgTot=$agTot>0 ? true : false;
                        $month01=0;
                        $month02=0;
                        $month03=0;
                        $month04=0;
                        $month05=0;
                        $month06=0;
                        $month07=0;
                        $month08=0;
                        $month09=0;
                        $month10=0;
                        $month11=0;
                        $month12=0;
                        $tot=0;
                    @endphp
                    @if ($agTot>0)
                        <hr>
                    @endif
                    <div class="contentSubTitle centered">{{ $typeCustomerVisit->first()->tipologia }}</div>

                    <table>
                        <thead>
                            <tr>
                                <th>Ragione Sociale</th>
                                <th width='18%'>Zona</th>
                                <th width='13%'>Settore</th>
                                <th width='3.2%'>Gen</th>
                                <th width='3.2%'>Feb</th>
                                <th width='3.2%'>Mar</th>
                                <th width='3.2%'>Apr</th>
                                <th width='3.2%'>Mag</th>
                                <th width='3.2%'>Giu</th>
                                <th width='3.2%'>Lug</th>
                                <th width='3.2%'>Ago</th>
                                <th width='3.2%'>Sett</th>
                                <th width='3.2%'>Ott</th>
                                <th width='3.2%'>Nov</th>
                                <th width='3.2%'>Dic</th>
                                <th width='3.5%'>Tot.</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($typeCustomerVisit as $visit)
                            <tr>
                                @if ($visit->client)    
                                <td><b>{{ $visit->client->descrizion }}</b> [<a href="{{ route('client::detail', $visit->codicecf ) }}">{{ $visit->codicecf }}</a>]</td>
                                <td>{{ $visit->client->detZona->descrizion }}</td>
                                <td>{{ $visit->client->detSect->descrizion }}</td>
                                @endif
                                @if ($visit->rubri)
                                <td><b>{{ strtoupper($visit->rubri->descrizion) }}</b> [<i>Potenziale Cliente</i>]</td>
                                <td>@if ($visit->rubri->codnazione == 'I' || $visit->rubri->codnazione == 'IT'){{ strtoupper($visit->rubri->localita ?? '') }}@else{{ strtoupper($visit->rubri->detZona->descrizion ?? '') }}@endif</td>
                                <td>{{ $visit->rubri->settore }}</td>                                
                                @endif
                                @if($visit->supplier)
                                <td><b>{{ $visit->supplier->descrizion }}</b> [<i>Potenziale Cliente</i>]</td>
                                <td>{{ $visit->supplier->detZona->descrizion }}</td>
                                <td>{{ $visit->supplier->detSect->descrizion }}</td>
                                @endif
                                <th>{{ $visit->month_01>0 ? $visit->month_01 : '' }}</th>
                                <th>{{ $visit->month_02>0 ? $visit->month_02 : '' }}</th>
                                <th>{{ $visit->month_03>0 ? $visit->month_03 : '' }}</th>
                                <th>{{ $visit->month_04>0 ? $visit->month_04 : '' }}</th>
                                <th>{{ $visit->month_05>0 ? $visit->month_05 : '' }}</th>
                                <th>{{ $visit->month_06>0 ? $visit->month_06 : '' }}</th>
                                <th>{{ $visit->month_07>0 ? $visit->month_07 : '' }}</th>
                                <th>{{ $visit->month_08>0 ? $visit->month_08 : '' }}</th>
                                <th>{{ $visit->month_09>0 ? $visit->month_09 : '' }}</th>
                                <th>{{ $visit->month_10>0 ? $visit->month_10 : '' }}</th>
                                <th>{{ $visit->month_11>0 ? $visit->month_11 : '' }}</th>
                                <th>{{ $visit->month_12>0 ? $visit->month_12 : '' }}</th>
                                <th class='grey'>{{ $visit->tot }}</th>                            
                            </tr>
                            @php
                                $month01+=$visit->month_01;
                                $month02+=$visit->month_02;
                                $month03+=$visit->month_03;
                                $month04+=$visit->month_04;
                                $month05+=$visit->month_05;
                                $month06+=$visit->month_06;
                                $month07+=$visit->month_07;
                                $month08+=$visit->month_08;
                                $month09+=$visit->month_09;
                                $month10+=$visit->month_10;
                                $month11+=$visit->month_11;
                                $month12+=$visit->month_12;
                                $tot+=$visit->tot;
                                $agMonth01+=$visit->month_01;
                                $agMonth02+=$visit->month_02;
                                $agMonth03+=$visit->month_03;
                                $agMonth04+=$visit->month_04;
                                $agMonth05+=$visit->month_05;
                                $agMonth06+=$visit->month_06;
                                $agMonth07+=$visit->month_07;
                                $agMonth08+=$visit->month_08;
                                $agMonth09+=$visit->month_09;
                                $agMonth10+=$visit->month_10;
                                $agMonth11+=$visit->month_11;
                                $agMonth12+=$visit->month_12;
                                $agTot+=$visit->tot;
                            @endphp
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan=3>TOTALE</th>
                                <th>{{ $month01 }}</th>
                                <th>{{ $month02 }}</th>
                                <th>{{ $month03 }}</th>
                                <th>{{ $month04 }}</th>
                                <th>{{ $month05 }}</th>
                                <th>{{ $month06 }}</th>
                                <th>{{ $month07 }}</th>
                                <th>{{ $month08 }}</th>
                                <th>{{ $month09 }}</th>
                                <th>{{ $month10 }}</th>
                                <th>{{ $month11 }}</th>
                                <th>{{ $month12 }}</th>
                                <th>{{ $tot }}</th>                            
                            </tr>
                        </tfoot>
                    </table>
                @endforeach
                @if ($printAgTot)
                    <hr>
                    <table>
                        <col>
                        <col width='3.2%'>
                        <col width='3.2%'>
                        <col width='3.2%'>
                        <col width='3.2%'>
                        <col width='3.2%'>
                        <col width='3.2%'>
                        <col width='3.2%'>
                        <col width='3.2%'>
                        <col width='3.2%'>
                        <col width='3.2%'>
                        <col width='3.2%'>
                        <col width='3.2%'>
                        <col width='3.5%'>
                        <tfoot>
                            <tr>
                                <th>TOTALE REPORTS</th>
                                <th>{{ $agMonth01 }}</th>
                                <th>{{ $agMonth02 }}</th>
                                <th>{{ $agMonth03 }}</th>
                                <th>{{ $agMonth04 }}</th>
                                <th>{{ $agMonth05 }}</th>
                                <th>{{ $agMonth06 }}</th>
                                <th>{{ $agMonth07 }}</th>
                                <th>{{ $agMonth08 }}</th>
                                <th>{{ $agMonth09 }}</th>
                                <th>{{ $agMonth10 }}</th>
                                <th>{{ $agMonth11 }}</th>
                                <th>{{ $agMonth12 }}</th>
                                <th>{{ $agTot }}</th>
                            </tr>
                        </tfoot>
                    </table>
                @endif
            </div>
        </p>    
        @endforeach
    @else
        <p class="page">
        <div class="row">
            
        </div>
        </p>
    @endif  
@endsection
