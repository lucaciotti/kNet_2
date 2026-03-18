@extends('_exports.pdf.masterPage.masterPdf')

@section('pdf-main')
    <p class="page">

        <div class="row">
            <div class="contentTitle">
                Portafoglio Clienti
            </div>
            <div style="padding-left: 50px; font-size: 75%">
                Filtri: <br>
                Orders -> { {{ $ordFilter }} }<br>
                Ddt -> { {{ $ddtFilter }} }<br>
                Invoice -> { {{ $fatfFilter }} }<br><br>
            </div>

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
                </thead><tbody>
                    @php
                    $fat_TotCustomer_N = 0;
                    $OrdAg_N = 0;
                    $DdtAg_N = 0;
                    $fatAg_N = 0;
                    $TotAg_N = 0;
                    @endphp
                    @foreach ($portfolio as $key => $group)
                    @php
                    $totNetVal = isset($group['totNetVal']) ? $group['totNetVal'] : [];
                    $fat_TotCustomer_N = (isset($totNetVal['O']) ? $totNetVal['O'] : 0) + (isset($totNetVal['B']) ? $totNetVal['B'] : 0)
                    + (isset($totNetVal['F']) ? $totNetVal['F'] : 0)+ (isset($totNetVal['N']) ? $totNetVal['N'] : 0);
                    $OrdAg_N += (isset($totNetVal['O']) ? $totNetVal['O'] : 0);
                    $DdtAg_N += (isset($totNetVal['B']) ? $totNetVal['B'] : 0);
                    $fatAg_N += (isset($totNetVal['F']) ? $totNetVal['F'] : 0)+ (isset($totNetVal['N']) ? $totNetVal['N'] : 0);
                    $TotAg_N += $fat_TotCustomer_N;
                    @endphp
                    <tr>
                        <td style="text-align: left;"><b>{{ $group['client']->descrizion }}</b> [<a
                                href="{{ route('client::detail', $key ) }}" target="_blank">{{ $key }}</a>]</td>
                        <td>{{ $group['client']->detZona->descrizion }}</td>
                        <td>{{ $group['client']->detSect->descrizion }}</td>
                        <td> {{ currency((isset($totNetVal['O']) ? $totNetVal['O'] : 0)) }} </td>
                        <td> {{ currency((isset($totNetVal['B']) ? $totNetVal['B'] : 0)) }} </td>
                        <td> {{ currency((isset($totNetVal['F']) ? $totNetVal['F'] : 0)+ (isset($totNetVal['N']) ? $totNetVal['N'] : 0))
                            }} </td>
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

        </div>

    </p>

@endsection