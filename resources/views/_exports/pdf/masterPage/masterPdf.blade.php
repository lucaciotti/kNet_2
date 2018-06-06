<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <style>
            /* @page { font-size: pt } */
            p { page-break-after: always; }
            p:last-child { page-break-after: avoid; }
            div.row { font-size: 9pt; }
            span.floatleft { float: left; width: 49%; } /* border-left:1px solid grey; */
            span.floatright { float: right; width: 49%; }
            hr.divider { width: 80%; float:left; margin-right: 20%;}
            hr.dividerPage { width: 80%; float:middle; margin-right: 10%;}
            dt { font-size: 8pt; font-style: italic; }
            table { width: 100%; font-size: 9pt; }
            table tr { page-break-inside: avoid; }
            table .fontsmall { font-size:  8pt; }
            table .centered { text-align: center; }
            table tr:nth-child(even) { background-color: #f2f2f2; }
            table tr.danger { background-color: red; }
            table tr.warning { background-color: orange; }
            table thead { display: table-header-group; }
            table tfoot { background-color: darkgrey; display: table-header-group; }
            div.contentTitle { 
                font-size: 11pt; 
                font-stretch: expanded; 
                font-style: oblique; 
                margin-left: 20px; 
                font-weight: bold; 
                text-decoration: underline; 
                padding-top: 20px;
                padding-bottom: 20px;
            }
        </style>
    </head>
    <body>
        
       {{--  @section('pdf-header')
            @include('_exports.pdf.masterPage.headerPdf')
        @show

        @section('pdf-footer')
            @include('_exports.pdf.masterPage.footerPdf')
        @show --}}

        @yield('pdf-main')        

        @stack('scripts')
    </body>
</html>