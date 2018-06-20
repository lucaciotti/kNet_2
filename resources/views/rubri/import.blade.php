@extends('adminlte::page')

@section('htmlheader_title')
	Import Rubrica LEAD
@endsection


@section('main-content')
	<div class="container-fluid spark-screen">
		<div class="row">
			<div class="col-md-9 col-md-offset-1">

				<div class="box box-success box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title">Example box</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        </div>
                        <!-- /.box-tools -->
                    </div>
                    <!-- /.box-header -->
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
                    <!-- /.box-body -->
                </div>
			</div>
		</div>
	</div>
@endsection
