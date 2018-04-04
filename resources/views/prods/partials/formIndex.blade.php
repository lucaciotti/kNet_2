<form action="{{ route('prod::fltList') }}" method="post">
  <input type="hidden" name="_token" value="{{ csrf_token() }}">

  <div class="form-group">
    <label>{{ trans('prod.codeArt') }}</label>
    <div class="input-group">
      <span class="input-group-btn">
        <select type="button" class="btn btn-warning dropdown-toggle" name="codArtOp">
          <option value="eql">=</option>
          <option value="stw" selected>[]...</option>
          <option value="cnt">...[]...</option>
        </select>
      </span>
      <input type="text" class="form-control" name="codArt" value="{{$codArt or ''}}">
    </div>
  </div>

  <div class="form-group">
    <label>{{ trans('prod.descArt') }}</label>
    <div class="input-group">
      <span class="input-group-btn">
        <select type="button" class="btn btn-warning dropdown-toggle" name="descrOp">
          <option value="eql">=</option>
          <option value="stw">[]...</option>
          <option value="cnt" selected>...[]...</option>
        </select>
      </span>
      <input type="text" class="form-control" name="descrArt" value="{{$descrArt or ''}}">
    </div>
  </div>

  <div class="form-group">
    <label>{{ trans('prod.groupProd') }}</label>
    <select name="gruppo[]" class="form-control select2" multiple="multiple" data-placeholder="{{ trans('prod.group_plchld') }}" style="width: 100%;">
      @foreach ($gruppi as $gruppo)
        <option value="{{ $gruppo->codice }}"
          @if(isset($grpSelected) && in_array($gruppo->codice, $grpSelected))
              selected
          @endif
          >[{{ $gruppo->codice }}] {{ $gruppo->descrizion }}</option>
      @endforeach
    </select>
  </div>

  {{-- <div class="form-group">
    <label>Tipologia Prodotto</label>
    <div class="checkbox">
      <label>
        <input type="checkbox" name="chkTipo[]" id="opt1" value="KR"
        @if(isset($chkTipo) && in_array("KR", $chkTipo))
            checked
        @endif
        @if(!isset($chkTipo))
            checked
        @endif
        > Krona
      </label>
      <label>
        <input type="checkbox" name="chkTipo[]" id="opt2" value="KO"
        @if(isset($chkTipo) && in_array("KO", $chkTipo))
            checked
        @endif
        @if(!isset($chkTipo))
            checked
        @endif
        > Koblenz
      </label>
      <label>
        <input type="checkbox" name="chkTipo[]" id="opt3" value="KU"
        @if(isset($chkTipo) && in_array("KU", $chkTipo))
            checked
        @endif
        @if(!isset($chkTipo))
            checked
        @endif
        > Kubica
      </label>
      <label>
        <input type="checkbox" name="chkTipo[]" id="opt4" value="GR"
        @if(isset($chkTipo) && in_array("GR", $chkTipo))
            checked
        @endif
        @if(!isset($chkTipo))
            checked
        @endif
        > Grass
      </label>
    </div>
  </div> --}}

  <div class="form-group">
    <label>{{ trans('prod.extra') }}</label>
    <div class="checkbox">
      <label>
        <input type="checkbox" name="chkCamp" id="opt1" value="1"
        @if(isset($chkCamp) && $chkCamp==1)
            checked
        @endif
        > {{ trans('prod.sampleArt') }}
      </label>
      <label>
        <input type="checkbox" name="chkPers" id="opt2" value="1"
        @if(isset($chkPers) && $chkPers==1)
            checked
        @endif
        > {{ trans('prod.persCliArt') }}
      </label>
  </div>

  <div>
    <button type="submit" class="btn btn-primary">{{ trans('_message.submit') }}</button>
  </div>

</form>
