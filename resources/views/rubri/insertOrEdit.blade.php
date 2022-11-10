@extends('layouts.app')

@section('htmlheader_title')
- Nuovo Potenziale Cliente (Contatto)
@endsection

@section('contentheader_title')
Nuovo Potenziale Cliente (Contatto)
@endsection

@section('contentheader_breadcrumb')
{!! Breadcrumbs::render('clients') !!}
@endsection

@section('main-content')
<div class="row">
    <div class="container">
        <div class="col-lg-12">

            <form action="{{ route('rubri::store') }}" method="POST" onsubmit="return checkForm()">
                {{ csrf_field() }}

                <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title" data-widget="collapse">Dati Anagrafici</h3> (obbligatori)
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="form-group">
                            <label>{{ trans('client.descCli') }}</label>
                            <input id='ragsoc' type="text" class="form-control" name="ragsoc" value="" placeholder="{{ trans('client.descCli') }}">
                        </div>

                        <div class="form-group">
                            <label>{{ trans('client.vatCode') }}</label>
                            <input id='vatCode' type="text" class="form-control" name="vatCode" value="" placeholder="{{ trans('client.vatCode') }}">
                        </div>

                        <div class="form-group">
                            @if ($settori)
                            <label>{{ trans('client.sector') }}</label>
                            <select id='sector' name="sector" class="form-control select2" style="width: 100%;">
                                @if ($settori instanceof Illuminate\Database\Eloquent\Collection)
                                @if ($settori->count()>1)
                                <option value=""> </option>
                                @endif
                                @foreach ($settori as $set)
                                <option value="{{ $set->descrizion }}">{{ $set->descrizion }}</option>
                                @endforeach
                                @else
                                <option value="{{ $settori->descrizion }}">{{ $settori->descrizion }}</option>
                                @endif
                            </select>
                            @endif
                        </div>

                        <hr>

                        <div class="form-group">
                            @if ($nazioni)
                            <label>{{ trans('client.nation') }}</label>
                            <select id='nation' name="nation" class="form-control select2" style="width: 100%;">
                                @if ($nazioni instanceof Illuminate\Database\Eloquent\Collection)
                                @if ($nazioni->count()>1)
                                <option value=""> </option>
                                @endif
                                @foreach ($nazioni as $naz)
                                <option value="{{ $naz->codice }}">[{{$naz->codice}}] {{ $naz->descrizion }}</option>
                                @endforeach
                                @else
                                <option value="{{ $nazioni->codice }}">[{{$nazioni->codice}}] {{ $nazioni->descrizion }}</option>
                                @endif
                            </select>
                            @endif
                        </div>

                        <div class="form-group">
                            <label>{{ trans('client.location') }}</label>
                            <input id='location' type="text" class="form-control" name="location" value="" placeholder="{{ trans('client.location') }}">
                        </div>

                        <div class="form-group">
                            <label>{{ trans('client.address') }}</label>
                            <input id='address' type="text" class="form-control" name="address" value="" placeholder="{{ trans('client.address') }}">
                        </div>

                        <div class="form-group">
                            <label>{{ trans('client.posteCode') }}</label>
                            <input id='posteCode' type="text" class="form-control" name="posteCode" value="" placeholder="{{ trans('client.posteCode') }}">
                        </div>

                        <hr>
                                                
                        <div class="form-group">
                            <label>{{ trans('client.email') }}</label>
                            <input id='email' type="text" class="form-control" name="email" value="" placeholder="{{ trans('client.email') }}">
                        </div>

                        <hr>

                        <div class="form-group">
                            @if ($agenti)
                            <label>{{ trans('client.referenceAgent') }}</label>
                            <select id='referenceAgent' name="referenceAgent" class="form-control select2" style="width: 100%;">
                                @if ($agenti instanceof Illuminate\Database\Eloquent\Collection)
                                @if ($agenti->count()>1)
                                <option value=""> </option>
                                @endif
                                @foreach ($agenti as $ag)
                                <option value="{{ $ag->codice }}">[{{$ag->codice}}] {{ $ag->descrizion }}</option>
                                @endforeach
                                @else
                                <option value="{{ $agenti->codice }}">[{{$agenti->codice}}] {{ $agenti->descrizion }}</option>
                                @endif
                            </select>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="box box-default collapsed-box">
                    <div class="box-header with-border">
                        <h3 class="box-title" data-widget="collapse">Contatti</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                       <div class="form-group">
                        <label>{{ trans('client.referencePerson') }}</label>
                        <input id='persdacont' type="text" class="form-control" name="persdacont" value=""
                            placeholder="{{ trans('client.referencePerson') }}">
                        </div>
                        
                        <div class="form-group">
                            <label>{{ trans('client.roleReferencePerson') }}</label>
                            <input id='pospersdacont' type="text" class="form-control" name="pospersdacont" value=""
                                placeholder="{{ trans('client.roleReferencePerson') }}">
                        </div>
                        
                        <hr>
                        
                        <div class="form-group">
                            <label>{{ trans('client.phone') }}</label>
                            <input id='phone' type="text" class="form-control" name="phone" value="" placeholder="{{ trans('client.phone') }}">
                        </div>
                        
                        <div class="form-group">
                            <label>{{ trans('client.site') }}</label>
                            <input id='site' type="text" class="form-control" name="site" value="" placeholder="{{ trans('client.site') }}">
                        </div>
                    </div>
                </div>

                <div class="box box-default">
                    <div class="box-body">                        
                        <div>
                            <input type="hidden" name='contact' value="{{ $contact }}">
                            <input type="hidden" name='insertVisit' value="{{ $returnToVisit }}">
                            <button type="submit" class="btn btn-block btn-primary">SALVA</button>
                        </div>
                    </div>
                </div>

            </form>

        </div>
    </div>
</div>
@endsection

@section('extra_script')
@include('layouts.partials.scripts.iCheck')
@include('layouts.partials.scripts.select2')
@include('layouts.partials.scripts.datePicker')

<script>
    function checkForm(){
        if($('#ragsoc').val()=='') {
          alert('Indicare Ragione Sociale');
          $('#ragsoc').focus();
          return false;
        }
        if($('#sector').select2('data')[0].id=='') {
          alert('Selezionare Settore Mercato');
          $('#sector').focus();
          return false;
        }
        if($('#nation').select2('data')[0].id=='') {
          alert('Selezionare Nazione');
          $('#nation').focus();
          return false;
        }
        if($('#location').val()=='') {
          alert('Indicare Località');
          $('#location').focus();
          return false;
        }
        if($('#referenceAgent').select2('data')[0].id=='') {
          alert('Selezionare Agente di Riferimento');
          $('#referenceAgent').focus();
          return false;
        }
        
        return true;
    };
</script>

@endsection