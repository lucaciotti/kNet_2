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

    </body>
</html>