@extends('layouts.app')

@section('htmlheader_title')
    - Modulo Falegnami
@endsection

@section('contentheader_title')
    Modulo Falegnami
@endsection

@section('main-content')
	<div class="row">

		<div id="app" class="container">
			<div class="col-lg-12">	
				@include ('modCarp01.partials.form')      
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
	<script src="/js/app_modCarp01.js"></script>
@endsection