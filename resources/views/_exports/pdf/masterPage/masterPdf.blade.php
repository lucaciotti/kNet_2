<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <style>
            /* @page { margin: 4em 1em 4em 1em; width: 100%; }
            body { margin: 50px 25px 70px 25px; }
            header { position: fixed; top: -50px; left: 0px; right: 0px; height: 30px; }
            footer { position: fixed; bottom: -70px; left: 0px; right: 0px; height: 40px; } */
            @page { margin: 0cm 0cm; size: 21cm 29.7cm }
            /** Define now the real margins of every page in the PDF **/
            body { margin-top: 2.5cm; margin-left: 2cm; margin-right: 2cm; margin-bottom: 2cm; }
            /** Define the header rules **/
            header { 
                position: fixed; top: 0cm; left: 0cm; right: 0cm; height: 2cm;
                /* text-align: margin-left: 2cm; */
                line-height: 1.5cm;
            }
            /** Define the footer rules **/
            footer {
                position: fixed; bottom: 0cm; left: 0cm; right: 0cm; height: 2cm;
                /* text-align: margin-left: 2cm; */
                line-height: 1.5cm;
            }
            p { page-break-after: always; }
            p:last-child { page-break-after: never; }
        </style>
    </head>
    <body>
        
        @section('pdf-header')
            @include('_exports.pdf.masterPage.headerPdf')
        @show

        @section('pdf-footer')
            @include('_exports.pdf.masterPage.footerPdf')
        @show

        @yield('pdf-main')        

    </body>
</html>