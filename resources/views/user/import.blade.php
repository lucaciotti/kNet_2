@extends('layouts.app')

@section('htmlheader_title')
    - {{ trans('user.headTitle_imp') }}
@endsection

@section('contentheader_title')
    {{ trans('user.contentTitle_imp') }}
@endsection

@section('contentheader_breadcrumb')
  {!! Breadcrumbs::render('clients') !!}
@endsection

@section('main-content')
  <div class="row">
      <div class="container">
      <div class="col-lg-12">
        @if(Session::has('success'))
          <div class="callout callout-success">
                <h4>LEAD imported with success!!</h4>
                <p>{!! Session::get('success') !!}</p>
          </div>
        @endif
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title" data-widget="collapse">{{ trans('user.importUser') }}</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          </div>
        </div>
        <div class="box-body">

          <form action="{{ route('user::import') }}" method="POST" enctype="multipart/form-data">
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
