
    <div class="form-group">
        <label>{{ trans('prod.groupProd') }}</label>
        <select name="grpPrdSelected[]" class="form-control select2" multiple="multiple"
            data-placeholder="{{ trans('prod.group_plchld') }}" style="width: 100%;">
            @foreach ($grpPrdList as $gruppo)
            <option value="{{ $gruppo->codice }}" @if(isset($grpPrdSelected) && in_array($gruppo->codice, $grpPrdSelected))
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
                <input type="radio" name="optTipoProd" id="opt1" value="" @if(!isset($optTipoProd)) checked @endif> {{ trans('doc.allDocs') }}
            </label>
            <label>
                <input type="radio" name="optTipoProd" id="opt2" value="KRONA" @if(isset($optTipoProd) && $optTipoProd=="KRONA") checked @endif> Krona
            </label>
            <label>
                <input type="radio" name="optTipoProd" id="opt3" value="KOBLENZ" @if(isset($optTipoProd) && $optTipoProd=="KOBLENZ") checked @endif> Koblenz
            </label>
            <label>
                <input type="radio" name="optTipoProd" id="opt4" value="KUBIKA" @if(isset($optTipoProd) && $optTipoProd=="KUBIKA") checked @endif> Kubica
            </label>
            @if(RedisUser::get('ditta')=='knet_es')
            <label>
                <input type="radio" name="optTipoProd" id="opt5" value="PLANET" @if(isset($optTipoProd) && $optTipoProd=="PLANET") checked @endif> Planet
            </label>
            @endif
        </div>
    </div>