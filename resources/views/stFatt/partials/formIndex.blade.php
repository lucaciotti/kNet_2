{{-- <form action="{{ route($route) }}" method="post"> --}}
  <input type="hidden" name="_token" value="{{ csrf_token() }}">
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
  
  
  <div class="form-group">
    <label>{{ trans('prod.masterGroup') }}</label>
    <div class="radio">
      <label>
        <input type="radio" name="optTipoDoc" id="opt1" value="" checked> {{ trans('doc.allDocs') }}
      </label>
      <label>
        <input type="radio" name="optTipoDoc" id="opt2" value="KRONA"> Krona
      </label>
      <label>
        <input type="radio" name="optTipoDoc" id="opt3" value="KOBLENZ"> Koblenz
      </label>
      <label>
        <input type="radio" name="optTipoDoc" id="opt3" value="KUBIKA"> Kubika
      </label>
      @if(RedisUser::get('ditta')=='knet_es')
        <label>
          <input type="radio" name="optTipoDoc" id="opt4" value="PLANET"> Planet
        </label>
      @endif
    </div>
  </div>
  {{-- <div>
    <input type="hidden" name="codag[]" value="{{ $fltAgents }}">
    <button type="submit" class="btn btn-primary">{{ trans('_message.submit') }}</button>
  </div> --}}
{{-- </form> --}}
