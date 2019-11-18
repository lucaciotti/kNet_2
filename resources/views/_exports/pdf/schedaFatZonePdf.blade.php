@extends('_exports.pdf.masterPage.masterPdf')

@section('pdf-main')
    <p class="page">
        <div class="row">
            <div class="contentTitle">Agents List</div>
            <dl class="dl-horizontal">
                @foreach ($agentList as $agent)
                <dt>{{$agent->codice}} - {{$agent->descrizion}}</dt>
                @endforeach
            </dl>
        </div>
        <div>
            <hr class="dividerPage">
        </div>

        @if($fatTot_KR)
            <div class="row">
                <div class="contentTitle">Zone Turnover Situation - KRONA</div>

                @include('_exports.pdf.schedaFat.tblZone', [
                        'fatZone' => $fatZone_KR,
                        'fatTot' => $fatTot_KR,
                        'thisYear' => $thisYear,
                        'prevYear' => $prevYear
                    ])
            </div>            
        </p>
        <p class="page">
        @endif
    
    @if($fatTot_KO)
            <div class="row">
                <div class="contentTitle">Zone Turnover Situation - KOBLENZ</div>

                @include('_exports.pdf.schedaFat.tblZone', [
                        'fatZone' => $fatZone_KO,
                        'fatTot' => $fatTot_KO,
                        'thisYear' => $thisYear,
                        'prevYear' => $prevYear
                    ])
            </div>            
        </p>
        <p class="page">
    @endif
    
    @if($fatTot_KU)
            <div class="row">
                <div class="contentTitle">Zone Turnover Situation - KUBICA</div>

                @include('_exports.pdf.schedaFat.tblZone', [
                        'fatZone' => $fatZone_KU,
                        'fatTot' => $fatTot_KU,
                        'thisYear' => $thisYear,
                        'prevYear' => $prevYear
                    ])
            </div>            
        </p>
        <p class="page">
    @endif
    
    @if($fatTot_PL)
            <div class="row">
                <div class="contentTitle">Zone Turnover Situation - PLANET</div>

                @include('_exports.pdf.schedaFat.tblZone', [
                        'fatZone' => $fatZone_PL,
                        'fatTot' => $fatTot_PL,
                        'thisYear' => $thisYear,
                        'prevYear' => $prevYear
                    ])
            </div>            
        </p>
    @endif

@endsection
