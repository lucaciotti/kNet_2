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
              <th>{{ trans('client.descCli') }}</th>
              <th>{{ trans('client.nat&loc') }}</th>
              <th>Pross.Visita</th>
              <th>{{ trans('client.agent') }}</th>
              <th>&nbsp;</th>
              <th>&nbsp;</th>
            </thead>
            <tbody>
              @foreach ($contacts as $contact)
                @if ($contact->date_nextvisit)
                  @php
                      $dToNextVisit=$contact->date_nextvisit->diffInDays(Carbon\Carbon::now());
                      if($contact->date_nextvisit<Carbon\Carbon::now()){
                        $dToNextVisit = $dToNextVisit*-1;
                      }
                  @endphp
                    @if ($dToNextVisit<10 && $dToNextVisit>0)
                        <tr class="warning">
                    @else
                      @if ($dToNextVisit<0)
                          <tr class="danger">
                      @else
                          <tr>                                              
                      @endif                        
                    @endif
                @else
                  <tr>                    
                @endif
                  <td>
                    <a href="{{ route('rubri::detail', ['rubri_id' => $contact->id] ) }}"> {{ $contact->descrizion }}</a>
                  </td>
                  <td>{{ $contact->codnazione }} - {{ $contact->regione }}, {{ ucfirst(strtolower($contact->localita)) }}</td>
                  <td>
                    @if($contact->date_nextvisit)
                      <span>{{$contact->date_nextvisit->format('Ymd')}}</span>{{ $contact->date_nextvisit->format('d-m-Y') }}
                    @endif
                  </td>
                  <td>@if($contact->agent) {{ $contact->agent->descrizion }} @endif</td>
                  <td>
                      @if($contact->codicecf) <a class="btn-sm btn-primary" href="{{ route('client::detail', $contact->codicecf ) }}"><i class="fa fa-users" target="_blank"></i></a> @endif
                  </td>
                  <td>
                      @if($contact->isModCarp01) 
                        <a class="btn-sm btn-success" href="{{ route('ModCarp01::edit', ['rubri_id' => $contact->id] ) }}" target="_blank"><i class="fa fa-file-text-o"></i></a>
                      @else
                        <a class="btn-sm btn-warning" href="{{ route('ModCarp01::create', ['rubri_id' => $contact->id] ) }}" target="_blank"><i class="fa fa-file-text-o"></i></a>
                      @endif
                  </td>
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
          @include('rubri.partials.formIndex')
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
