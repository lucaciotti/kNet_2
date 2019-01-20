@extends('layouts.app')

@section('htmlheader_title')
    - Rubrica Contatti
@endsection

@section('contentheader_title')
    Rubrica Contatti
@endsection

@section('contentheader_breadcrumb')
  {!! Breadcrumbs::render('clients') !!}
@endsection

@section('main-content')
  <div class="row">

    <div class="col-lg-7">
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title" data-widget="collapse">Lista Contatti</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          </div>
        </div>
        <div class="box-body">
          <table class="table table-hover table-condensed dtTbls_light">
            <thead>
              <th>{{ trans('client.code') }}</th>
              <th>{{ trans('client.descCli') }}</th>
              <th>{{ trans('client.nat&loc') }}</th>
              <th>{{ trans('client.sector') }}</th>
              <th>{{ trans('client.agent') }}</th>
            </thead>
            <tbody>
              @foreach ($contacts as $client)
                <tr>
                  <td>
                    <a href="{{ route('client::detail', $client->id ) }}"> {{ $client->codice }}</a>
                  </td>
                  <td>{{ $client->descrizion }}</td>
                  <td>{{ $client->codnazione }} - {{ $client->localita }}</td>
                  <td>{{ $client->settore }}</td>
                  <td>@if($client->agent) {{ $client->agent->descrizion }} @endif</td>
                </tr>
              @endforeach
            </tbody>
          </table>
          {{-- {!! $clients->render() !!} --}}
        </div>
      </div>
    </div>

    <div class="col-lg-5">
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title" data-widget="collapse">{{ trans('client.filter') }}</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            {{-- <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button> --}}
          </div>
        </div>
        <div class="box-body">
          {{-- @include('client.partials.formIndex') --}}
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
