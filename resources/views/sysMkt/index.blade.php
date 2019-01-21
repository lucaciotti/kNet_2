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
				@include ('sysMkt.partials.list')   
					
				@include ('sysMkt.partials.form')      
			</div>
    	</div>
        
    </div>
@endsection

@section('extra_script')
  	@include('layouts.partials.scripts.iCheck')
 	@include('layouts.partials.scripts.select2')
  	@include('layouts.partials.scripts.datePicker')
  	<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.15.3/axios.js"></script>
	<script src="https://unpkg.com/vue@2.1.6/dist/vue.js"></script>
	<script src="/js/app_sysmkt.js"></script>
@endsection
