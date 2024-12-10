<form action="{{ route('visit::report') }}" method="post">
  <input type="hidden" name="_token" value="{{ csrf_token() }}">
  <div class="form-group">
    <label>{{ trans('doc.descClient') }}</label>
    <div class="input-group">
      <span class="input-group-btn">
        <select type="button" class="btn btn-warning dropdown-toggle" name="ragsocOp">
          <option value="eql">=</option>
          <option value="stw">[]...</option>
          <option value="cnt" selected>...[]...</option>
        </select>
      </span>
      <input type="text" class="form-control" name="ragsoc" value="{{$ragSoc or ''}}">
    </div>
  </div>
  <div class="form-group">
    <label>Date:</label>
    <div class="input-group">
      <button type="button" class="btn btn-default pull-right daterange-btn">
        <i class="fa fa-calendar"></i>&nbsp;
        <span></span> <b class="fa fa-caret-down"></b>
      </button>
      <input type="hidden" name="startDate" value="">
      <input type="hidden" name="endDate" value="">
    </div>
  </div>
  <div class="form-group">
    <label>&nbsp;
      <input type="checkbox" name="noDate" id="noDate" value="C" @if($startDate=="") checked @endif> {{ trans('doc.anyDate') }}
    </label>
  </div>

  {{-- VECCHIA GESTIONE TIPOLOGIA --}}
  {{-- <div class="form-group">
    <label>Tipo:</label>
    <div class="radio">
      <label>
        <input type="radio" name="optTipo" id="opt1" value="" @if($optTipo=="") checked @endif> All
      </label>
      <label>
        <input type="radio" name="optTipo" id="opt2" value="Meet"@if($optTipo=="Meet") checked @endif> {{ trans('visit.eventMeeting') }}
      </label>
      <label>
        <input type="radio" name="optTipo" id="opt3" value="Mail"@if($optTipo=="Mail") checked @endif> {{ trans('visit.eventMail') }}
      </label>
      <label>
        <input type="radio" name="optTipo" id="opt4" value="Prod"@if($optTipo=="Prod") checked @endif> {{ trans('visit.eventProduct') }}
      </label>
      <label>
        <input type="radio" name="optTipo" id="opt5" value="Scad"@if($optTipo=="Scad") checked @endif> {{ trans('visit.eventDebt') }}
      </label>
      <label>
        <input type="radio" name="optTipo" id="opt6" value="RNC"@if($optTipo=="RNC") checked @endif> {{ trans('visit.eventRNC') }}
      </label>
    </div>
  </div> --}}

  <div class="form-group">
    <label>Tipo:</label>
    <div class="checkbox">
    <label>
      <input type="checkbox" name="typeMeet" id="typeMeet" value="1" @if($typeMeet) checked @endif> {{trans('visit.eventMeeting') }}
    </label>
    <br>
    <label>
      <input type="checkbox" name="typeMail" id="typeMail" value="1" @if($typeMail) checked @endif> {{trans('visit.eventMail') }}
    </label>
    <br>
    <label>
      <input type="checkbox" name="typeProd" id="typeProd" value="1" @if($typeProd) checked @endif> {{trans('visit.eventProduct') }}
    </label>
    <br>
    <label>
      <input type="checkbox" name="typeScad" id="typeScad" value="1" @if($typeScad) checked @endif> {{trans('visit.eventDebt') }}
    </label>
    <br>
    <label>
      <input type="checkbox" name="typeRNC" id="typeRNC" value="1" @if($typeRNC) checked @endif> {{trans('visit.eventRNC') }}
    </div>
  </div>

  <div class="form-group">
    <label>Relatore</label>
    <div class="input-group">
      <span class="input-group-btn">
        <select type="button" class="btn btn-warning dropdown-toggle" name="relatOp">
          <option value="eql">=</option>
          <option value="stw">[]...</option>
          <option value="cnt" selected>...[]...</option>
        </select>
      </span>
      <input type="text" class="form-control" name="relat" value="{{$relat or ''}}">
    </div>
  </div>

  <div>
    <button type="submit" class="btn btn-primary pull-right">{{ trans('_message.submit') }}</button>
  </div>
</form>
