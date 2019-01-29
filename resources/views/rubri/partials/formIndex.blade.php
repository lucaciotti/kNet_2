<form action="{{ route('rubri::fltList') }}" method="post">
  <input type="hidden" name="_token" value="{{ csrf_token() }}">

  {{-- Ragione Sociale --}}
    <div class="form-group">
      <label>Ragione Sociale</label>
      <select name="rubri_id" class="form-control select2" style="width: 100%;">
        <option value=""> </option>
        @foreach ($fltContacts as $contact)
          <option value="{{ $contact->id }}">{{ $contact->descrizion or 'cDeleted' }}</option>
        @endforeach
      </select>
    </div>

  {{-- Partita Iva --}}
    <div class="form-group">
      <label>Partita Iva</label>
      <div class="input-group">
        <span class="input-group-btn">
          <select type="button" class="btn btn-warning dropdown-toggle" name="ragsocOp">
            <option value="eql">=</option>
            <option value="stw">[]...</option>
            <option value="cnt" selected>...[]...</option>
          </select>
        </span>
        <input type="text" class="form-control" name="partiva">
      </div>
    </div>
  {{-- Regione --}}
    <div class="form-group">
      <label>Regione</label>
      <select name="regione" class="form-control select2" style="width: 100%;">
        <option value=""> </option>
        @foreach ($regioni as $regione)
          <option value="{{ $regione->regione }}">{{ $regione->regione or 'cDeleted' }}</option>
        @endforeach
      </select>
    </div>

  {{-- Località --}}
    <div class="form-group">
      <label>Provncia</label>
      <select name="prov" class="form-control select2" style="width: 100%;">
        <option value=""> </option>
        @foreach ($zone as $loc)
          <option value="{{ $loc->prov }}">{{ $loc->prov or 'cDeleted' }}</option>
        @endforeach
      </select>
    </div>

  {{-- Agente --}}
    <div class="form-group">
      <label>Agente</label>
      <select name="agente" class="form-control select2" style="width: 100%;">
        <option value=""> </option>
        @foreach ($agenti as $agente)
          <option value="{{ $agente->agente }}">{{ $agente->agent->descrizion or 'cDeleted' }}</option>
        @endforeach
      </select>
    </div>

  {{-- Stato Cf --}}
    <div class="form-group">
      <label>Stato Contatto</label>
      <div class="radio">
        <label>
          <input type="radio" name="optStatocf" id="opt1" value="T"> {{ trans('client.activeStatus') }}
        </label>
        <label>
          <input type="radio" name="optStatocf" id="opt2" value="C"> {{ trans('client.closedStatus') }}
        </label>
        <label>
          <input type="radio" name="optStatocf" id="opt3" value="" checked> {{ strtoupper(trans('client.allStatus')) }}
        </label>
      </div>
    </div>

  {{-- Date Next Visit --}}

  {{-- isModule --}}  
    <div class="form-group">
      <label>Modulo Falegnami</label>
      <div class="radio">
        <label>
          <input type="radio" name="optModCarp" id="optMod1" value="S">Si
        </label>
        <label>
          <input type="radio" name="optModCarp" id="optMod2" value="N">No
        </label>
        <label>
          <input type="radio" name="optModCarp" id="optMod3" value="" checked> {{ strtoupper(trans('client.allStatus')) }}
        </label>
      </div>
    </div>

  <div>
    <button type="submit" class="btn btn-primary">{{ trans('_message.submit') }}</button>
  </div>
</form>
