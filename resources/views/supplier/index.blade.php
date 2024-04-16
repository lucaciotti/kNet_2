@extends('layouts.app')

@section('htmlheader_title')
    - {{ trans('supplier.headTitle_idx') }}
@endsection

@section('contentheader_title')
    {{ trans('supplier.contentTitle_idx') }}
@endsection

@section('contentheader_breadcrumb')
  {!! Breadcrumbs::render('suppliers') !!}
@endsection

@section('main-content')
  <div class="row">

    <div class="col-lg-7">
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title" data-widget="collapse">{{ trans('supplier.listCli') }}</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          </div>
        </div>
        <div class="box-body">
          <table class="table table-hover table-condensed dtTbls_light">
            <thead>
              <th>{{ trans('supplier.code') }}</th>
              <th>{{ trans('supplier.descCli') }}</th>
              <th>{{ trans('supplier.nat&loc') }}</th>
              <th>{{ trans('supplier.sector') }}</th>
              <th>{{ trans('supplier.agent') }}</th>
              {{-- <th>{{ trans('supplier.lnkDocuments') }}</th> --}}
            </thead>
            <tbody>
              @foreach ($suppliers as $supplier)
                <tr>
                  <td>
                    <a href="{{ route('supplier::detail', $supplier->codice ) }}"> {{ $supplier->codice }}</a>
                  </td>
                  <td>{{ $supplier->descrizion }}</td>
                  <td>{{ $supplier->codnazione }} - {{ $supplier->localita }}</td>
                  <td>{{ $supplier->settore }}</td>
                  <td>@if($supplier->agent) {{ $supplier->agent->descrizion }} @endif</td>
                  {{-- <td><a href="{{ route('doc::supplier', $supplier->codice) }}">{{ trans('supplier.documents') }}</a></td> --}}
                </tr>
              @endforeach
            </tbody>
          </table>
          {{-- {!! $suppliers->render() !!} --}}
        </div>
      </div>
    </div>

    <div class="col-lg-5">
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title" data-widget="collapse">{{ trans('supplier.filter') }}</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            {{-- <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button> --}}
          </div>
        </div>
        <div class="box-body">
          @include('supplier.partials.formIndex')
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
