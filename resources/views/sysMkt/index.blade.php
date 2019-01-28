@extends('layouts.app')

@section('htmlheader_title')
    - System Mkt
@endsection

@section('contentheader_title')
    System Mkt
@endsection

@section('main-content')
	<div class="row">

		<div id="app" class="container">
			<div class="col-lg-12">	
				{{-- @include ('sysMkt.partials.form')       --}}
				<form-sys-mkt></form-sys-mkt>

				@include ('sysMkt.partials.list')   
			</div>
    	</div>
        
    </div>
@endsection

@section('extra_script')
  	@include('layouts.partials.scripts.iCheck')
 	@include('layouts.partials.scripts.select2')
  	@include('layouts.partials.scripts.datePicker')
	{{-- <script src="/js/app_sysmkt.js"></script> --}}
@endsection
