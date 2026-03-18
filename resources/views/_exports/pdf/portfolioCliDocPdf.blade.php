@extends('_exports.pdf.masterPage.masterPdf')

@section('pdf-main')
    <p class="page">

        <div class="row">
            <div class="contentTitle">
                CliDoc Clienti
            </div>
            <div style="padding-left: 50px; padding-right: 50px; font-size: 75%">
                Filtri: <br>
                <span style="padding-left: 10px;">
                    Orders -> { {{ $ordFilter }} }<br>
                </span>
                <span style="padding-left: 10px;">
                    Ddt -> { {{ $ddtFilter }} }<br>
                </span>
                <span style="padding-left: 10px;">
                Invoice -> { {{ $fatfFilter }} }<br><br>
                </span>
            </div>

            @foreach ($portfolio as $key => $group)
                <h2><b>{{ $group['client']->descrizion }}</b> [<a href="{{ route('client::detail', $key ) }}" target="_blank">{{ $key}}</a>] <br></h2>
                @php
                    $docsArray = $group['docs']->toArray();
                    $docs = $group['docs'];
                @endphp
                @if(array_key_exists("O",$docsArray))
                    @foreach ($docs['O'] as $docId => $doc)                
                        @php
                        $head = $doc['head'];
                        $rows = $doc['rows'];
                        @endphp
                        @include('_exports.pdf.cliDoc.tblRows', [$head, $doc] )
                    @endforeach
                @endif
                @if(array_key_exists("B",$docsArray))
                    @foreach ($docs['B'] as $docId => $doc)                
                        @php
                        $head = $doc['head'];
                        $rows = $doc['rows'];
                        @endphp
                        @include('_exports.pdf.cliDoc.tblRows', [$head, $doc] )
                    @endforeach
                @endif
                @if(array_key_exists("F",$docsArray))
                    @foreach ($docs['F'] as $docId => $doc)                
                        @php
                        $head = $doc['head'];
                        $rows = $doc['rows'];
                        @endphp
                        @include('_exports.pdf.cliDoc.tblRows', [$head, $doc] )
                    @endforeach
                @endif
                @if(array_key_exists("N",$docsArray))
                    @foreach ($docs['N'] as $docId => $doc)                
                        @php
                        $head = $doc['head'];
                        $rows = $doc['rows'];
                        @endphp
                        @include('_exports.pdf.cliDoc.tblRows', [$head, $doc] )
                    @endforeach
                @endif
                
                {{-- @if (array_key_exists("B",$docs))
                    Dettaglio DDT:<br>
                    @foreach ($docs['B'] as $docId => $doc)
                        Documento {{ $doc['head']['numerodoc'] }}<br><table class="table table-hover table-striped dtTbls_portfolio" id="portfolioTbl" style="text-align: center;">
                            <thead>
                                <tr>
                                    <th>Codice Articolo</th>
                                    <th>Descrizione</th>
                                    <th>UM</th>
                                    <th>Quantità</th>
                                    <th>Qta Residua</th>
                                    <th>Tot Prezzo</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($doc['rows'] as $row)
                                <tr>
                                    <td>{{ $row['codicearti'] }}</td>
                                    <td>{{ $row['descrizion'] }}</td>
                                    <td>{{ $row['unmisura'] }}</td>
                                    <td>{{ $row['quantita'] }}</td>
                                    <td>{{ $row['quantitare'] }}</td>
                                    <td>{{ $row['totNetGrossPrice'] }}</td>
                                </tr>                            
                                @endforeach   
                                </tbody>
                            </table>                 
                    @endforeach            
                @endif --}}

                {{-- @if (array_key_exists("F",$docs) || (array_key_exists("N",$docs))
                    Dettaglio Fatture:<br>
                    @foreach ($docs['F'] as $docId => $doc)
                        Documento {{ $doc['head']['numerodoc'] }}<br><table class="table table-hover table-striped dtTbls_portfolio" id="portfolioTbl" style="text-align: center;">
                            <thead>
                                <tr>
                                    <th>Codice Articolo</th>
                                    <th>Descrizione</th>
                                    <th>UM</th>
                                    <th>Quantità</th>
                                    <th>Qta Residua</th>
                                    <th>Tot Prezzo</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($doc['rows'] as $row)
                                <tr>
                                    <td>{{ $row['codicearti'] }}</td>
                                    <td>{{ $row['descrizion'] }}</td>
                                    <td>{{ $row['unmisura'] }}</td>
                                    <td>{{ $row['quantita'] }}</td>
                                    <td>{{ $row['quantitare'] }}</td>
                                    <td>{{ $row['totNetGrossPrice'] }}</td>
                                </tr>                            
                                @endforeach   
                                </tbody>
                            </table>                 
                    @endforeach                    
                    @foreach ($docs['N'] as $docId => $doc)
                        Documento {{ $doc['head']['numerodoc'] }}<br><table class="table table-hover table-striped dtTbls_portfolio" id="portfolioTbl" style="text-align: center;">
                            <thead>
                                <tr>
                                    <th>Codice Articolo</th>
                                    <th>Descrizione</th>
                                    <th>UM</th>
                                    <th>Quantità</th>
                                    <th>Qta Residua</th>
                                    <th>Tot Prezzo</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($doc['rows'] as $row)
                                <tr>
                                    <td>{{ $row['codicearti'] }}</td>
                                    <td>{{ $row['descrizion'] }}</td>
                                    <td>{{ $row['unmisura'] }}</td>
                                    <td>{{ $row['quantita'] }}</td>
                                    <td>{{ $row['quantitare'] }}</td>
                                    <td>{{ $row['totNetGrossPrice'] }}</td>
                                </tr>                            
                                @endforeach   
                                </tbody>
                            </table>                 
                    @endforeach
                @endif --}}
            @endforeach
        
        </div>

    </p>

@endsection