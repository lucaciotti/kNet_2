@extends('layouts.app')

@section('htmlheader_title')
    - Listini Cliente
@endsection

@section('contentheader_title')
    Listini Personalizzati {{ $customerDet->descrizion }}
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
        <form action="{{ route('listini::idxCli') }}" method="post">
          <input type="hidden" name="_token" value="{{ csrf_token() }}">
          <div class="form-group">
            <label>{{ trans('stFatt.selClient') }}</label>
            <select name="codcli" class="form-control select2" style="width: 100%;">
              <option value=""> </option>
              @foreach ($customers as $client)
                <option value="{{ $client->codclifor }}"
                  @if($client->codclifor==$customer)
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
        <h3 class="box-title" data-widget="collapse">Link</h3>
        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          {{-- <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button> --}}
        </div>
      </div>
      <div class="box-body">
        @if($customerDet->gruppolist)
          <a type="button" class="btn btn-default btn-block" href="{{ route('listini::grpCli', $customerDet->gruppolist) }}" target="_blank">
              Gruppo Listino Cliente -> {{$customerDet->gruppolist}} - {{$customerDet->grpCli->descrizion}}
          </a>
        @endif
        <a type="button" class="btn btn-default btn-block" href="{{ route('client::detail', $customer) }}" target="_blank">
            Anagrafica Cliente
        </a>
        <a type="button" class="btn btn-default btn-block" href="{{ route('stAbc::idxCli', ['codcli' => $customer]) }}" target="_blank">
            Abc Articolo Cliente
        </a>
      </div>
    </div>

    {{-- <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title" data-widget="collapse">{{ trans('doc.filter') }}</h3>
        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          
        </div>
      </div>
      <div class="box-body">
        @include('listini.partials.formIndex', ['gruppi' => $gruppi, 'agente' => null, 'route' => 'listini::idxCli', 'customer' => $customer])
      </div>
    </div>--}}
  </div>

  <div class="col-lg-9">
    <div class="nav-tabs-custom">
      <ul class="nav nav-tabs pull-left">
        <li class="active"><a href="#listProd" data-toggle="tab" aria-expanded="true">Articolo&nbsp;<span class="badge bg-green">{{$ListProds->count()}}</span></a></li>
        <li><a href="#listGrpProd" data-toggle="tab" aria-expanded="true">Gruppo Prodotto&nbsp;<span class="badge bg-yellow">{{$ListGrpProds->count()}}</span></a></li>
        {{-- <li class="pull-left header"><i class="fa fa-th"></i> Listini Personalizzati </li> --}}
      </ul>
      <div class="tab-content">
        <div class="tab-pane active" id="listProd">
            @include('listini.partials.tblProd', [
            'ListProds' => $ListProds,
            'customer' => $customer,
            'customerDesc' => $customerDet->descrizion, 
            'endOfYear' => $endOfYear,
            'noCli'=> false
            ])
        </div>
        <div class="tab-pane" id="listGrpProd">
            @include('listini.partials.tblGrpProd', [
            'ListGrpProds' => $ListGrpProds,
            'customer' => $customer,
            'customerDesc' => $customerDet->descrizion, 
            'endOfYear' => $endOfYear,
            'noCli'=> false
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
   <script>
    $(document).ready(function(){
      $('[data-toggle="popover"]').popover({
        html: true,
        trigger: 'focus'
        });
    });
  </script>
@endsection

