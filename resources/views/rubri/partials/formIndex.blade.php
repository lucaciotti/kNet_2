<form action="{{ route('rubri::fltList') }}" method="post">
  <input type="hidden" name="_token" value="{{ csrf_token() }}">

  {{-- Ragione Sociale --}}
    <div class="form-group">
      <label>Ragione Sociale</label>
      <select name="rubri_id" class="form-control select2" style="width: 100%;">
        <option value=""> </option>
        @foreach ($fltContacts as $contact)
          <option value="{{ $contact->id }}"
            @if ($contact->id==old('rubri_id'))
                selected
            @endif
            >{{ $contact->descrizion or 'cDeleted' }}</option>
        @endforeach
      </select>
    </div>

  {{-- Partita Iva --}}
    <div class="form-group">
      <label>Partita Iva</label>
      <div class="input-group">
        <span class="input-group-btn">
          <select type="button" class="btn btn-warning dropdown-toggle" name="partivaOp">
            <option value="eql">=</option>
            <option value="stw">[]...</option>
            <option value="cnt" selected>...[]...</option>
          </select>
        </span>
        <input type="text" class="form-control" name="partiva" value="{{ old('partiva') }}">
      </div>
    </div>
  {{-- Regione --}}
    <div class="form-group">
      <label>Regione</label>
      <select name="regione" class="form-control select2" style="width: 100%;">
        <option value=""> </option>
        @foreach ($regioni as $regione)
          <option value="{{ $regione->regione }}"@if ($regione->regione==old('regione')) selected @endif>{{ $regione->regione or 'cDeleted' }}</option>
        @endforeach
      </select>
    </div>

  {{-- Località --}}
    <div class="form-group">
      <label>Provncia</label>
      <select name="prov" class="form-control select2" style="width: 100%;">
        <option value=""> </option>
        @foreach ($zone as $loc)
          <option value="{{ $loc->prov }}"@if ($loc->prov==old('prov')) selected @endif>{{ $loc->prov or 'cDeleted' }}</option>
        @endforeach
      </select>
    </div>

  {{-- Agente --}}
    <div class="form-group">
      <label>Agente</label>
      <select name="agente" class="form-control select2" style="width: 100%;">
        <option value=""> </option>
        @foreach ($agenti as $agente)
          <option value="{{ $agente->agente }}"@if ($agente->agente==old('agente')) selected @endif>{{ $agente->agent->descrizion or 'cDeleted' }}</option>
        @endforeach
      </select>
    </div>

  {{-- Stato Cf --}}
    <div class="form-group">
      <label>Stato Contatto</label>
      <div class="radio">
        <label>
          <input type="radio" name="optStatocf" id="opt1" value="T" @if (old('optStatocf')=='T' || old('optStatocf')=='') checked @endif> {{ trans('client.activeStatus') }}
        </label>
        <label>
          <input type="radio" name="optStatocf" id="opt2" value="C"@if (old('optStatocf')=='C') checked @endif> {{ trans('client.closedStatus') }}
        </label>
        <label>
          <input type="radio" name="optStatocf" id="opt3" value="%" @if (old('optStatocf')=='%') checked @endif> {{ strtoupper(trans('client.allStatus')) }}
        </label>
      </div>
    </div>

  {{-- Date Next Visit --}}

  {{-- isModule --}}  
    <div class="form-group">
      <label>Analisi di Mercato 2019</label>
      <div class="radio">
        <label>
          <input type="radio" name="optModCarp" id="optMod1" @if (old('optModCarp')=='S') checked @endif value="S">Si
        </label>
        <label>
          <input type="radio" name="optModCarp" id="optMod2" @if (old('optModCarp')=='N') checked @endif value="N">No
        </label>
        <label>
          <input type="radio" name="optModCarp" id="optMod3" @if (old('optModCarp')=='') checked @endif value="" > {{ strtoupper(trans('client.allStatus')) }}
        </label>
      </div>
    </div>

  <div>
    <button type="submit" class="btn btn-primary">{{ trans('_message.submit') }}</button>
  </div>
</form>
