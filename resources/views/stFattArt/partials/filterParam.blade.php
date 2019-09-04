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
