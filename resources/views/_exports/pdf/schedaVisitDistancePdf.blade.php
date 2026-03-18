@extends('_exports.pdf.masterPage.masterPdf')

@section('pdf-main')         
    @foreach ($visits as $periodo => $group)
        @foreach ($group as $day => $visite)
            Giorno: {{ $visite->first()['visita']->data->format('d/m/Y') }} <br>
            @foreach ($visite as $key => $visitaData)
                Partenza: {{ $visitaData['prev_address'] }} <br>
                Cliente: {{ $visitaData['contatto']->descrizion }} <br>
                Destinazione: {{ $visitaData['formatted_address'] }} <br>
                KM percorsi: {{ $visitaData['distanceKm'] }} <br><br>
            @endforeach
            <br>
        @endforeach
    @endforeach
@endsection
