@extends('layouts.app')
@section('htmlheader_title')
    {{ trans('home.headTitle') }}
@endsection

@section('contentheader_title')
    Ciao,<br> <strong>{{ Auth::user()->name }}</strong><br>
@endsection

@section('contentheader_description')
    {{ trans('home.contentDesc') }}
@endsection

@section('contentheader_breadcrumb')
  {!! Breadcrumbs::render('home') !!}
@endsection

@section('main-content')

	<br><br><br>

@if (!in_array(session('user.role'), ['user']))

	<div class="row">
		<div class="container">
      <div class="col-lg-6 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-aqua">
          <div class="inner">
            <h3>{{ $nOrdini }}</h3>
            <p>{{ trans('home.orderDeliver') }}</p>
          </div>
          <div class="icon">
            <i class="ion ion-ios-cart-outline"></i>
          </div>
          <a href="{{ route('doc::orderDeliver') }}" class="small-box-footer">{{ trans('home.moreInfo') }} <i class="fa fa-arrow-circle-right"></i></a>
        </div>
      </div>
      <!-- ./col -->
			<div class="col-lg-6 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-yellow">
          <div class="inner">
            <h3>{{ $nBolle }}</h3>
            <p>{{ trans('home.ddtReceive') }}</p>
          </div>
          <div class="icon">
            <i class="fa fa-truck fa-flip-horizontal"></i>
          </div>
          <a href="{{ route('doc::ddtReceive') }}" class="small-box-footer">{{ trans('home.moreInfo') }} <i class="fa fa-arrow-circle-right"></i></a>
        </div>
      </div>
		</div>
	</div>
	<div class="row">
		<div class="container">
      <div class="col-lg-6 col-xs-6">
        <div class="small-box bg-green">
          <div class="inner">
            <h3>{{ $nArticoli }}</h3>
            <p>{{ trans('home.newProd') }}</p>
          </div>
          <div class="icon">
            <i class="fa fa-barcode"></i>
          </div>
          <a href="{{ route('prod::newProd') }}" class="small-box-footer">{{ trans('home.moreInfo') }} <i class="fa fa-arrow-circle-right"></i></a>
        </div>
      </div>
      <div class="col-lg-6 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-red">
          <div class="inner">
            <h3>{{ $nScadenze }}</h3>
            <p>{{ trans('home.debtPay') }}</p>
          </div>
          <div class="icon">
            <i class="ion ion-cash"></i>
          </div>
          <a href="{{ route('scad::list') }}" class="small-box-footer">{{ trans('home.moreInfo') }} <i class="fa fa-arrow-circle-right"></i></a>
        </div>
      </div>
		</div>
  </div>
@else
  <div class="row">
    <div class="col-lg-10 col-lg-offset-1">
      <h3>{{ trans('home.newUserMessage') }}</h3>
      <p>
        {{ trans('home.pleaseWait') }}<br>
        {{ trans('home.thanks') }}
      </p>
    </div>
  </div>
@endif

@endsection