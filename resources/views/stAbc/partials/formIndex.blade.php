
<form action="{{ route($route) }}" method="post">
  <input type="hidden" name="_token" value="{{ csrf_token() }}">
  @if ($agentList)
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title" data-widget="collapse">{{ trans('stFatt.agent') }}</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          </div>
        </div>
        <div class="box-body">
          <div class="form-group">
            <label>{{ trans('stFatt.selAgent') }}</label>
            <select name="codag[]" class="form-control select2 selectAll" multiple="multiple" data-placeholder="Select Agents"
              style="width: 100%;">
              @foreach ($agentList as $agent)
              <option value="{{ $agent->codice }}" {{-- @if($agent->codice==$agente &&
                strlen($agent->codice)==strlen($agente)) --}}
                @if(isset($fltAgents) && in_array($agent->codice, $fltAgents, true))
                selected
                @endif
                >{{ $agent->descrizion or "Error $agent->codice - No Description" }}</option>
              @endforeach
            </select>
          </div>
          <div class="form-group">
            <label>&nbsp;
              <input type="checkbox" id="checkbox" class="selectAll"> &nbsp; Select All
            </label>
          </div>
        </div>
      </div>
  @endif

  @if ($customerList)   
    <div class="box box-default collapsed-box">
      <div class="box-header with-border">
        <h3 class="box-title" data-widget="collapse">Filtro Cliente</h3>
        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        </div>
      </div>
      <div class="box-body">
        @include(
        'stAbc.partials.filterCustomer',
        [
        'customerList' => $customerList,'customerSelected' => $customerSelected,
        'zoneList' => $zoneList,'zoneSelected' => $zoneSelected,
        'settoriList' => $settoriList,'settoreSelected' => $settoreSelected,
        ])
      </div>
    </div>
  @endif

  @if ($grpPrdList)
      <div class="box box-default collapsed-box">
        <div class="box-header with-border">
          <h3 class="box-title" data-widget="collapse">{{ trans('prod.groupProd') }}</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          </div>
        </div>
        <div class="box-body">
          @include(
          'stAbc.partials.filterPrdInd',
          [
          'grpPrdList' => $grpPrdList,
          'grpPrdSelected' => $grpPrdSelected,
          'optTipoProd' => $optTipoProd,
          ])
        </div>
      </div>
  @endif

  @if ($customer)
      <input type="hidden" name="codcli" value="{{ $customer }}">
  @endif
  

  <div class="box box-default">
    <div class="box-body">
      <button type="submit" class="btn btn-primary btn-block">{{ trans('_message.submit') }}</button>
    </div>
  </div>

</form>
