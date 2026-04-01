@extends('_exports.pdf.masterPage.masterPdf')

@section('pdf-main')         
    @if (!$visits->isEmpty())
    @foreach ($visits as $userName => $groupUser)
    <p class="page">
    <div class="row">
        <div class="contentTitle">{{ $userName }} - Distanze Percorse Reports</div>

        @foreach ($groupUser as $periodo => $group)
        @php
        $totDistancePeriodo = 0;
        @endphp
        <hr>
        <h3>Periodo (Anno/Settimana): {{ $periodo }}</h3>
        @foreach ($group as $day => $visite)
        @php
        $totDistanceDay = 0;

        $formatter = new IntlDateFormatter(
            'it_IT',
            IntlDateFormatter::FULL,
            IntlDateFormatter::FULL,
            'Europe/Rome',
            IntlDateFormatter::GREGORIAN,
            'EEEE' // Formato per il nome del giorno completo
        );        
        $dayName = $formatter->format($visite->first()['visita']->data);
        @endphp
        <h4>Giorno: {{ $dayName }} - {{ $visite->first()['visita']->data->format('d/m/Y') }}</h4> 
        {{-- <br> --}}
        <table class="table table-hover table-condensed" id="cliDoc" style="text-align: center;">
            <col width="200">
            <col width="50">
            <col width="100">
            <col width="250">
            <col width="250">
            <col width="80">
            <col width="80">
            <col width="80">
            <thead>
                <th>Cliente / Contatto Visitato</th>
                <th>Tipo di incontro</th>
                <th>Descrizione Incontro</th>
                <th>Località di Partenza</th>
                <th>Località di Destinazione</th>
                <th>Km percorsi</th>
                <th>Km percorsi (Tot Giorno)</th>
                <th>Km percorsi (Tot Settimana)</th>
            </thead>
            <tbody>
                @foreach ($visite as $key => $visitaData)
                @php
                $visit = $visitaData['visita'];
                $type = '-';
                $descrizione = $visit->descrizione;
                switch ($visit->tipo) {
                case 'Meet':
                $type = trans('visit.eventMeeting');
                break;
                case 'Mail':
                $type = trans('visit.eventMail');
                break;
                case 'Prod':
                $type = trans('visit.eventProduct');
                break;
                case 'Scad':
                $type = trans('visit.eventDebt');
                break;
                case 'RNC':
                $type = trans('visit.eventRNC');
                break;
                default:
                $type = trans('visit.eventGeneric');
                break;
                }
                $totDistanceDay += $visitaData['distanceKm'];
                $totDistancePeriodo += $visitaData['distanceKm'];
                @endphp
                <tr>
                    <td>{{ $visitaData['contatto']->descrizion }}</td>
                    <td>{{ $type }}</td>
                    <td>{{ $descrizione }}</td>
                    <td>{{ $visitaData['prev_address'] }}</td>
                    <td>{{ $visitaData['formatted_address'] }}</td>
                    <td>@if ($visitaData['nation_address']!=$visitaData['prev_nation_address'])** @endif{{ $visitaData['distanceKm'] }} Km</td>
                    <td>{{ $totDistanceDay }} Km</td>
                    <td>{{ $totDistancePeriodo }} Km</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endforeach
        @endforeach


            {{-- @include('_exports.pdf.schedaVisit.timeline', [
            'visits' => $agentVisit,
            ]) --}}
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
