@extends('layouts.app') 
@section('htmlheader_title') - Listini in Scadenza
@endsection
 
@section('contentheader_title')
Listini in Scadenza {{ $endOfYear->format('d-m-Y')}}
@endsection

@section('contentheader_description') 
Non ancora Estesi al 2021
@endsection

@section('main-content')
<div class="row">
    <div class="container">
        <div class="col-lg-12">

            <div class="box box-default">
                <div class="box-body">
                    @include('listini.partials.tblGrpScad', [ 'customers' => $customers, ])
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
 
@section('extra_script')
    @include('layouts.partials.scripts.iCheck')
    @include('layouts.partials.scripts.select2')
    @include('layouts.partials.scripts.datePicker')
    <script>
        $(document).ready(function(){
          $('[data-toggle="popover"]').popover({
            html: true,
            trigger: 'focus'
            });
        });
    </script>
@endsection