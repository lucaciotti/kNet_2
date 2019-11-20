@extends('layouts.app')

@section('htmlheader_title')
    - {{ trans('user.headTitle_pfl') }}
@endsection

@section('contentheader_title')
@endsection

@section('contentheader_breadcrumb')
  {!! Breadcrumbs::render('clients') !!}
@endsection

@section('main-content')
  <div class="row">
    <div class="col-lg-10 col-lg-offset-1">
      <img src="{{asset('/img/avatar_default.jpg')}}" style="width:120px; height:120px; float:left; border-radius:50%; margin-right:25px;"/>
      <h2>{{ trans('user.userProfile', ['user' => $user->name]) }}</h2>
      @if (!in_array(RedisUser::get('role'), ['client', 'agent', 'superAgent', 'user']))
        <a href="{{ route('user::users.edit', $user->id ) }}">
          <button type="submit" id="edit-user-{{ $user->id }}" class="btn btn-sm">
              <i class="fa fa-btn fa-pencil"></i>&nbsp;&nbsp; {{ trans('user.modify') }}
          </button>
        </a>
      @endif
    </div>
  </div>
  <hr>
  <div class="row">
    <div class="col-lg-8 col-lg-offset-2">
        <div class="box box-default">
          <div class="box-header with-border">
            <h3 class="box-title" data-widget="collapse">{{ trans('user.userSettings') }}</h3>
            <div class="box-tools pull-right">
              <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div>
          </div>
          <div class="box-body">
            <dl class="dl-horizontal">
              <dt>{{ trans('user.name') }}</dt>
              <dd>{{$user->name}}</dd>

              <dt>{{ trans('user.eMail') }}</dt>
              <dd>{{ $user->email }}</dd>

              <dt>{{ trans('user.role') }}</dt>
              <dd>{{$user->roles()->first()->display_name}}</dd>

              @if (!empty($user->codag))
                <dt>{{ trans('user.codAg') }}</dt>
                <dd>{{$user->codag}} - {{$user->agent->descrizion}}</dd>
              @endif

              @if (!empty($user->codcli))
                <dt>{{ trans('user.codCli') }}</dt>
                <dd>{{$user->codcli}} - {{$user->client->descrizion or 'NONE'}}</dd>
              @endif

              <dt>{{ trans('user.refDitta') }}</dt>
              <dd>{{ $user->ditta }}</dd>

              <hr>

              <dt>{{ trans('user.changeLang') }}</dt>
              <dd>
                <form action="{{ route('user::changeLang') }}" method="post" class="form" style="max-width:30%;">
                  <input type="hidden" name="_token" value="{{ csrf_token() }}">
                  <div class="input-group">
                    <select class="form-control" name="lang">
                      <option value="" @if ($user->lang=='') selected="selected" @endif>Auto</option>
                      <option value="it" @if ($user->lang==='it') selected="selected" @endif>{{ trans('user.langIT')}}</option>
                      <option value="es" @if ($user->lang=='es') selected="selected" @endif>{{ trans('user.langES')}}</option>
                      <option value="fr" @if ($user->lang=='fr') selected="selected" @endif>{{ trans('user.langFR')}}</option>
                      <option value="en" @if ($user->lang=='en') selected="selected" @endif>{{ trans('user.langEN')}}</option>
                      <option value="de" @if ($user->lang=='de') selected="selected" @endif>{{ trans('user.langDE') }}</option>
                    </select>
                    <span class="input-group-btn">
                      <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-angle-right"></i></button>
                    </span>
                  </div>
                </form>
              </dd>
              <hr>

              <dt>Reset Password</dt>
                <dd>
                  <a href="{{ url('/password/reset') }}">Reset My Password!</a><br>
                </dd>
            </dl>

          </div>
      </div>

      @if($ritana)
        <div class="box box-default">
            <div class="box-header with-border">
              <h3 class="box-title" data-widget="collapse">Dati Enasarco</h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
              </div>
            </div>
            <div class="box-body">
              @php                
                $tipoAgente = ($ritana->tipoage == 1 ? "Monomandatario" : "Plurimandatario");
                $sum_totfattura = 0;
                $sum_compensi = 0;
                $sum_impendit = 0;
                $sum_prog = 0;
                $sum_res = 0;
                $sum_impenage = 0;
                $sum_prog = 0;
                $sum_res = $ritana->impmax;
              @endphp
              <dl class="dl-horizontal">
                <dt>Tipo Agente</dt>
                <dd>{{ $tipoAgente }}</dd>

                <dt>Minimo Imponibile</dt>
                <dd>{{ currency($ritana->impmin) }}</dd>

                <dt>Massimo Imponibile</dt>
                <dd>{{ currency($ritana->impmax) }}</dd>

                <dt>% a Carico Ditta</dt>
                <dd>{{ $ritana->perendit }} %</dd>

                <dt>% a Carico Agente</dt>
                <dd>{{ $ritana->perenage }} %</dd>

                <dt>Enasarco XLS</dt>
                <dd><a type="button" class="btn btn-default btn-xs" target="_blank" href="{{ route('user::enasarcoXLS', [$user->id]) }}">Download</a></dd>
              </dl>

              <hr>

              <table class="table table-hover table-condensed dtTbls_light">
                <thead>
                  <tr>
                    <th>Data doc.</th>
                    <th>Numero</th>
                    <th>Tot. Fattura</th>
                    <th>Imponibile</th>
                    <th>Progr. Imp.</th>
                    <th>% Ditta</th>
                    <th>Importo Ditta</th>
                    <th>% Agente</th>
                    <th>Importo Agente</th>
                    <th>Progressivo</th>
                    {{-- <th>Residuo</th> --}}
                  </tr>
                </thead>
                <tbody>
                  @foreach ($ritmov as $mov)
                    @php
                      $sum_totfattura += $mov->totfattura;
                      $sum_compensi += $mov->compensi;
                      $sum_impendit += $mov->impendit;
                      $sum_prog += $mov->impendit;
                      $sum_res -= $mov->impendit;
                      $sum_impenage += (float) $mov->impenage;
                      $sum_prog += $mov->impenage;
                      $sum_res -= $mov->impenage;
                    @endphp
                    <tr>
                      <td><span>{{$mov->ftdatadoc->format('Ymd')}}</span>{{ $mov->ftdatadoc->format('d-m-Y') }}</td>
                      <td>{{ $mov->ftnumdoc }} </td>
                      <td>{{ currency($mov->totfattura) }}</td>
                      <td>{{ currency($mov->compensi) }}</td>
                      @if($sum_compensi > $ritana->impmax)
                        <td class="danger">{{ currency($sum_compensi) }}</td>
                      @else
                        <td>{{ currency($sum_compensi) }}</td>
                      @endif
                      <td>{{ $mov->perendit }}</td>
                      <td>{{ currency($mov->impendit) }}</td>
                      <td>{{ $mov->perenage }}</td>
                      <td>{{ currency($mov->impenage) }}</td>
                      <td>{{ currency($sum_prog) }}</td>
                      {{-- <td>{{ currency($sum_res) }}</td> --}}
                    </tr>
                  @endforeach
                </tbody>
                <tfoot class="bg-gray">
                  <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>{{ currency($sum_totfattura) }}</td>
                    <td>{{ currency($sum_compensi) }}</td>
                    <td>{{ currency($sum_compensi) }}</td>
                    <td>&nbsp;</td>
                    <td>{{ currency($sum_impendit) }}</td>
                    <td>&nbsp;</td>
                    <td>{{ currency($sum_impenage) }}</td>
                    <td>{{ currency($sum_prog) }}</td>
                    {{-- <td>{{ currency($sum_res) }}</td> --}}
                  </tr>
                </tfoot>
              </table>
            </div>
        </div>
      @endif

    </div>
  </div>
@endsection

@section('extra_script')
  @include('layouts.partials.scripts.iCheck')
  @include('layouts.partials.scripts.select2')
  @include('layouts.partials.scripts.datePicker')
@endsection
