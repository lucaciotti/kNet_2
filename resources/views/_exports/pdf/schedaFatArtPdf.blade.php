@extends('_exports.pdf.masterPage.masterPdf')

@section('pdf-main')
    <p class="page">

        <div class="row">
            <div class="contentTitle">Turnover Situation</div>

            @include('_exports.pdf.schedaFatArt.tblDetail', [
              'fatList' => $fatList,
              'thisYear' => $thisYear,
              'yearBack' => $yearback,
            ])

        </div>

        <div><hr class="dividerPage"></div>

    </p>

@endsection