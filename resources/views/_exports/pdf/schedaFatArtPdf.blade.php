@extends('_exports.pdf.masterPage.masterPdf')

@section('pdf-main')
    <p class="page">

        @include('_exports.pdf.schedaFatArt.infoCustomer', [
        'client' => $customer,
        ])

        <div>
            <hr class="dividerPage">
        </div>

        <div class="row">
            <div class="contentTitle">KRONA</div>

            @include('_exports.pdf.schedaFatArt.tblDetail', [
              'fatList' => $fatList->where('tipoProd', 'KRONA'),
              'thisYear' => $thisYear,
              'yearBack' => $yearback,
            ])

        </div>

        <div><hr class="dividerPage"></div>

        <div class="row">
            <div class="contentTitle">KOBLENZ</div>
        
            @include('_exports.pdf.schedaFatArt.tblDetail', [
            'fatList' => $fatList->where('tipoProd', 'KOBLENZ'),
            'thisYear' => $thisYear,
            'yearBack' => $yearback,
            ])
        
        </div>

        <div>
            <hr class="dividerPage">
        </div>
        
        <div class="row">
            <div class="contentTitle">KUBICA - HINGES</div>
        
            @include('_exports.pdf.schedaFatArt.tblDetail', [
            'fatList' => $fatList->where('tipoProd', 'KUBIKA'),
            'thisYear' => $thisYear,
            'yearBack' => $yearback,
            ])
        
        </div>
    </p>

@endsection