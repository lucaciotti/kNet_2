<div class="form-group">
  <label>Anni Precedenti?</label>
  <select name="yearback" class="form-control select2"
    data-placeholder="Anni Precedenti" style="width: 100%;">
    <option value="2" @if($yearback==2) selected @endif>3 Anni Precedenti</option>
    <option value="3" @if($yearback==3) selected @endif>4 Anni Precedenti</option>
    <option value="4" @if($yearback==4) selected @endif>5 Anni Precedenti</option>
  </select>
</div>

<div class="form-group">
  <label>Fatturato Minimo Anno Corrente</label>
  <div class="input-group">
    <span class="input-group-btn">
      <select type="button" class="btn btn-warning dropdown-toggle" name="limitValOp">
        <option value="€" selected>€</option>
      </select>
    </span>
    <input type="number" min="0" value="{{ $limitVal }}" step=".01" class="form-control" name="limitVal" value="{{ old('limitVal') }}">
  </div>
</div>

<hr>

<div class="form-group">
  <label>
    Select Month
  </label>
  <select name="mese" class="form-control select2" data-placeholder="Select Mese" style="width: 100%;">
    <option value="1" @if($mese==1) selected @endif>{{ __('stFatt.january')}}</option>
    <option value="2" @if($mese==2) selected @endif>{{ __('stFatt.february')}}</option>
    <option value="3" @if($mese==3) selected @endif>{{ __('stFatt.march')}}</option>
    <option value="4" @if($mese==4) selected @endif>{{ __('stFatt.april')}}</option>
    <option value="5" @if($mese==5) selected @endif>{{ __('stFatt.may')}}</option>
    <option value="6" @if($mese==6) selected @endif>{{ __('stFatt.june')}}</option>
    <option value="7" @if($mese==7) selected @endif>{{ __('stFatt.july')}}</option>
    <option value="8" @if($mese==8) selected @endif>{{ __('stFatt.august')}}</option>
    <option value="9" @if($mese==9) selected @endif>{{ __('stFatt.september')}}</option>
    <option value="10" @if($mese==10) selected @endif>{{ __('stFatt.october')}}</option>
    <option value="11" @if($mese==11) selected @endif>{{ __('stFatt.november')}}</option>
    <option value="12" @if($mese==12) selected @endif>{{ __('stFatt.december')}}</option>
  </select>
</div>

<div class="form-group">
  <label>&nbsp;
    <input type="checkbox" id="onlyMese" name="onlyMese" @if($onlyMese) checked @endif> &nbsp; Solo Fatturato Mese
  </label>
</div>

<div class="form-group">
  <label>&nbsp;
    <input type="checkbox" id="pariperiodo" name="pariperiodo" @if($pariperiodo) checked @endif @if($onlyMese) disabled @endif> &nbsp; Pari Periodo
  </label>
</div>

@push('script-footer')
<script>
  $(function () {
    $("#onlyMese").on('ifChanged', function(event) {
      if(event.target.checked){
        $('#pariperiodo').iCheck('check'); 
        // style="pointer-events: none;"
        $('#pariperiodo').iCheck('disable');
      }else{
        $('#pariperiodo').iCheck('uncheck');
        $('#pariperiodo').iCheck('enable');
      }
    });
  });
</script>
@endpush