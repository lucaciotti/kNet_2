@extends('layouts.app') 
@section('htmlheader_title') - Listini in Scadenza
@endsection
 
@section('contentheader_title')
Listini in Scadenza {{ $endOfYear->format('d-m-Y')}}
@endsection

@section('contentheader_description') 
Non ancora Estesi al 2019
@endsection

@section('main-content')
<div class="row">
    <div class="container">
        <div class="col-lg-12">
            @include('listini.partials.tblCliScad', [ 'customers' => $customers, ])
        </div>
    </div>
</div>
@endsection
 
@section('extra_script')
    @include('layouts.partials.scripts.iCheck')
    @include('layouts.partials.scripts.select2')
    @include('layouts.partials.scripts.datePicker')
@endsection