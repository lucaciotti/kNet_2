<table class="table table-hover table-condensed dtTbls_full" id="listGrpScad">
    <thead>
        <tr>
            <th colspan="5"></th>
            <th colspan="1">|</th>
            @if (RedisUser::get('ditta_DB')=='kNet_it')
            <th colspan="4" style="text-align: center;">n° Cod.Art.</th>
            @else
            <th colspan="2" style="text-align: center;">n° Cod.Art.</th>
            @endif
            <th colspan="1">|</th>
            <th colspan="2" style="text-align: center;">n° Gruppo Prod.</th>
        </tr>
        <tr>
            <th style="text-align: center;">Cod. Grp.</th>
            <th style="text-align: center;">Descrizione</th>

            <th style="text-align: center;">Link Listino</th>

            <th colspan="1">|</th>
            <th style="text-align: center;">Fatto?</th>
            
            <th colspan="1">|</th>
            <th style="text-align: center;">Residui</th>
            <th style="text-align: center;">Elaborati</th>
            @if (RedisUser::get('ditta_DB')=='kNet_it')   
                <th colspan="1">|</th>
                <th style="text-align: center;">PROMO</th>
            @endif

            <th colspan="1">|</th>
            <th style="text-align: center;">Residui</th>
            <th style="text-align: center;">Elaborati</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($customers as $customer)
        <tr>
            <td>
                @include('listini.partials.mdlFormCliGrp', ['id'=> $customer->first()->gruppocli,'customersList' => $customer->pluck('cliGrp')->unique()])
                {{-- <a href="#" data-toggle="popover" title="Condizioni" data-trigger="focus" 
                    data-content="
                        <div>
                            Clienti con Listino Attivo: <br>
                            @foreach ($customer->pluck('cliGrp')->unique() as $clients)
                                @if($clients instanceof \Illuminate\Support\Collection)
                                    @foreach ($clients as $client)
                                        <a href='{{ route('client::detail', ['codice'=>$client->codice]) }}' target='_blank'>
                                            {{ $client->codice }} </a> {{ $client->descrizion or '' }} <br>
                                            @endforeach
                                @else
                                    <a href='{{ route('client::detail', ['codice'=>$clients->codice]) }}' target='_blank'>
                                        {{ $clients->codice }} </a> {{ $clients->descrizion or '' }}<br>
                                @endif
                            @endforeach
                          </div>" 
                    data-placement="right"> <i class="fa fa-info-circle"> </i>
                </a> --}}
                <a href="#" data-toggle="modal" data-target=".bs-modal_{{$customer->first()->gruppocli}}"> {{ $customer->first()->gruppocli }} </a>
            </td>
            <td>{{ $customer->first()->grpcli->descrizion or '' }}</td>
            
            <td><a href="{{ route('listini::grpCli', $customer->first()->gruppocli) }}" target="_blank"> Listino Personalizzato </a></td>
            
            <th colspan="1">|</th>

            <td style="text-align: center;">@if($customer->where('wListOk.nList', '==', '1')->count()>0) <i class='fa fa-check-square-o'></i> @else - @endif </td>

            <th colspan="1">|</th>

            <td style="text-align: center; color:red;"><b>{{ $customer->where('nCodArt', '==', '1')->count() - $customer->where('nCodArt', '==', '1')->where('wListOk.nList', '==', '1')->count() - $customer->where('isPromo', '==', '1')->count() }}</b></td>
            <td style="text-align: center;">{{ $customer->where('nCodArt', '==', '1')->where('wListOk.nList', '==', '1')->count() }}</td>
            @if (RedisUser::get('ditta_DB')=='kNet_it')
                <th colspan="1">|</th>
                <td style="text-align: center;"><b>{{ $customer->where('isPromo', '==', '1')->count() }}</b></td>
            @endif
           
            <th colspan="1">|</th>
            <td style="text-align: center; color:red;"><b>{{ $customer->where('nGrpMag', '==', '1')->count() - $customer->where('nGrpMag', '==', '1')->where('wListOk.nList', '==', '1')->count() }}</b></td>
            <td style="text-align: center;">{{ $customer->where('nGrpMag', '==', '1')->where('wListOk.nList', '==', '1')->count() }}</td>

        </tr>
        @endforeach
    </tbody>
</table>