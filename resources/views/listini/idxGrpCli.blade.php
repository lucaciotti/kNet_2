@extends('layouts.app')

@section('htmlheader_title')
    - Listini Cliente
@endsection

@section('contentheader_title')
    Listini Personalizzati 
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
        <h3 class="box-title" data-widget="collapse">Gruppo Cliente</h3>
        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          {{-- <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button> --}}
        </div>
      </div>
      <div class="box-body">
        <form action="{{ route('listini::grpCli') }}" method="post">
          <input type="hidden" name="_token" value="{{ csrf_token() }}">
          <div class="form-group">
            <label>Selezione Gruppo Cliente</label>
            <select name="grpCli" class="form-control select2" style="width: 100%;">
              <option value=""> </option>
              @foreach ($cliGrps as $grp)
                <option value="{{ $grp->gruppocli }}"
                  @if($grp->gruppocli==$customerGrp)
                      selected
                  @endif
                  >{{ $grp->gruppocli }} - {{ $grp->grpCli->descrizion or 'cDeleted' }}</option>
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
        </div>
      </div>
      <div class="box-body">
        <a type="button" class="btn btn-default btn-block" href="" data-toggle="modal" data-target=".bs-modal">
            Visualizza Lista Clienti Associati
        </a>
        @include('listini.partials.mdlFormCustomers', [ 'grpCliDet' => $grpCliDet, ])
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
        @include('listini.partials.formIndex', ['gruppi' => $gruppi, 'agente' => null, 'route' => 'listini::grpCli', 'customer' => $customerGrp])
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
          @php
            if($grpCliDet){
              $customerDesc=$grpCliDet->descrizion;
            } else {
              $customerDesc='';
            }
          @endphp
            @include('listini.partials.tblProd', [
            'ListProds' => $ListProds,
            'customer' => $customerGrp,
            'customerDesc' => $customerDesc,
            'endOfYear' => $endOfYear,
            'noCli'=> true
            ])
        </div>
        <div class="tab-pane" id="listGrpProd">
            @include('listini.partials.tblGrpProd', [
            'ListGrpProds' => $ListGrpProds,
            'customer' => $customerGrp,
            'customerDesc' => $customerDesc,
            'endOfYear' => $endOfYear,
            'noCli'=> true
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
