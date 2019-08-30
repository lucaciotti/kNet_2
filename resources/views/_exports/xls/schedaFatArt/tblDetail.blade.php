<table class="table table-hover table-striped" id="statFattArtCli" style="text-align: center;">
    <col width="5">
    <col width="5">
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
        <tr>
            <th colspan="4">&nbsp;</th>
            @if($yearBack==4) 
                <th colspan="3" style="text-align: center;">{!! $thisYear-4 !!}</th>
                <th>&nbsp;</th>
            @endif
            @if($yearBack>=3) 
                <th colspan="3"style="text-align: center;">{!! $thisYear-3 !!}</th>
                <th>&nbsp;</th>
            @endif
            @if($yearBack>=2)
                <th colspan="3"style="text-align: center;">{!! $thisYear-2 !!}</th>
                <th>&nbsp;</th>
            @endif
            <th colspan="3"style="text-align: center;">{!! $thisYear-1 !!}</th>
            <th>&nbsp;</th>
            <th colspan="3"style="text-align: center;">
                {!! $thisYear !!}
                @if($fatList->first())
                    ({{ trans('stFatt.'.strtolower(Carbon\Carbon::createFromDate(null, $fatList->first()->meseRif, 25)->format('F'))) }})
                @endif
            </th>
        </tr>
        <tr>
            <th colspan='2' style="text-align: center;">Gruppo</th>
            <th style="text-align: center;">{{ trans('prod.codeArt')}}</th>
            <th style="text-align: center;">{{ trans('prod.descArt')}}</th>
            @if($yearBack==4) 
                <th style="text-align: center;">{{ trans('stFatt.qta')}}</th>
                <th style="text-align: center;">P.M.</th>
                <th style="text-align: center;">{{ trans('stFatt.revenue')}}</th> <th rowspan="1">&nbsp;</th>
            @endif
            @if($yearBack>=3)
                <th style="text-align: center;">{{ trans('stFatt.qta')}}</th>
                <th style="text-align: center;">P.M.</th>
                <th style="text-align: center;">{{ trans('stFatt.revenue')}}</th> <th rowspan="1">&nbsp;</th>
            @endif
            @if($yearBack>=2)
                <th style="text-align: center;">{{ trans('stFatt.qta')}}</th>
                <th style="text-align: center;">P.M.</th>
                <th style="text-align: center;">{{ trans('stFatt.revenue')}}</th> <th rowspan="1">&nbsp;</th>
            @endif
            <th style="text-align: center;">{{ trans('stFatt.qta')}}</th>
            <th style="text-align: center;">P.M.</th>
            <th style="text-align: center;">{{ trans('stFatt.revenue')}}</th><th rowspan="1">&nbsp;</th>
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
            <tr style="font-size: 8pt;">
                <th colspan="4" style="text-align: left;">
                    {{ $newMacroGrp }} - {{ $fatArt->descrMacrogrp}}
                </th>
                @if($yearBack==4)
                <th colspan="2">&nbsp;</th>
                <th>{{currency($fatList->where('macrogrp', $newMacroGrp)->sum('fatN4'))}}</th>
                <th>&nbsp;</th>
                @endif
                @if($yearBack>=3)
                <th colspan="2">&nbsp;</th>
                <th>{{currency($fatList->where('macrogrp', $newMacroGrp)->sum('fatN3'))}}</th>
                <th>&nbsp;</th>
                @endif
                @if($yearBack>=2)
                <th colspan="2">&nbsp;</th>
                <th>{{currency($fatList->where('macrogrp', $newMacroGrp)->sum('fatN2'))}}</th>
                <th>&nbsp;</th>
                @endif
                <th colspan="2">&nbsp;</th>
                <th>{{currency($fatList->where('macrogrp', $newMacroGrp)->sum('fatN1'))}}</th>
                <th>&nbsp;</th>
                <th colspan="2">&nbsp;</th>
                <th>{{currency($fatList->where('macrogrp', $newMacroGrp)->sum('fatN'))}}</th>
            </tr>
            @php
            $macroGrp=$newMacroGrp;
            @endphp
        @endif
        @if($newGruppo!=$gruppo)
            <tr style="font-size: 8pt;">
                <th>&nbsp;</th>
                <th colspan="3" style="text-align: left;">
                    {{ $newGruppo }} - {{ $fatArt->descrGruppo}}
                </th>
                @if($yearBack==4)
                <th colspan="2">&nbsp;</th>
                <th>{{currency($fatList->where('codGruppo', $newGruppo)->sum('fatN4'))}}</th>
                <th>&nbsp;</th>
                @endif
                @if($yearBack>=3)
                <th colspan="2">&nbsp;</th>
                <th>{{currency($fatList->where('codGruppo', $newGruppo)->sum('fatN3'))}}</th>
                <th>&nbsp;</th>
                @endif
                @if($yearBack>=2)
                <th colspan="2">&nbsp;</th>
                <th>{{currency($fatList->where('codGruppo', $newGruppo)->sum('fatN2'))}}</th>
                <th>&nbsp;</th>
                @endif
                <th colspan="2">&nbsp;</th>
                <th>{{currency($fatList->where('codGruppo', $newGruppo)->sum('fatN1'))}}</th>
                <th>&nbsp;</th>
                <th colspan="2">&nbsp;</th>
                <th>{{currency($fatList->where('codGruppo', $newGruppo)->sum('fatN'))}}</th>
            </tr>
            @php
            $gruppo=$newGruppo;
            @endphp
        @endif
        <tr>
            <th colspan='2'>&nbsp;</th>
            <th>{{$fatArt->codicearti}}</th>
            <th style="font-size: 7pt; text-align: left;">{{$fatArt->descrArt}}</th>
            @if($yearBack==4) 
                <td>{{ $fatArt->qtaN4 }}</td>
                <td>{{ currency($fatArt->pmN4) }}</td>
                <td><strong>{{ currency($fatArt->fatN4) }}</strong></td>
                <td>|</td>
            @endif
            @if($yearBack>=3)
                <td>{{ $fatArt->qtaN3 }}</td>
                <td>{{ currency($fatArt->pmN3) }}</td>
                <td><strong>{{ currency($fatArt->fatN3) }}</strong></td>
                <td>|</td>
            @endif
            @if($yearBack>=2)
                <td>{{ $fatArt->qtaN2 }}</td>
                <td>{{ currency($fatArt->pmN2) }}</td>
                <td><strong>{{ currency($fatArt->fatN2) }}</strong></td>
                <td>|</td>
            @endif
            <td>{{ $fatArt->qtaN1 }}</td>
            <td>{{ currency($fatArt->pmN1) }}</td>
            <td><strong>{{ currency($fatArt->fatN1) }}</strong></td>
            <td>|</td>

            <td>{{ $fatArt->qtaN }}</td>
            <td>{{ currency($fatArt->pmN) }}</td>
            <td><strong>{{ currency($fatArt->fatN) }}</strong></td>
        </tr>
        @endforeach
    </tbody>
    {{-- <tfoot class="bg-gray">
        <tr>
            <th>{{ strtoupper(trans('stFatt.granTot')) }}</th>
        </tr>
    </tfoot> --}}
</table>
