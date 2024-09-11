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
          @break

          @case( 'Mail' )
              <i class="fa fa-envelope bg-orange"></i>
              @php
                $message=trans('visit.eventMail')
              @endphp
          @break

          @case( 'Prod' )
              <i class="fa fa-cube bg-green"></i>
              @php
                $message=trans('visit.eventProduct')
              @endphp
          @break

          @case( 'Scad' )
              <i class="fa fa-money bg-purple"></i>
              @php
                $message=trans('visit.eventDebt')
              @endphp
          @break

          @case( 'RNC' )
              <i class="fa fa-exclamation-circle bg-red"></i>
              @php
                $message=trans('visit.eventRNC')
              @endphp
          @break

          @default
              <i class="fa fa-question-circle bg-yellow"></i>
              @php
                $message=trans('visit.eventGeneric')
              @endphp
          @break
              
      @endswitch
        <div class="timeline-item">
          <span class="time"><i class="fa fa-user"></i> {{ $visit->user->name }}</span>

          <h3 class="timeline-header"><strong>{{ $visit->descrizione }}</strong> - <small> {{ $message }} </small></h3>

          <div class="timeline-body" style="padding-bottom: 5px">
            @if($visit->persona_contatto)
              <i class="fa fa-arrow-right" aria-hidden="true"></i> <small>Persona Contatta:</small><br> 
              <p style="font-size:large; margin-left: 25px">{{ $visit->persona_contatto }} [{{ $visit->funzione_contatto }}]</p>
            @endif
              <i class="fa fa-arrow-right" aria-hidden="true"></i> <small>Note:</small><br>
              <p style="font-size:large;margin-left: 25px">{!! $visit->note !!}</p>
              <i class="fa fa-arrow-right" aria-hidden="true"></i> <small>Conclusione:</small><br>
              <p style="font-size:large;margin-left: 25px">{!! $visit->conclusione !!}</p>
            @if($visit->ordine)
              <i class="fa fa-arrow-right" aria-hidden="true"></i> <strong>Effettuerà Ordine!</strong>         
            @endif
          </div>
          <div class="timeline-footer text-right">
            <a href='{{ route('visit::edit', ['id'=>$visit->id]) }}' class="btn btn-primary btn-sm">Edit</a>
            <form action="{{ route('visit::delete', ['id'=>$visit->id]) }}" method="post" style="display: inline;" id="delete_visit_form" onsubmit="return deleteVisit()">
              <input type="hidden" name="_token" value="{{ csrf_token() }}">
              <button type='submit' class="btn btn-danger btn-sm">Delete</button>
            </form>
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
      @if($codcli)
        <a class="btn btn-sm btn-default" href="{{ route('visit::insert', $codcli) }}"> <i class="fa fa-plus"></i> <span>{{ trans('visit.insEvent') }}</span></a>
        <a class="btn btn-sm btn-primary" href="{{ route('visit::show', $codcli) }}">  <span>{{ trans('client.seeTimeline') }}... </span></a>
      @else
        <a class="btn btn-sm btn-default" href="{{ route('visit::insertRubri', $rubri_id) }}"> <i class="fa fa-plus"></i> <span>{{ trans('visit.insEventRubri') }}</span></a>
      @endif
    </span>
  </li>
</ul>


@section('extra_script')
<script>
  function deleteVisit(){
    var result = confirm("Eliminare visita selezionata?");
    if (result) {
      return true;
    } else {
      return false;
    }
  };
</script>
@endsection

