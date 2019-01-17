<table class="table table-hover table-condensed dtTbls_full" id="statAbcTable">
  <thead>
    <tr>
      <th colspan="4">&nbsp;</th>
      <th colspan="2" style="text-align: center;">Qta a {{__('_monthList.month_'.$thisMonth)}}</th>
      <th colspan="2" style="text-align: center;"></th>
    </tr>
    <tr>
      <th style="text-align: center;">Cliente</th>
      <th style="text-align: center;">Rag. Sociale</th>
      <th style="text-align: center;">Link Documenti</th>
      <th style="text-align: center;">U.M.</th>
      <th colspan="1">|</th>

      <th style="text-align: center;">{{ $thisYear }}</th>
      <th style="text-align: center;">{{ $prevYear }}</th>
      <th style="text-align: center;">Delta Qta</th>

      <th colspan="1">|</th>
      <th style="text-align: center;">Qta Fine {{ $prevYear }} </th>
    </tr>
  </thead>
  <tbody>
    @foreach ($AbcProds as $abc)
      <tr>
        <td><a href="{{ route('client::detail', $abc->codicecf ) }}"> {{ $abc->codicecf }} </a></td>
        <td>{{ $abc->client->descrizion or '' }}</td>
        <td><a href="{{ route('stAbc::docsArtCli', ['codArt' => $abc->articolo, 'codcli' => $abc->codicecf] ) }}"> Link Docs </a></td>
        <td>{{ $abc->unmisura or 'PZ' }}</td>
        <th colspan="1">|</th>
        @php
          $qta_TY = 0;
          $qta_PY = 0;
          for($i=1; $i<=$thisMonth; $i++){
            $campo_TY = 'qta_TY_'.$i;
            $campo_PY = 'qta_PY_'.$i;
            $qta_TY += $abc->$campo_TY;
            $qta_PY += $abc->$campo_PY;
          }
          $delta_qta = $qta_TY - $qta_PY;
        @endphp
        <td>{{ $qta_TY }}</td>
        <td>{{ $qta_PY }}</td>
        <td>{{ $delta_qta }}</td>
        <th colspan="1">|</th>
        <td>{{ $abc->qta_PY }}</td>
      </tr>
    @endforeach
  </tbody>
</table>
