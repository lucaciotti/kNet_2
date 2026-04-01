@extends('_exports.pdf.masterPage.masterPdf')

@section('pdf-main')            
    @if (!$visits->isEmpty())
        @foreach ($visits as $userName => $groupUser)
        <p class="page">
        <div class="row">
            <div class="contentTitle">{{ $userName }} - Reports Visite Completo</div>
            
            @foreach ($groupUser as $periodo => $group)
                @include('_exports.pdf.schedaVisit.timelineCompleto', [
                    'periodo' => $periodo,
                    'group' => $group,
                ])
            @endforeach
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
