@extends('_exports.pdf.masterPage.masterPdf')

@section('pdf-main')
    <p class="page">
        @if($customer)
            @include('_exports.pdf.schedaFatArt.infoCustomer', [
            'client' => $customer,
            ])

            <div>
                <hr class="dividerPage">
            </div>
        @endif
        
        @if(!empty($fatList->where('tipoProd', 'KRONA')->first()))
            <div class="row">
                <div class="contentTitle">
                    KRONA
                    <span class="contentSubTitle">
                         - 
                        @if($pariperiodo && !$onlyMese) [Pari Periodo] {
                        {{ trans('stFatt.'.strtolower(Carbon\Carbon::createFromDate(null, $mese, 25)->format('F'))) }} } @endif
                        @if($onlyMese) [Solo Mese] {
                        {{ trans('stFatt.'.strtolower(Carbon\Carbon::createFromDate(null, $mese, 25)->format('F'))) }} } @endif
                    </span>
                </div>

                @include('_exports.pdf.schedaFatArt.tblDetail', [
                'fatList' => $fatList->where('tipoProd', 'KRONA'),
                'thisYear' => $thisYear,
                'yearBack' => $yearback,
                ])

            </div>

            <div>
                <hr class="dividerPage">
            </div>
        @endif
        
        @if(!empty($fatList->where('tipoProd', 'KOBLENZ')->first()))
            <div class="row">
                <div class="contentTitle">
                    KOBLENZ
                    <span class="contentSubTitle">
                        -
                        @if($pariperiodo && !$onlyMese) [Pari Periodo] {
                        {{ trans('stFatt.'.strtolower(Carbon\Carbon::createFromDate(null, $mese, 25)->format('F'))) }} } @endif
                        @if($onlyMese) [Solo Mese] {
                        {{ trans('stFatt.'.strtolower(Carbon\Carbon::createFromDate(null, $mese, 25)->format('F'))) }} } @endif
                    </span>
                </div>
            
                @include('_exports.pdf.schedaFatArt.tblDetail', [
                'fatList' => $fatList->where('tipoProd', 'KOBLENZ'),
                'thisYear' => $thisYear,
                'yearBack' => $yearback,
                ])
            
            </div>

            <div>
                <hr class="dividerPage">
            </div>
        @endif

        @if(!empty($fatList->where('tipoProd', 'KUBIKA')->first()))        
            <div class="row">
                <div class="contentTitle">
                    KUBICA - HINGES
                    <span class="contentSubTitle">
                        -
                        @if($pariperiodo && !$onlyMese) [Pari Periodo] {
                        {{ trans('stFatt.'.strtolower(Carbon\Carbon::createFromDate(null, $mese, 25)->format('F'))) }} } @endif
                        @if($onlyMese) [Solo Mese] {
                        {{ trans('stFatt.'.strtolower(Carbon\Carbon::createFromDate(null, $mese, 25)->format('F'))) }} } @endif
                    </span>
                </div>
            
                @include('_exports.pdf.schedaFatArt.tblDetail', [
                'fatList' => $fatList->where('tipoProd', 'KUBIKA'),
                'thisYear' => $thisYear,
                'yearBack' => $yearback,
                ])        
            </div>

            <div>
                <hr class="dividerPage">
            </div>
        @endif

        @if(!empty($fatList->where('tipoProd', 'PLANET')->first()))
            <div class="row">
                <div class="contentTitle">
                    PLANET
                    <span class="contentSubTitle">
                        -
                        @if($pariperiodo && !$onlyMese) [Pari Periodo] {
                        {{ trans('stFatt.'.strtolower(Carbon\Carbon::createFromDate(null, $mese, 25)->format('F'))) }} } @endif
                        @if($onlyMese) [Solo Mese] {
                        {{ trans('stFatt.'.strtolower(Carbon\Carbon::createFromDate(null, $mese, 25)->format('F'))) }} } @endif
                    </span>
                </div>
            
                @include('_exports.pdf.schedaFatArt.tblDetail', [
                'fatList' => $fatList->where('tipoProd', 'PLANET'),
                'thisYear' => $thisYear,
                'yearBack' => $yearback,
                ])
            </div>
            
            <div>
                <hr class="dividerPage">
            </div>
        @endif
        
        <div class="row">
            <div class="contentTitle">TOTAL</div>
            @include('_exports.pdf.schedaFatArt.tblGranTotal', [
            'fatList' => $fatList,
            'thisYear' => $thisYear,
            'yearBack' => $yearback,
            ])
        </div>
    </p>

@endsection