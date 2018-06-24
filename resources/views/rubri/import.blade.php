@extends('layouts.app')

@section('htmlheader_title')
	Import Rubrica LEAD
@endsection


@section('main-content')
	@if(Session::has('success'))
        <div class="alert-box success">
            <h2>{!! Session::get('success') !!}</h2>
        </div>
    @endif
    <div class="row">
        <div class="container">
            <div class="col-lg-12">
                <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title" data-widget="collapse">{{ trans('user.importUser') }}</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                                       
                        <form action="{{ route('rubri::import') }}" method="POST" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label>{{ trans('user.loadExcel') }}</label>
                                <input type="file" id="InputFile" name="file">
                            </div>
                            
                            <div class="form-group">
                            <label>Insert Country of Import</label>
                            <input type="text" class="form-control" name="country" value="IT">
                            </div>

                            <div class="form-group">
                            <label>Select Month of Import</label>
                            <select name="mese" class="form-control select2" data-placeholder="Select Mese" style="width: 100%;">
                                <option value="1" selected >{{ __('stFatt.january')}}</option>
                                <option value="2">{{ __('stFatt.february')}}</option>
                                <option value="3">{{ __('stFatt.march')}}</option>
                                <option value="4">{{ __('stFatt.april')}}</option>
                                <option value="5">{{ __('stFatt.may')}}</option>
                                <option value="6">{{ __('stFatt.june')}}</option>
                                <option value="7">{{ __('stFatt.july')}}</option>
                                <option value="8" >{{ __('stFatt.august')}}</option>
                                <option value="9">{{ __('stFatt.september')}}</option>
                                <option value="10">{{ __('stFatt.october')}}</option>
                                <option value="11">{{ __('stFatt.november')}}</option>
                                <option value="12">{{ __('stFatt.december')}}</option>
                            </select>
                        </div>

                            <div>
                                <button type="submit" class="btn btn-primary">{{ trans('_message.submit') }}</button>
                            </div>
                        </form>
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
@endsection