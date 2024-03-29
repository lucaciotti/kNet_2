@extends('layouts.app')

@section('htmlheader_title')
    - Analisi Mercato
@endsection

@section('contentheader_title')
    Revisione Analisi Mercato 
@endsection

@section('main-content')
	<div class="row">

		<div id="app" class="container">
			<div class="col-lg-12">	
			<form-carp-edit contact="{{$contact}}" 
			sysmkt="{{$sysMkt}}" modcarp="{{$modCarp}}" sysother="{{$sysOther}}">
				</form-carp-edit>
				{{-- @include ('modCarp01.partials.form')       --}}
			</div>
    	</div>
        
    </div>
@endsection

@section('extra_script')
	{{-- @include('layouts.partials.scripts.iCheck')
 	@include('layouts.partials.scripts.select2')
  	@include('layouts.partials.scripts.datePicker')
	<script src="/js/modCarp01.js"></script> --}}
  	{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.15.3/axios.js"></script>
	<script src="https://unpkg.com/vue@2.1.6/dist/vue.js"></script> --}}
@endsection