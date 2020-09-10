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
        @if (in_array(RedisUser::get('role'), ['agent', 'superAgent']))
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
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                            class="fa fa-minus"></i></button>
                </div>
            </div>
            <div class="box-body">
                
            </div>
        </div>

        @if($ritana)
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title" data-widget="collapse">Dati Enasarco</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                            class="fa fa-minus"></i></button>
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
                    <dd><a type="button" class="btn btn-default btn-xs" target="_blank"
                            href="{{ route('user::enasarcoXLS', [$user->id]) }}">Download</a></dd>
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
                            <td><span>{{$mov->ftdatadoc->format('Ymd')}}</span>{{ $mov->ftdatadoc->format('d-m-Y') }}
                            </td>
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