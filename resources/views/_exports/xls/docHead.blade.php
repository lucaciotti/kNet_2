<table >
  <tr>
    <th># Doc.</th>
    <th>Year</th>   
    <th>Doc. Date</th>
    <th>Customer Code</th>
    <th>Rif. Doc.</th>
    <th>Date Rif. Doc.</th>  
    <th>Currency</th>
    <th>Change Value</th>
    <th>Discount</th>
    <th>Cash Discount</th>    
    <th>Doc. Type</th>
    <th>Net Weight</th>
    <th>Gross Weight</th>
    <th>Volume</th>
    <th>Carrier</th>
    <th>Carrier Departure</th>
    <th>Carrier Dep. Time</th>
    <th>Appearance of goods</th>
    <th># Packages</th>
    <th>Goods Destination</th>
    <th>Transport Cost</th>
    <th>Goods Tot.</th>
    <th>Discount Value</th>
    <th>Taxable Tot.</th>
    <th>VAT Tot.</th>
    <th>Total Due</th>
  </tr>

  <tr>
    <td>{{ $head->doc }}</td>
    <td>{{ $head->esercizio }}</td>
    <td>{{ $head->datadoc }}</td>
    <td>{{ $head->codicecf }}</td>
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