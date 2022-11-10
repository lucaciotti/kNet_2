@extends('_exports.pdf.masterPage.masterPdf')

@section('pdf-main')            
    @if (!$visits->isEmpty())
        @foreach ($visits as $agentVisit)
        <p class="page">
            <div class="row">
                <div class="contentTitle">{{ $agentVisit->first()->user->name }} - Reports</div>
        
                @include('_exports.pdf.schedaVisit.timeline', [
                'visits' => $agentVisit,
                ])
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
