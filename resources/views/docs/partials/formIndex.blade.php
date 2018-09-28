<form action="{{ route('doc::fltList') }}" method="post">
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
    <label>{{ trans('doc.dateDoc') }}:</label>
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
  <div class="form-group">
    <label>{{ trans('doc.typeDoc') }}</label>
    <div class="radio">
      <label>
        <input type="radio" name="optTipoDoc" id="opt1" value="" checked> {{ trans('doc.allDocs') }}
      </label>
      <label>
        <input type="radio" name="optTipoDoc" id="opt2" value="O"> {{ trans('doc.orders') }}
      </label>
      <label>
        <input type="radio" name="optTipoDoc" id="opt3" value="B"> {{ trans('doc.ddt') }}
      </label>
      <label>
        <input type="radio" name="optTipoDoc" id="opt4" value="F"> {{ trans('doc.invoice') }}
      </label>
    </div>
  </div>
  <div>
    <button type="submit" class="btn btn-primary">{{ trans('_message.submit') }}</button>
  </div>
</form>
