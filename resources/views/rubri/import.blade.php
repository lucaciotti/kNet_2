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