<table class="table table-hover table-striped" id="statFattArtGranTot" style="text-align: center;">
    <col width="5">
    <col width="5">
    <col width="150">
    <col width="280"><!--Cliente-->
    @if($yearBack==4)
    <col width="50">
    <col width="50">
    <col width="80">
    <col width="10">
    <!--Val N-4--> @endif
    @if($yearBack>=3)
    <col width="50">
    <col width="50">
    <col width="80">
    <col width="10">
    <!--Val N-3--> @endif
    @if($yearBack>=2)
    <col width="50">
    <col width="50">
    <col width="80">
    <col width="10">
    <!--Val N-2--> @endif
    <col width="50">
    <col width="50">
    <col width="80">
    <col width="10">
    <col width="50">
    <col width="50">
    <col width="80">
    <!--Val N-->
    <thead>
        <tr>
            <th colspan="4">&nbsp;</th>
            @if($yearBack==4)
            <th colspan="3" style="text-align: center;">{!! $thisYear-4 !!}</th>
            <th>&nbsp;</th>
            @endif
            @if($yearBack>=3)
            <th colspan="3" style="text-align: center;">{!! $thisYear-3 !!}</th>
            <th>&nbsp;</th>
            @endif
            @if($yearBack>=2)
            <th colspan="3" style="text-align: center;">{!! $thisYear-2 !!}</th>
            <th>&nbsp;</th>
            @endif
            <th colspan="3" style="text-align: center;">{!! $thisYear-1 !!}</th>
            <th>&nbsp;</th>
            <th colspan="3" style="text-align: center;">
                {!! $thisYear !!}
                @if(!$pariperiodo && !$onlyMese)
                ({{ trans('stFatt.'.strtolower(Carbon\Carbon::createFromDate(null, $mese, 25)->format('F'))) }})
                @endif</th>
            </th>
        </tr>
        <tr>
            <th colspan='2' style="text-align: center;">Gruppo</th>
            <th style="text-align: center;">{{ trans('prod.codeArt')}}</th>
            <th style="text-align: center;">{{ trans('prod.descArt')}}</th>
            @if($yearBack==4)
            <th style="text-align: center;">{{ trans('stFatt.qta')}}</th>
            <th style="text-align: center;">P.M.</th>
            <th style="text-align: center;">{{ trans('stFatt.revenue')}}</th>
            <th rowspan="1">&nbsp;</th>
            @endif
            @if($yearBack>=3)
            <th style="text-align: center;">{{ trans('stFatt.qta')}}</th>
            <th style="text-align: center;">P.M.</th>
            <th style="text-align: center;">{{ trans('stFatt.revenue')}}</th>
            <th rowspan="1">&nbsp;</th>
            @endif
            @if($yearBack>=2)
            <th style="text-align: center;">{{ trans('stFatt.qta')}}</th>
            <th style="text-align: center;">P.M.</th>
            <th style="text-align: center;">{{ trans('stFatt.revenue')}}</th>
            <th rowspan="1">&nbsp;</th>
            @endif
            <th style="text-align: center;">{{ trans('stFatt.qta')}}</th>
            <th style="text-align: center;">P.M.</th>
            <th style="text-align: center;">{{ trans('stFatt.revenue')}}</th>
            <th rowspan="1">&nbsp;</th>
            <th style="text-align: center;">{{ trans('stFatt.qta')}}</th>
            <th style="text-align: center;">P.M.</th>
            <th style="text-align: center;">{{ trans('stFatt.revenue')}}</th>
        </tr>
    </thead>
    <tfoot class="bg-gray">
        <tr>
            <th colspan='4'>{{ strtoupper(trans('stFatt.granTot')) }}</th>
            @if($yearBack==4)
            <td>{{ $fatList->sum('qtaN4') }}</td>
            <td>&nbsp;</td>
            <td><strong>{{ currency($fatList->sum('fatN4')) }}</strong></td>
            <td>|</td>
            @endif
            @if($yearBack>=3)
            <td>{{ $fatList->sum('qtaN3') }}</td>
            <td>&nbsp;</td>
            <td><strong>{{ currency($fatList->sum('fatN3')) }}</strong></td>
            <td>|</td>
            @endif
            @if($yearBack>=2)
            <td>{{ $fatList->sum('qtaN2') }}</td>
            <td>&nbsp;</td>
            <td><strong>{{ currency($fatList->sum('fatN2')) }}</strong></td>
            <td>|</td>
            @endif
            <td>{{ $fatList->sum('qtaN1') }}</td>
            <td>&nbsp;</td>
            <td><strong>{{ currency($fatList->sum('fatN1')) }}</strong></td>
            <td>|</td>

            <td>{{ $fatList->sum('qtaN') }}</td>
            <td>&nbsp;</td>
            <td><strong>{{ currency($fatList->sum('fatN')) }}</strong></td>
        </tr>
    </tfoot>
</table>