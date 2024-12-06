    @php
      $date=null;
      $message='';
    @endphp
    @foreach ($visits as $visit)
      
        @switch( $visit->tipo )
          @case( 'Meet' )
              @php
                $message=trans('visit.eventMeeting')
              @endphp
          @break

          @case( 'Mail' )
              @php
                $message=trans('visit.eventMail')
              @endphp
          @break

          @case( 'Prod' )
              @php
                $message=trans('visit.eventProduct')
              @endphp
          @break

          @case( 'Scad' )
              @php
                $message=trans('visit.eventDebt')
              @endphp
          @break

          @case( 'RNC' )
              @php
                $message=trans('visit.eventRNC')
              @endphp
          @break
          
          @default
              @php
                $message=trans('visit.eventGeneric')
              @endphp
          @break
        @endswitch
        
      <div><hr class="dividerPage"></div>
      <div>
       <span class="floatleft10">
        <dl class="dl-horizontal">
            <dt>Data</dt>
            <dd>
                <big><strong>{{ $visit->data->format('d M. Y') }}</strong></big>
            </dd><br>
            
            <dt>Tipologia Incontro</dt>
            <dd>{{ $message }}</dd><br>

            <dt>
                <small>{{ $visit->user->name }}</small>
            </dt><br>
        </dl>
      </span>


      <span class="floatright90">

        <div class="containerEvent">
          <p><span >{{ $visit->descrizione }}</span></p>
          <p> --> <b>Persona Contattata</b> {{ $visit->persona_contatto }} [{{ $visit->funzione_contatto }}]</p>
          @if($visit->note)
          <p> --> <b>Note</b></p>
          {!! preg_replace("/<img[^>]+\>/i", "(image) ", $visit->note) !!}
          @endif
          <p> --> <b>Conclusioni</b>:</p>
          {!! $visit->conclusione !!}
        </div>

      </span>
      </div>
    @endforeach
      <div><hr class="dividerPage"></div>
