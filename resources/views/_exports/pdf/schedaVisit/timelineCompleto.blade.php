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
    @foreach ($visite as $visitData)
      @php
        $visit = $visitData['visita'];
        $type = '-';
        // $descrizione = $visit->descrizione;
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
        $totDistanceDay += $visitData['distanceKm'];
        $totDistancePeriodo += $visitData['distanceKm'];
      @endphp        
      <div>
          @if($visit->client)
          <strong>Cliente:&nbsp;</strong>
          <span class="contentSubTitle">
            <a target="_blank" href="{{ route('client::detail', $visit->codicecf ) }}">{{ $visit->client->descrizion }} [{{ $visit->codicecf }}]</a>
          </span>
          @else
            @if($visit->supplier)
            <strong>Supplier:&nbsp;</strong>
          <span class="contentSubTitle">
              <a target="_blank" href="{{ route('supplier::detail', $visit->codicecf ) }}">{{ $visit->supplier->descrizion }} [{{ $visit->codicecf }}]</a>
        </span>
            @else
            <strong>Potenziale Cliente:&nbsp;</strong>
          <span class="contentSubTitle">
            <a target="_blank" href="{{ route('rubri::detail', $visit->rubri_id ?? 0 ) }}">{{ $visit->rubri->descrizion ?? 'Error' }}</a>
        </span>
            @endif
          @endif
      </div>

      <div>
       <span class="floatleft20">
        <dl class="dl-horizontal">           
            <dt>Data</dt>
            <dd>
                <big><strong>{{ $dayName }}</strong></big><br>
                <big><strong>{{ $visit->data->format('d M. Y') }}</strong></big>
            </dd><br>
            
            <dt>Tipologia Incontro</dt>
            <dd>{{ $type }}</dd><br>
            
            <hr>
            <dt>Località di Partenza</dt>
            <dd>{{ $visitData['prev_address'] }}</dd><br>
            <dt>Località di Destinazione</dt>
            <dd>{{ $visitData['formatted_address'] }}</dd><br>
            <dt>Km percorsi</dt>
            <dd>
            @if ($visitData['nation_address']!=$visitData['prev_nation_address'])** @endif
            {{ $visitData['distanceKm'] }} Km
            </dd><br>
            <dt>Km percorsi (Tot Giorno)</dt>
            <dd>{{ $totDistanceDay }} Km</dd><br>
            <dt>Km percorsi (Tot Week)</dt>
            <dd>{{ $totDistancePeriodo }} Km</dd><br>

            {{-- <dt>
                <small>{{ $visit->user->name }}</small>
            </dt><br> --}}
        </dl>
      </span>


      <span class="floatright80">

        <div class="containerEvent">
          <p><span >{{ $visit->descrizione }}</span></p>
          <p> --> <b>Persona Contattata</b> {{ $visit->persona_contatto }} [{{ $visit->funzione_contatto }}]</p>
          @if($visit->note)
          <p> --> <b>Note</b></p>
          {!! preg_replace("/<img[^>]+\>/i", "[image not visible] ", $visit->note) !!}
          @endif
          <p> --> <b>Conclusioni</b>:</p>
          {!! $visit->conclusione !!}
        </div>

      </span>
      </div>
      
      <div><hr class="dividerPage"></div>
    @endforeach
@endforeach