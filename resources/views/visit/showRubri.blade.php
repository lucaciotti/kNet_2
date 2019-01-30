@extends('layouts.app')

@section('htmlheader_title')
    - {{ trans('visit.headTitle_ins') }}
@endsection

@section('contentheader_title')
    {{$client->descrizion or ''}}
@endsection

@section('main-content')
  <div class="row">
    <div class="container">
      <div class="col-lg-12">
        @include('visit.partials.timeline', [
          'visits' => $visits,
          'codcli' => '',
          'rubri_id' => $client->id,
          'dateNow' => $dateNow,
          ])
      </div>
    </div>
  </div>
@endsection

@section('extra_script')
  @include('layouts.partials.scripts.iCheck')
  @include('layouts.partials.scripts.select2')
  @include('layouts.partials.scripts.datePicker')
@endsection
