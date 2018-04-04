<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

@section('htmlheader')
    @include('layouts.partials.htmlheader')
@show

<body class="skin-green sidebar-mini sidebar-collapse">
    <div id="app" class="wrapper">

        @include('layouts.partials.mainheader')

        @include('layouts.partials.sidebar')

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">

            @include('layouts.partials.contentheader')

            <!-- Main content -->
            <section class="content">
                <!-- Your Page Content Here -->
                @yield('main-content')
            </section><!-- /.content -->
        </div><!-- /.content-wrapper -->

        @include('layouts.partials.controlsidebar')

        @include('layouts.partials.footer')

    </div><!-- ./wrapper -->

    @section('scripts')
        @include('layouts.partials.scripts')
    @show

</body>
</html>
