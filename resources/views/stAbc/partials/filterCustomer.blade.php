<div class="form-group">
    <label>{{ trans('client.client') }}</label>
    <select name="customerSelected[]" class="form-control select2" multiple="multiple"
        data-placeholder="{{ trans('client.client') }}" style="width: 100%;">
        @foreach ($customerList as $customer)
        <option value="{{ $customer->codice }}" @if (isset($customerSelected) && in_array($customer->codice, $customerSelected))
            selected
            @endif>{{ $customer->codice }} - {{ $customer->descrizion }}</option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <label>{{ trans('client.zone') }}</label>
    <select name="zoneSelected[]" class="form-control select2" multiple="multiple"
        data-placeholder="{{ trans('client.zone_plchld') }}" style="width: 100%;">
        @foreach ($zoneList as $zona)
        <option value="{{ $zona->codice }}" @if (isset($zoneSelected) && in_array($zona->codice, $zoneSelected))
            selected
            @endif>{{ $zona->descrizion }}</option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <label>Settore</label>
    <select name="settoreSelected[]" class="form-control select2" multiple="multiple" data-placeholder="Settore"
        style="width: 100%;">
        @foreach ($settoriList as $settore)
        <option value="{{ $settore->codice }}" @if(isset($settoreSelected) && in_array($settore->codice,
            $settoreSelected))
            selected
            @endif
            >[{{ $settore->codice }}] {{ $settore->descrizion }}</option>
        @endforeach
    </select>
</div>