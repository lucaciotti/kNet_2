<table class="table table-hover table-condensed dtTbls_full" id="statAbcTable">
  <thead>
    {{-- <tr>
      <th colspan="3" style="text-align: center;">Doc. Detail</th>
      <th colspan="1">&nbsp;</th>
      <th colspan="2" style="text-align: center;">Qta a Luglio</th>
      <th colspan="2" style="text-align: center;"></th>
    </tr> --}}
    <tr>
      <th style="text-align: center;">{{ trans('doc.typeDoc') }}</th>
      <th style="text-align: center;">{{ trans('doc.#Doc') }}</th>
      <th style="text-align: center;">{{ trans('doc.dateDoc_condensed') }}</th>
      <th colspan="1">|</th>

      <th style="text-align: center;">Cod. Art.</th>
      <th style="text-align: center;">U.M.</th>
      <th style="text-align: center;">Qty</th>
      <th colspan="1">|</th>
      <th style="text-align: center;">Gross Unit Price</th>
      <th style="text-align: center;">Discount</th>
      <th style="text-align: center;">Net Unit Price</th>
      <th colspan="1">|</th>
      <th style="text-align: center;">Tot. Price</th>

    </tr>
  </thead>
  <tbody>
    @foreach ($listDocs as $doc)
      <tr>
        <td>{{ $doc->doccli->tipodoc }}</td>
        <td><a href="{{ route('doc::detail', $doc->id_testa) }}"> {{ $doc->doccli->numerodoc }} </a></td>
        <td><span>{{$doc->doccli->datadoc->format('Ymd')}}</span>{{ $doc->doccli->datadoc->format('d-m-Y') }}</td>
        <th colspan="1">|</th>

        <td>{{ $doc->codicearti }}</td>
        <td>{{ $doc->unmisura or 'PZ' }}</td>
        <td>{{ $doc->quantita }}</td>
        <th colspan="1">|</th>

        <td>{{ $doc->prezzoun }}</td>
        <td>{{ $doc->sconti }}</td>
        <td>{{ knet\Helpers\Utils::scontaDel($doc->prezzoun, $doc->sconti, 2) }}</td>
        <th colspan="1">|</th>

        <td>{{ $doc->prezzotot }}</td>
      </tr>
    @endforeach
  </tbody>
</table>
