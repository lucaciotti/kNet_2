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

       <span class="floatleft20">
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


      <span class="floatright80">

        <div class="containerEvent">
          <p><span >{{ $visit->descrizione }}</span><br>Note:</p>
          {!! $visit->note !!}
        </div>

      </span>
    @endforeach
