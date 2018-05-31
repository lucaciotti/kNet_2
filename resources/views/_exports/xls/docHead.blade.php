<table >
  {{-- <tr>
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
  </tr> --}}

  <tr>
    <td>{{ $head->doc }}</td>
    <td>{{ $head->datadoc }}</td>
    <td>{{ $head->esercizio }}</td>
    <td>{{ $head->codicecf }}</td>
    <td>{{ $head->numrighepr }}</td>
    <td>{{ $head->numerodocf }}</td>
    <td>{{ $head->datadocfor }}</td>
    <td>{{ $head->valuta }}</td>
    <td>{{ $head->cambio }}</td>
    <td>{{ $head->sconti }}</td>
    <td>{{ $head->scontocassa }}</td>
    <td>{{ $head->tipomodulo }}</td>
    <td>{{ $head->pesonetto }}</td>
    <td>{{ $head->pesolordo }}</td>
    <td>{{ $head->volume }}</td>
    <td>
      @if($head->vettore)
        {{ $head->vettore->descrizion }}
      @endif
    </td>
    <td>{{ $head->v1data }}</td>
    <td>{{ $head->v1ora }}</td>
    <td>
      @if($head->detBeni)
        {{ $head->detBeni->descrizion }}
      @endif
    </td>
    <td>{{ $head->colli }}</td>
    <td>
      @if($destDiv)
        {{ $destDiv->ragionesoc }}, 
        {{ $destDiv->localita }}, 
        {{ $destDiv->indirizzo }}
      @endif
    </td>
    <td>{{ $head->spesetras }}</td>
    <td>{{ $head->totmerce }}</td>
    <td>{{ $head->totsconto }}</td>
    <td>{{ $head->totimp }}</td>
    <td>{{ $head->totiva }}</td>
    <td>{{ $head->totdoc }}</td>
  </tr>
  
</table>