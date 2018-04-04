<form action="{{ route('scad::fltList') }}" method="post">
  <input type="hidden" name="_token" value="{{ csrf_token() }}">
  <div class="form-group">
    <label>{{ trans('scad.descClient') }}</label>
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
    <label>{{ trans('scad.datePay') }}:</label>
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
      <input type="checkbox" name="noDate" id="noDate" value="C" > {{ trans('scad.anyDate') }}
    </label>
  </div>
  <div class="form-group">
    <label>{{ trans('scad.typePayment') }}</label>
    <div class="checkbox">
      <label>
        <input type="checkbox" name="chkPag[]" id="opt1" value="D" checked> {{ trans('scad.dirRem') }}
      </label>
      <label>
        <input type="checkbox" name="chkPag[]" id="opt2" value="R" checked> {{ trans('scad.bnkRpt') }}
      </label>
      <label>
        <input type="checkbox" name="chkPag[]" id="opt3" value="T" checked> {{ trans('scad.blExc') }}
      </label>
      <label>
        <input type="checkbox" name="chkPag[]" id="opt4" value="P" checked> {{ trans('scad.iou') }}
      </label>
      <br>
      <label>
        <input type="checkbox" name="chkPag[]" id="opt6" value="L" checked> {{ trans('scad.pstlPay') }}
      </label>
      <label>
        <input type="checkbox" name="chkPag[]" id="opt7" value="C" checked> {{ trans('scad.CoD') }}
      </label>
      <label>
        <input type="checkbox" name="chkPag[]" id="opt5" value="B" checked> {{ trans('scad.WiTr') }}
      </label>
      <label>
        <input type="checkbox" name="chkPag[]" id="opt8" value="A" checked> {{ trans('scad.otherPayment') }}
      </label>
    </div>
  </div>
  <div class="form-group">
    <label>{{ trans('scad.statusPayment') }}</label>
    <div class="checkbox">
      <label>
        <input type="checkbox" name="chkStato_P" id="optStato1" value="P"> {{ trans('scad.payedStatus') }}
      </label>
      <label>
        <input type="checkbox" name="chkStato_T" id="optStato2" value="T"> {{ strtoupper(trans('scad.allStatus')) }}
      </label>
    </div>
  </div>
  <div class="form-group">
    <label>{{ trans('scad.mergedOrNot') }}</label>
    <div class="radio">
      <label>
        <input type="radio" name="optRaggr" id="opt1" value="F" checked> {{ trans('scad.each') }}
      </label>
      <label>
        <input type="radio" name="optRaggr" id="opt2" value="M"> {{ trans('scad.merged') }}
      </label>
    </div>
  </div>
  <div>
    <button type="submit" class="btn btn-primary">{{ trans('_message.submit') }}</button>
  </div>
</form>
