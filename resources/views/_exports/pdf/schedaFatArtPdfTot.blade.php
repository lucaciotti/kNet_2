@extends('_exports.pdf.masterPage.masterPdf')

@section('pdf-main')
    <p class="page">

        <div class="row">
            <div class="contentTitle">Fatturato Confronto Anni</div>

            @include('_exports.pdf.schedaFatArt.tblCustomers', [
              'fatList' => $fatList,
              'thisYear' => $thisYear,
              'yearBack' => $yearback,
            ])

        </div>

    </p>

@endsection