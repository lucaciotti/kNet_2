<form action="{{ route('supplier::fltList') }}" method="post">
  <input type="hidden" name="_token" value="{{ csrf_token() }}">
  {{-- Ragione Sociale --}}
  <div class="form-group">
    <label>Ragione Sociale</label>
    <select name="ragsoc" class="form-control select2" style="width: 100%;">
          <option value=""> </option>
          @foreach ($fltsuppliers as $supplier)
            <option value="{{ $supplier->codice }}"
              @if ($supplier->codice==old('ragsoc') || $supplier->codice==old('codcli'))
                  selected
              @endif
              >{{ $supplier->descrizion or 'cDeleted' }}</option>
          @endforeach
        </select>
  </div>
  {{-- Codice fornitore --}}
  <div class="form-group">
    <label>Codice fornitore</label>
    <div class="input-group">
      <span class="input-group-btn">
        <select type="button" class="btn btn-warning dropdown-toggle" name="codcliOp">
          <option value="eql">=</option>
          <option value="stw">[]...</option>
          <option value="cnt" selected>...[]...</option>
        </select>
      </span>
      @php
          if (old('ragsoc')!='' || old('codcli')!=''){
            $codcliflt = (old('ragsoc') ? old('ragsoc') : old('codcli'));
          } else {
            $codcliflt = '';
          }
      @endphp
      <input type="text" class="form-control" name="codcli" value="{{ $codcliflt }}">
    </div>
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

  {{-- <div class="form-group">
    <label>{{ trans('supplier.sector') }}</label>
    <select name="settore[]" class="form-control select2" multiple="multiple" data-placeholder="{{ trans('supplier.sector_plchld') }}" style="width: 100%;">
      @foreach ($settori as $settore)
        <option value="{{ $settore->codice }}"@if (in_array($settore->codice, (old('settore') ? old('settore') : []))) selected @endif>{{ $settore->descrizion }}</option>
      @endforeach
    </select>
  </div> --}}
  {{-- <div class="form-group">
    <label>{{ trans('supplier.nation') }}</label>
    <select name="nazione[]" class="form-control select2" multiple="multiple" data-placeholder="{{ trans('supplier.nation_plchld') }}" style="width: 100%;">
      @foreach ($nazioni as $nazione)
        <option value="{{ $nazione->codice }}"@if (in_array($nazione->codice, (old('nazione') ? old('nazione') : []))) selected @endif>{{ $nazione->descrizion }}</option>
      @endforeach
    </select>
  </div> --}}
  {{-- <div class="form-group">
    <label>{{ trans('supplier.zone') }}</label>
    <select name="zona[]" class="form-control select2" multiple="multiple" data-placeholder="{{ trans('supplier.zone_plchld') }}" style="width: 100%;">
      @foreach ($zone as $zona)
        <option value="{{ $zona->codice }}"@if (in_array($zona->codice, (old('zona') ? old('zona') : []))) selected @endif>{{ $zona->descrizion }}</option>
      @endforeach
    </select>
  </div> --}}

  <div class="form-group">
    <label>{{ trans('supplier.statusCli') }}</label>
    <div class="radio">
      <label>
        <input type="radio" name="optStatocf" id="opt1" value="T" @if (old('optStatocf')=='T') checked @endif> {{ trans('supplier.activeStatus') }}
      </label>
      {{-- <label>
        <input type="radio" name="optStatocf" id="opt2" value="I" @if (old('optStatocf')=='I') checked @endif> {{ trans('supplier.unsolvedStatus') }}
      </label> --}}
      {{-- <label>
        <input type="radio" name="optStatocf" id="opt3" value="M" @if (old('optStatocf')=='M') checked @endif> {{ trans('supplier.defaultingStatus') }}
      </label> --}}
      <label>
        <input type="radio" name="optStatocf" id="opt4" value="C" @if (old('optStatocf')=='C') checked @endif> {{ trans('supplier.closedStatus') }}
      </label>
      <label>
        <input type="radio" name="optStatocf" id="opt5" value="" @if (old('optStatocf')=='') checked @endif> {{ strtoupper(trans('supplier.allStatus')) }}
      </label>
    </div>
  </div>

  <div>
    <button type="submit" class="btn btn-primary">{{ trans('_message.submit') }}</button>
  </div>
</form>
