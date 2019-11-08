@extends('layouts.app')

@section('htmlheader_title')
    - {{trans('modRicFat.headTitle')}}
@endsection

@section('contentheader_title')
    {{trans('modRicFat.headTitle')}}
@endsection

@section('main-content')
	<div class="row">

		<div id="app" class="container">
			<div class="col-lg-12">	
			<form-ric-fat contact="{{$client}}" sysmkt="{{$sysMkt}}" sysother="{{$sysOther}}" ditta={{ RedisUser::get('ditta_DB') }}>
			</form-ric-fat>
			</div>
    	</div>
        
    </div>
@endsection

@section('extra_script')
@endsection