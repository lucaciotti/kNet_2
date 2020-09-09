@extends('_exports.pdf.masterPage.masterPdf')

@section('pdf-main')
<p class="page">
    <div class="row">
        @include('_exports.pdf.docDetail.docHead', [$head] )        
    </div>
                
    <div>
        <hr class="dividerPage">
    </div>
    <div class="row">
        <div class="contentTitle">{{ trans('doc.listRows') }}</div>
        @include('_exports.pdf.docDetail.tblRowDetail', [$rows, $head] )
    </div>
    <div>
        <hr class="dividerPage">
    </div>


    <div class="row">
        <br>
        {{-- <br> --}}
        @include('_exports.pdf.docDetail.docFooter2', [$destinaz, $head, $ddtOk, $totValueFOC] )
    </div>





    @if($head->tipomodulo == 'F' || $head->tipomodulo == 'N')
    <div>
        <hr class="dividerPage">
    </div>
    <div class="row">
        <div class="contentTitle">{{ trans('doc.lnkPayment') }}</div>
        @include('_exports.pdf.docDetail.tblPayment', ['scads'=> $head->scadenza] )
    </div>
    @endif




</p>
@endsection