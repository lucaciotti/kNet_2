<div class="row">
    <span class="floatleft">
        <dl class="dl-horizontal">
            <dt>{{ trans('client.descCli') }}</dt>
            <dd>
                <big><strong>{{$client->descrizion}}</strong></big><br>
                <small>{{$client->supragsoc}}</small>
            </dd>
            
            <dt>{{ trans('client.codeCli') }}</dt>
            <dd>{{$client->codice}}</dd>
        </dl>
    </span>


    <span class="floatright">
        <dl class="dl-horizontal">
            <dt>{{ trans('client.sector_full') }}</dt>
            <dd>{{$client->settore}} - @if($client->detSect) {{$client->detSect->descrizion}} @endif</dd>

            <dt>{{ trans('client.referenceAgent') }}</dt>
            <dd>@if($client->agent) {{$client->agent->descrizion}} @endif</dd>
            
            <hr class="divider">
            
            <dt>{{ trans('client.zone') }}</dt>
            <dd>@if($client->detZona) {{$client->detZona->descrizion}} - @endif @if($client->detNation) {{$client->detNation->descrizion}} @endif</dd>
            
            <dt>{{ trans('client.location') }}</dt>
            <dd>{{$client->localita}} ({{$client->prov}})</dd>
        </dl>
    </span>
</div>
