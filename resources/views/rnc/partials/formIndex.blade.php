<form action="{{ route('rnc::fltList') }}" method="post">
  <input type="hidden" name="_token" value="{{ csrf_token() }}">

  <div class="form-group">
    <label># RNC</label>
    <div class="input-group">
      <span class="input-group-btn">
        <select type="button" class="btn btn-warning dropdown-toggle" name="nummovOp">
          <option value="eql">=</option>
          <option value="stw">[]...</option>
          <option value="cnt" selected>...[]...</option>
        </select>
      </span>
      <input type="text" class="form-control" name="nummov" value="{{$selectedRncNum or ''}}">
    </div>
  </div>

  <div class="form-group">
    <label>{{ trans('rnc.descClient') }}</label>
    <div class="input-group">
      <span class="input-group-btn">
        <select type="button" class="btn btn-warning dropdown-toggle" name="ragsocOp">
          <option value="eql">=</option>
          <option value="stw">[]...</option>
          <option value="cnt" selected>...[]...</option>
        </select>
      </span>
      <input type="text" class="form-control" name="ragsoc" value="{{$selectedRagSoc or ''}}">
    </div>
  </div>

  <div class="form-group">
    <label>{{ trans('rnc.dateReg') }}:</label>
    <div class="input-group">&nbsp;
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
      <input type="checkbox" name="noDate" id="noDate" value="C" > {{ trans('rnc.anyDate') }}
    </label>
  </div>
  
  <div class="form-group">
    <label>{{ trans('rnc.ctiporapp') }}</label>
    <select name="ctiporapp[]" class="form-control select2" multiple="multiple"
      data-placeholder="{{ trans('rnc.ctiporapp') }}" style="width: 100%;">
      @foreach ($listTipo as $tipo)
      <option value="{{ $tipo->codice }}" @if(isset($selectedTipo) && in_array($tipo->codice, $selectedTipo))
        selected
        @endif
        >[{{ $tipo->codice }}] {{ $tipo->descrizion }}</option>
      @endforeach
    </select>
  </div>

  <div class="form-group">
    <label>{{ trans('rnc.causa') }}</label>
    <select name="causa[]" class="form-control select2" multiple="multiple"
      data-placeholder="{{ trans('rnc.causa') }}" style="width: 100%;">
      @foreach ($listCause as $causa)
      <option value="{{ $causa->codice }}" @if(isset($selectedCause) && in_array($causa->codice, $selectedCause))
        selected
        @endif
        >[{{ $causa->codice }}] {{ $causa->descrizion }}</option>
      @endforeach
    </select>
  </div>

  <div class="form-group">
    <label>{{ trans('rnc.severity') }}</label>
    <div class="radio">
      <label>
        <input type="radio" name="difett" id="opt1" value="1" @if (isset($selectedSeverity) and $selectedSeverity=='1') checked @endif> 1 - Basso
      </label>
      <label>
        <input type="radio" name="difett" id="opt2" value="2" @if (isset($selectedSeverity) and $selectedSeverity=='2') checked @endif> 2 - Medio
      </label>
      <label>
        <input type="radio" name="difett" id="opt3" value="3" @if (isset($selectedSeverity) and $selectedSeverity=='3') checked @endif> 3 - Alto
      </label>
      <label>
        <input type="radio" name="difett" id="opt4" value="" @if (isset($selectedSeverity) and $selectedSeverity=='') checked @endif> Tutti
      </label>
    </div>
  </div>

  <div>
    <button type="submit" class="btn btn-primary">{{ trans('_message.submit') }}</button>
  </div>
</form>
