@extends('_exports.pdf.masterPage.masterPdf')

@section('pdf-main')
    <p class="page">

        <div class="row">
            <div class="contentTitle">
                Protafoglio
                <span class="contentSubTitle">
                        - Dettaglio Documenti (da Evadere) <br>
                    {{-- @if($cumulativo) {{ \Carbon\Carbon::createFromDate(null, 1, null)->format('F')}} - @endif
                    {{ \Carbon\Carbon::createFromDate(null, $mese, null)->format('F')}} {{ $thisYear }} --}}
                </span>
            </div>

            @foreach ($agents as $agent)
            @php
                $listOcAgent = $listOC[$agent->codice] ?? null;
                $listOcAgent_n = $listOcAgent ? $listOcAgent->count() : 0;
                $listXcAgent = $listXC[$agent->codice] ?? null;
                $listXcAgent_n = $listXcAgent ? $listXcAgent->count() : 0;
                // if($listOcAgent_n>0){
                //     $agentDet = $listOcAgent->first()->agent;
                // } else {
                //     if($listXcAgent_n>0){
                //         $agentDet = $listXcAgent->first()->agent;
                //     }
                // }
            @endphp

            @if($listOcAgent_n>0 || $listXcAgent_n>0)
                <div class="row">
                    <div class="contentTitle">{{$agent->codice}} - {{$agent->descrizion}}</div>
                </div>
                <div>
                    <hr class="dividerPage">
                </div>
                
                @if ($listOcAgent_n>0)
                    <div class="row">
                        <div class="contentTitle">List OC</div>
                    
                        @include('_exports.pdf.portfolio.tblListDoc', [
                        'listDoc' => $listOcAgent,
                        'cumulativo' => $cumulativo,
                        'thisYear' => $thisYear,
                        'mese' => $mese,
                        ])
                    
                    </div>
                    
                    <div>
                        <hr class="dividerPage">
                    </div>                    
                @endif

                @if ($listXcAgent_n>0)
                <div class="row">
                    <div class="contentTitle">List XC</div>
                
                    @include('_exports.pdf.portfolio.tblListDoc', [
                    'listDoc' => $listXcAgent,
                    'cumulativo' => $cumulativo,
                    'thisYear' => $thisYear,
                    'mese' => $mese,
                    ])
                
                </div>
                
                <div>
                    <hr class="dividerPage">
                </div>
                @endif
            @endif
                
            @endforeach
            
        </div>

    </p>

@endsection