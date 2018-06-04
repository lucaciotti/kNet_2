<footer>
    <hr>
    {{ Auth::user()->name }} - {{ \Carbon\Carbon::now()->format('d-m-Y') }}
    <script type="text/php">
        if ( isset($pdf) ) {
            $font = $fontMetrics->get_font("serif", "regular");
            $pdf->page_text(550, 800, "{PAGE_NUM} / {PAGE_COUNT}", $font, 9, array(0,0,0));
        }
    </script> 
</footer>