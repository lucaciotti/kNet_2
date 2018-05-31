<table >
  <tr>
    <th># Line</th>
    <th>Item Code</th>
    <th>Item Description</th>   
    <th>U.M.</th>
    <th>Fatt.</th>
    <th>Quantity</th>
    <th>Qty Residual</th>  
    <th>Unit Price</th>
    <th>Discount</th>
    <th>Tot.Price</th>
    <th>VAT</th>    
    <th>FreeOfCharge?</th>
    <th>Lot Code</th>
    <th>Mat Code</th>
    <th>Dispach Date</th>
  </tr>
  @foreach ($rows as $row)
    <tr>
      <td>{{ $row->numeroriga }}</td>
      <td>{{ $row->codicearti }}</td>
      <td>{{ $row->descrizion }}</td>
      <td>{{ $row->unmisura }}</td>
      <td>{{ $row->fatt }}</td>
      <td>{{ $row->quantita }}</td>
      <td>{{ $row->quantitare }}</td>
      <td>{{ $row->prezzoun }}</td>
      <td>{{ $row->sconti }}</td>
      <td>{{ $row->prezzotot }}</td>
      <td>{{ $row->aliiva }}</td>
      <td>{{ $row->ommerce }}</td>
      <td>{{ $row->lotto }}</td>
      <td>{{ $row->matricola }}</td>
      <td> @if($row->u_dtpronto)
            {{ $row->u_dtpronto->format('d-m-Y') }}
          @else
            @if($row->dataconseg)
              {{ $row->dataconseg->format('d-m-Y') }}
            @endif
          @endif
      </td>
    </tr>
  @endforeach
  
</table>