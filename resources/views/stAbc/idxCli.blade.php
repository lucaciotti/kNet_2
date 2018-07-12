@extends('layouts.app')

@section('htmlheader_title')
    - {{ trans('stFatt.headTitle_cli') }}
@endsection

@section('contentheader_title')
    {{ trans('stFatt.contentTitle_cli') }}
@endsection

@section('contentheader_breadcrumb')
    {{-- {!! Breadcrumbs::render('clientStFat', $customer) !!} --}}
@endsection

@push('css-head')
  <!-- Morris charts -->
  <link rel="stylesheet" href="{{ asset('/plugins/morris/morris.css') }}">
@endpush

@section('main-content')
<div class="row">
  <div class="col-lg-3">
    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title" data-widget="collapse">{{ trans('stFatt.client') }}</h3>
        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          {{-- <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button> --}}
        </div>
      </div>
      <div class="box-body">
        <form action="{{ route('stAbc::idxCli') }}" method="post">
          <input type="hidden" name="_token" value="{{ csrf_token() }}">
          <div class="form-group">
            <label>{{ trans('stFatt.selClient') }}</label>
            <select name="codcli" class="form-control select2" style="width: 100%;">
              <option value=""> </option>
              @foreach ($customers as $client)
                <option value="{{ $client->codicecf }}"
                  @if($client->codicecf==$customer)
                      selected
                  @endif
                  >{{ $client->client->descrizion or 'cDeleted' }}</option>
              @endforeach
            </select>
          </div>
          <div>
            <button type="submit" class="btn btn-primary">{{ trans('_message.submit') }}</button>
          </div>
        </form>
      </div>
    </div>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title" data-widget="collapse">{{ trans('doc.filter') }}</h3>
        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          {{-- <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button> --}}
        </div>
      </div>
      <div class="box-body">
        @include('stAbc.partials.formIndex', ['gruppi' => $gruppi, 'agente' => null, 'route' => 'stAbc::idxCli', 'customer' => $customer])
      </div>
    </div>
  </div>

  <div class="col-lg-9">
    <div class="nav-tabs-custom">
      <ul class="nav nav-tabs pull-right">
        <li class="active"><a href="#statAbc" data-toggle="tab" aria-expanded="true">{{ strtoupper(trans('stFatt.total')) }}</a></li>
        <li class="pull-left header"><i class="fa fa-th"></i> {{ trans('stFatt.statsTitle') }}</li>
      </ul>
      <div class="tab-content">
        <div class="tab-pane active" id="statAbc">
        @include('stAbc.partials.tblIdxCli', [
          'customer' => $customer,
          'AbcProds' => $AbcProds,
          'thisYear' => $thisYear,
          'prevYear' => $prevYear,
          'thisMonth' => $thisMonth,
        ])
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

