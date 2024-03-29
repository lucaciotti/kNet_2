<table id="statFattArtCli" style="text-align: center;">
    {{-- <col width="5"> --}}
    <col width="50">
    <col width="150">
    <col width="280">
    
    <!--Cliente-->
    @if($yearBack==4)
    <col width="50"><col width="50"><col width="80"><col width="10">
    <!--Val N-4--> @endif
    @if($yearBack>=3)
    <col width="50"><col width="50"><col width="80"><col width="10">
    <!--Val N-3--> @endif
    @if($yearBack>=2)
    <col width="50"><col width="50"><col width="80"><col width="10">
    <!--Val N-2--> @endif
    <col width="50"><col width="50"><col width="80"><col width="10">
    <col width="50"><col width="50"><col width="80">
    <!--Val N-->
    <thead>
        <tr style="background-color: #D6EEEE;">
            <th>&nbsp;</th>
            <th>&nbsp;</th>
            <th>&nbsp;</th>
            @if($yearBack==4) 
                <th colspan="3" style="text-align: center;">{!! $thisYear-4 !!}</th>
            @endif
            @if($yearBack>=3) 
                <th colspan="3" style="text-align: center;">{!! $thisYear-3 !!}</th>
            @endif
            @if($yearBack>=2)
                <th colspan="3" style="text-align: center;">{!! $thisYear-2 !!}</th>
            @endif
            <th colspan="3" style="text-align: center;">{!! $thisYear-1 !!}</th>
            <th colspan="3" style="text-align: center;">
                {!! $thisYear !!}
                @if($fatList->first())
                    ({{ trans('stFatt.'.strtolower(Carbon\Carbon::createFromDate(null, $fatList->first()->meseRif, 25)->format('F'))) }})
                @endif
            </th>
        </tr>
        <tr>
            <th style="text-align: center;">Tipo Riga</th>
            <th style="text-align: center;">Gruppo / Cod.Art.</th>
            <th style="text-align: center;">{{ trans('prod.descArt')}}</th>
            @if($yearBack==4) 
                <th style="text-align: center;">{{ trans('stFatt.qta')}}</th>
                <th style="text-align: center;">P.M.</th>
                <th style="text-align: center;">{{ trans('stFatt.revenue')}}</th>
            @endif
            @if($yearBack>=3)
                <th style="text-align: center;">{{ trans('stFatt.qta')}}</th>
                <th style="text-align: center;">P.M.</th>
                <th style="text-align: center;">{{ trans('stFatt.revenue')}}</th>
            @endif
            @if($yearBack>=2)
                <th style="text-align: center;">{{ trans('stFatt.qta')}}</th>
                <th style="text-align: center;">P.M.</th>
                <th style="text-align: center;">{{ trans('stFatt.revenue')}}</th>
            @endif
            <th style="text-align: center;">{{ trans('stFatt.qta')}}</th>
            <th style="text-align: center;">P.M.</th>
            <th style="text-align: center;">{{ trans('stFatt.revenue')}}</th>
            <th style="text-align: center;">{{ trans('stFatt.qta')}}</th>
            <th style="text-align: center;">P.M.</th>
            <th style="text-align: center;">{{ trans('stFatt.revenue')}}</th>
        </tr>
    </thead>
    <tbody>
        @php
        $gruppo='';
        $newGruppo='';
        $macroGrp='';
        $newMacroGrp='';
        @endphp
        @foreach ($fatList as $fatArt)
        @php
        $newGruppo=$fatArt->codGruppo;
        $newMacroGrp=$fatArt->macrogrp;
        @endphp
        @if($newMacroGrp!=$macroGrp)
            {{-- RIGA MACROGRUPPO --}}
            <tr style="font-size: 8pt;">
                <td>MagroGruppo</td>
                <td style="text-align: left;">
                    {{ $newMacroGrp }} - {{ $fatArt->descrMacrogrp}}
                </td>
                <td>&nbsp;</td>
                @if($yearBack==4)
                <td>{{$fatList->where('macrogrp', $newMacroGrp)->sum('qtaN4')}}</td>
                <td>&nbsp;</td>
                <td>{{$fatList->where('macrogrp', $newMacroGrp)->sum('fatN4')}}</td>
                @endif
                @if($yearBack>=3)
                <td>{{$fatList->where('macrogrp', $newMacroGrp)->sum('qtaN3')}}</td>
                <td>&nbsp;</td>
                <td>{{$fatList->where('macrogrp', $newMacroGrp)->sum('fatN3')}}</td>
                @endif
                @if($yearBack>=2)
                <td>{{$fatList->where('macrogrp', $newMacroGrp)->sum('qtaN2')}}</td>
                <td>&nbsp;</td>
                <td>{{$fatList->where('macrogrp', $newMacroGrp)->sum('fatN2')}}</td>
                @endif
                <td>{{$fatList->where('macrogrp', $newMacroGrp)->sum('qtaN1')}}</td>
                <td>&nbsp;</td>
                <td>{{$fatList->where('macrogrp', $newMacroGrp)->sum('fatN1')}}</td>
                <td>{{$fatList->where('macrogrp', $newMacroGrp)->sum('qtaN')}}</td>
                <td>&nbsp;</td>
                <td>{{$fatList->where('macrogrp', $newMacroGrp)->sum('fatN')}}</td>
            </tr>
            @php
            $macroGrp=$newMacroGrp;
            @endphp
        @endif
        @if($newGruppo!=$gruppo)
            {{-- RIGA MACROGRUPPO --}}
            <tr style="font-size: 8pt;">
                <td>Gruppo</td>
                <td style="text-align: left;">
                    {{ $newGruppo }} - {{ $fatArt->descrGruppo}}
                </td>
                <td>&nbsp;</td>
                @if($yearBack==4)
                <td>{{$fatList->where('codGruppo', $newGruppo)->sum('qtaN4')}}</td>
                <td>&nbsp;</td>
                <td>{{$fatList->where('codGruppo', $newGruppo)->sum('fatN4')}}</td>
                @endif
                @if($yearBack>=3)
                <td>{{$fatList->where('codGruppo', $newGruppo)->sum('qtaN3')}}</td>
                <td>&nbsp;</td>
                <td>{{$fatList->where('codGruppo', $newGruppo)->sum('fatN3')}}</td>
                @endif
                @if($yearBack>=2)
                <td>{{$fatList->where('codGruppo', $newGruppo)->sum('qtaN2')}}</td>
                <td>&nbsp;</td>
                <td>{{$fatList->where('codGruppo', $newGruppo)->sum('fatN2')}}</td>
                @endif
                <td>{{$fatList->where('codGruppo', $newGruppo)->sum('qtaN1')}}</td>
                <td>&nbsp;</td>
                <td>{{$fatList->where('codGruppo', $newGruppo)->sum('fatN1')}}</td>
                <td>{{$fatList->where('codGruppo', $newGruppo)->sum('qtaN')}}</td>
                <td>&nbsp;</td>
                <td>{{$fatList->where('codGruppo', $newGruppo)->sum('fatN')}}</td>
            </tr>
            @php
            $gruppo=$newGruppo;
            @endphp
        @endif
        <tr>
            <td>Articolo</td>
            <th>{{$fatArt->codicearti}}</th>
            <th style="font-size: 7pt; text-align: left;">{{$fatArt->descrArt}}</th>
            @if($yearBack==4) 
                <td>{{ $fatArt->qtaN4 }}</td>
                <td>{{ $fatArt->pmN4 }}</td>
                <td>{{ $fatArt->fatN4 }}</td>
            @endif
            @if($yearBack>=3)
                <td>{{ $fatArt->qtaN3 }}</td>
                <td>{{ $fatArt->pmN3 }}</td>
                <td>{{ $fatArt->fatN3 }}</td>
            @endif
            @if($yearBack>=2)
                <td>{{ $fatArt->qtaN2 }}</td>
                <td>{{ $fatArt->pmN2 }}</td>
                <td>{{ $fatArt->fatN2 }}</td>
            @endif
            <td>{{ $fatArt->qtaN1 }}</td>
            <td>{{ $fatArt->pmN1 }}</td>
            <td>{{ $fatArt->fatN1 }}</td>

            <td>{{ $fatArt->qtaN }}</td>
            <td>{{ $fatArt->pmN }}</td>
            <td>{{ $fatArt->fatN }}</td>
        </tr>
        @endforeach
    </tbody>
    {{-- <tfoot class="bg-gray">
        <tr>
            <th>{{ strtoupper(trans('stFatt.granTot')) }}</th>
        </tr>
    </tfoot> --}}
</table>
