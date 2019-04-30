@extends('_exports.pdf.masterPage.masterPdf')
@section('pdf-main')
<p class="page">
    <div class="row">
        <div class="contentTitle">Abc Items - {{$agente->descrizion}}</div>
        @include('_exports.pdf.schedaCli.tblAbc', [ 
            'AbcProds' => $AbcProds, 
            'thisYear' => $thisYear, 
            'prevYear' => $prevYear, 
            'thisMonth' => $thisMonth 
            ])
    </div>
</p>
@endsection