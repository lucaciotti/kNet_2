<ul class="timeline">
  <!-- timeline time label -->
  @if (!$visits->isEmpty())
    @php
      $date=null;
      $message=''
    @endphp
    @foreach ($visits as $visit)
      @if ($visit->data != $date)
        <li class="time-label">
          <span class="bg-gray">
            {{ $visit->data->format('d M. Y') }}
            {{-- <a href="{{ route('visit::insert', $codcli) }}"> </a> --}}
            @php
              $date=$visit->data
            @endphp
          </span>
        </li>
      @endif
      <li>
      @switch( $visit->tipo )
          @case( 'Meet' )
              <i class="fa fa-weixin bg-light-blue"></i>
              @php
                $message=trans('visit.eventMeeting')
              @endphp
          @case( 'Mail' )
              <i class="fa fa-envelope bg-orange"></i>
              @php
                $message=trans('visit.eventMail')
              @endphp
          @case( 'Prod' )
              <i class="fa fa-cube bg-green"></i>
              @php
                $message=trans('visit.eventProduct')
              @endphp
          
          @case( 'Scad' )
              <i class="fa fa-money bg-purple"></i>
              @php
                $message=trans('visit.eventDebt')
              @endphp
          
          @case( 'RNC' )
              <i class="fa fa-exclamation-circle bg-red"></i>
              @php
                $message=trans('visit.eventRNC')
              @endphp
          
          @default
              <i class="fa fa-question-circle bg-yellow"></i>
              @php
                $message=trans('visit.eventGeneric')
              @endphp
          
      @endswitch
        <div class="timeline-item">
          <span class="time"><i class="fa fa-user"></i> {{ $visit->user->name }}</span>

          <h3 class="timeline-header"><strong>{{ $visit->descrizione }}</strong> - <small> {{ $message }} </small></h3>

          <div class="timeline-body">
            {!! $visit->note !!}
          </div>
          <div class="timeline-footer">
            <a class="btn btn-primary btn-xs">{{ trans('visit.readMore') }}</a>
          </div>
        </div>
      </li>
    @endforeach
  @else
    <li class="time-label">
      <span class="bg-gray">
        {{ $dateNow->format('d M. Y') }}
      </span>
    </li>
  @endif
  <li>
    <i class='fa fa-clock-o bg-gray'></i>
    <span class="timeline-item">
      <a class="btn btn-sm btn-default" href="{{ route('visit::insertRubri', $rubri_id) }}"> <i class="fa fa-plus"></i> <span>{{ trans('client.insEvent') }}</span></a>
      <a class="btn btn-sm btn-primary" href="{{ route('visit::showRubri', $rubri_id) }}">  <span>{{ trans('client.seeTimeline') }}... </span></a>
    </span>
  </li>
</ul>
