@extends('layouts.app')

@section('htmlheader_title')
    - {{ trans('visit.headTitle_ins') }}
@endsection

@section('contentheader_title')
    {{$client->descrizion or ''}}
@endsection

@section('contentheader_breadcrumb')
  @if ($client instanceof Illuminate\Database\Eloquent\Collection)
    {!! Breadcrumbs::render('visitIns') !!}
  @else
    {!! Breadcrumbs::render('visitInsCli', $client->codice) !!}
  @endif
@endsection

@section('main-content')
  <div class="row">
      <div class="container">
      <div class="col-lg-12">
      
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title" data-widget="collapse">{{ trans('visit.insEventSupplier') }}</h3>
          <a type="button" class="box-tools btn btn-primary btn-sm pull-right" target="" href="{{ route('visit::insertRubri') }}">
            <strong> {{ trans('visit.btnEventRubri') }}</strong>
          </a>
          {{-- <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          </div> --}}
        </div>
        <div class="box-body">

          <form action="{{ route('visit::store') }}" method="POST" onsubmit="return checkForm()">
              {{ csrf_field() }}

            <div class="form-group">
              @if ($client)
                  <label>{{ trans('visit.supplier') }}</label>
                  <select id='codcli' name="codcli" class="form-control select2" style="width: 100%;">
                    @if ($client instanceof Illuminate\Database\Eloquent\Collection)
                      @if ($client->count()>1)
                        <option value=""> </option>
                      @endif
                      @foreach ($client as $cli)
                        <option value="{{ $cli->codice }}">[{{$cli->codice}}] {{ $cli->descrizion }}</option>
                      @endforeach
                    @else
                      <option value="{{ $client->codice }}">[{{$client->codice}}] {{ $client->descrizion }}</option>
                    @endif
                  </select>
              @endif              
            </div>

            {{-- <a type="button" class="btn btn-warning btn-block" target="" href="{{ route('visit::insertRubri') }}">
              <strong> Oppure inserisci visita Contatto</strong>
            </a> --}}
            <hr>

            <div class="form-group">
              <label>{{ trans('visit.eventPers') }}</label>
              <input id='pers' type="text" class="form-control" name="persona" value="{{ $visit->persona_contatto or '' }}"
                placeholder="{{ trans('visit.pers_plchld') }}">
            </div>

            <div class="form-group">
              <label>{{ trans('visit.eventRolePers') }}</label>
              <input id='rolePers' type="text" class="form-control" name="rolePersona" value="{{ $visit->funzione_contatto or '' }}"
                placeholder="{{ trans('visit.rolePers_plchld') }}">
            </div>

            <hr>

            <div class="form-group">
              @php
                $tipo = !empty($visit) ? $visit->tipo : '';
              @endphp
              <label>{{ trans('visit.eventType') }}</label>
              <select id='tipo' name="tipo" class="form-control select2" style="width: 100%;">
                <option value=""> </option>
                <option value="Meet"@if ($tipo=='Meet') selected @endif>{{ trans('visit.eventMeeting') }}</option>
                <option value="Mail"@if ($tipo=='Mail') selected @endif>{{ trans('visit.eventMail') }}</option>
                <option value="Prod"@if ($tipo=='Prod') selected @endif>{{ trans('visit.eventProduct') }}</option>
                <option value="Scad"@if ($tipo=='Scad') selected @endif>{{ trans('visit.eventDebt') }}</option>
                <option value="RNC"@if ($tipo=='RNC') selected @endif>{{ trans('visit.eventRNC') }}</option>
              </select>
            </div>

            <div class="form-group">
              <label>{{ trans('visit.eventDate') }}:</label>
              <div class="input-group date">
                <div class="input-group-addon">
                  <i class="fa fa-calendar"></i>
                </div>
                <input id='date' type="text" class="form-control pull-right datepicker" name="data" readonly="true" value='{{ $visit->data or '' }}'>
              </div>
            </div>

            <div class="form-group">
              <label>{{ trans('visit.eventDesc') }}</label>
              <input id='desc' type="text" class="form-control" name="descrizione" value="{{ $visit->descrizione or '' }}" placeholder="{{ trans('visit.desc_plchld') }}">
            </div>

            <div class="form-group">
              <label>{{ trans('visit.eventNote') }}</label>
              {{-- <textarea class="form-control" rows="6" name="note" placeholder="Dettagli &hellip;"></textarea>
              style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"--}}
              <textarea id='note' class="textarea" placeholder="{{ trans('visit.note_plchld') }}" name="note"
                style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"></textarea>
            </div>

            <hr>

            <div class="form-group">
              <label>{{ trans('visit.eventConclusion') }}</label>
              {{-- <textarea class="form-control" rows="6" name="note" placeholder="Dettagli &hellip;"></textarea>
              style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"--}}
              <textarea id='conclusione' class="textarea" placeholder="{{ trans('visit.conclusion_plchld') }}" name="conclusione"
                style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"></textarea>
            </div>

            <div class="form-group" style="display: none;">
              @php
                $optOrdine = !empty($visit) ? $visit->ordine : 0;
              @endphp
              <label>{{ trans('visit.eventOrd') }}</label>
              <div class="radio">
                <label>
                  <input type="radio" name="optOrdine" id="opt1" value="0" @if ($optOrdine==0) checked @endif> No
                </label>
                <label>
                  <input type="radio" name="optOrdine" id="opt2" value="1" @if ($optOrdine==1) checked @endif> Si
                </label>
              </div>
            </div>

            <hr>
            <div class="form-group">
              <label>{{ trans('visit.eventDateNext') }}</label>
              <div class="input-group date">
                <div class="input-group-addon">
                  <i class="fa fa-calendar"></i>
                </div>
                <input id='dateNext' type="text" class="form-control pull-right datepicker" name="dateNext" readonly="true" value='{{ $visit->data_prox or '' }}'>
              </div>
            </div>

            @push('css-head')
              <link rel="stylesheet" href="../../plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
            @endpush

            @php
                $editNote = preg_replace("/<img[^>]+\>/i", "(image) ", trim(preg_replace('/\s+/', ' ', $visit->note)));
                $editConclusion = preg_replace("/<img[^>]+\>/i", "(image) ", trim(preg_replace('/\s+/', ' ', $visit->conclusione)));
            @endphp
            @push('script-footer')
              <script src="{{ asset('/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.js') }}" type="text/javascript"></script>
              <script type="text/javascript">
                    $(function () {
                      //bootstrap WYSIHTML5 - text editor
                      $(".textarea").wysihtml5();
                    // });
                    // $(document).ready(function(){
                      @if (!empty($visit))
                        $('#note').data("wysihtml5").editor.setValue('{!! $editNote !!}');
                        $('#conclusione').data("wysihtml5").editor.setValue('{!! $editConclusion !!}');
                      @endif
                    });
              </script>
            @endpush
            
            @if (!empty($visit))    
              {!! Form::hidden('id', $visit->id) !!}
            @endif
            <div>
              <button type="submit" class="btn btn-block btn-primary">{{ trans('modRicFat.submit') }}</button>
            </div>
          </form>

        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('extra_script')
  @include('layouts.partials.scripts.iCheck')
  @include('layouts.partials.scripts.select2')
  @include('layouts.partials.scripts.datePicker')

  <script>
    function checkForm(){
        if($('#codcli').select2('data')[0].id=='') {
          alert('Selezionare Cliente');
          $('#codcli').focus();
          return false;
        }
        if($('#tipo').select2('data')[0].id=='') {
          alert('Selezionare Tipologia Incotro');
          $('#tipo').focus();
          return false;
        }
        if($('#date').val()=='') {
          alert('Indicare la data');
          $('#date').focus();
          return false;
        } else {
          var inputDate = new Date($('#date').val());
          var todaysDate = new Date();
          if(inputDate.setHours(0,0,0,0) > todaysDate.setHours(0,0,0,0)) {
            alert('La data dell\'incotro non può essere maggiore di oggi');
            $('#date').focus();
            return false;
          }
        }
        if($('#pers').val()=='') {
        alert('Indicare Persona Contatta');
        $('#pers').focus();
        return false;
        }
        if($('#rolePers').val()=='') {
        alert('Indicare Ruolo Persona Contatta');
        $('#rolePers').focus();
        return false;
        }
        if($('#desc').val()=='') {
        alert('Indicare Motivo Incotro / Breve Descrizione');
        $('#desc').focus();
        return false;
        }
        if($('#conclusione').val()=='') {
        alert('Indicare Conclusione Incotro');
        $('#conclusione').focus();
        return false;
        }
        // if($('#dateNext').val()=='') {
        //   alert('Indicare Data prox incontro');
        //   $('#dateNext').focus();
        //   return false;
        // } else {
        //   var inputDate = new Date($('#dateNext').val());
        //   var todaysDate = new Date();
        //   if(inputDate.setHours(0,0,0,0) <= todaysDate.setHours(0,0,0,0)) {
        //     alert('La data dell\'incotro non può essere antecedente a oggi');
        //     $('#dateNext').focus();
        //     return false;
        // }
        if($('#dateNext').val()!='') {
          var inputDate = new Date($('#dateNext').val());
          var todaysDate = new Date();
          if(inputDate.setHours(0,0,0,0) <= todaysDate.setHours(0,0,0,0)) {
            alert('La data dell\'incotro non può essere antecedente a oggi');
            $('#dateNext').focus();
            return false;
          }
          
          return true;
        }
  };
  </script>

@endsection
