<!DOCTYPE html>
<!--
Landing page based on Pratt: http://blacktie.co/demo/pratt/
-->
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="kNet 2.0 ">
    <meta name="author" content="Luca Ciotti">

    <meta property="og:title" content="kNet 2.0 " />
    <meta property="og:type" content="website" />
    <meta property="og:description" content="kNet 2.0 " />
    <meta property="og:url" content="http://intranet.krona.it/" />
    {{-- <meta property="og:image" content="http://demo.adminlte.acacha.org/img/AcachaAdminLTE.png" />
    <meta property="og:image" content="http://demo.adminlte.acacha.org/img/AcachaAdminLTE600x600.png" />
    <meta property="og:image" content="http://demo.adminlte.acacha.org/img/AcachaAdminLTE600x314.png" /> --}}
    <meta property="og:sitename" content="intranet.krona.it/" />
    <meta property="og:url" content="http://intranet.krona.it/" />

    {{-- <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:site" content="@acachawiki" />
    <meta name="twitter:creator" content="@acacha1" /> --}}

    <title>kNet 2.0</title>

    <!-- Bootstrap core CSS -->
    <link href="{{ asset('/css/app-landing.css') }}" rel="stylesheet">

    <!-- Custom styles for this template -->
    {{-- <link href="{{ asset('/css/main.css') }}" rel="stylesheet"> --}}

    <link href='http://fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Raleway:400,300,700' rel='stylesheet' type='text/css'>

    {{-- <script src="{{ asset('/plugins/jQuery/jQuery-2.1.4.min.js') }}"></script> --}}
    <script src="{{ asset('/js/app-landing.js') }}"></script>

</head>

<body data-spy="scroll" data-offset="0" data-target="#navigation">

<!-- Fixed navbar -->
<div id="navigation" class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#"><b>kNet 2.0</b></a>
        </div>
        <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li class="active"><a href="#home" class="smoothScroll">{{ trans('_message.home') }}</a></li>
                {{-- <li><a href="#desc" class="smoothScroll">{{ trans('adminlte_lang::message.description') }}</a></li>
                <li><a href="#showcase" class="smoothScroll">{{ trans('adminlte_lang::message.showcase') }}</a></li> --}}
                <li><a href="#contact" class="smoothScroll">{{ trans('_message.contact') }}</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                @if (Auth::guest())
                    <li><a href="{{ url('/login') }}">{{ trans('_message.login') }}</a></li>
                    {{-- <li><a href="{{ url('/register') }}">{{ trans('_message.register') }}</a></li> --}}
                @else
                    <li><a href="/home">{{ Auth::user()->name }}</a></li>
                @endif
            </ul>
        </div><!--/.nav-collapse -->
    </div>
</div>


<section id="home" name="home">
<div id="headerwrap">
    <div class="container">
        <div class="row centered">
            <div class="col-lg-12">
                <h1>kNet <b><a href="{{ url('/login') }}">2.0</a></b></h1>
                <h3>{{ trans('landing.knetMission') }}</h3>
            </div>
        </div>
    </div> <!--/ .container -->
</div><!--/ #headerwrap -->

<!-- INTRO WRAP -->
<div id="intro">
    <div class="container">
        <div class="row centered">
            {{-- <h1>{{ trans('landing.services') }}</h1> --}}
            <br>
            <br>
            <div class="col-lg-4">
                <img src="{{ asset('/img/PC_1.png') }}" alt="" height="380">
                {{-- <h3>{{ trans('_message.community') }}</h3> --}}
                {{-- <p>{{ trans('adminlte_lang::message.see') }} <a href="https://github.com/acacha/adminlte-laravel">{{ trans('adminlte_lang::message.githubproject') }}</a>, {{ trans('adminlte_lang::message.post') }} <a href="https://github.com/acacha/adminlte-laravel/issues">{{ trans('adminlte_lang::message.issues') }}</a> {{ trans('adminlte_lang::message.and') }} <a href="https://github.com/acacha/adminlte-laravel/pulls">{{ trans('adminlte_lang::message.pullrequests') }}</a></p> --}}
            </div>
            <div class="col-lg-4">
                <img src="{{ asset('/img/Smart_2.png') }}" alt="" height="330">
                {{-- <h3>{{ trans('_message.schedule') }}</h3> --}}
                {{-- <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p> --}}
            </div>
            <div class="col-lg-4">
                <img src="{{ asset('/img/PC_3.png') }}" alt="" height="380">
                {{-- <h3>{{ trans('_message.monitoring') }}</h3> --}}
                {{-- <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p> --}}
            </div>
        </div>
        <br>
        <hr>
    </div>
    <div class="container">
        <div class="row centered">
            <div class="col-lg-12">
                <h1><b><a href="{{ url('/login') }}">Enter</a></b></h1>
                <br>
                
            </div>
        </div>
    </div> <!--/ .container -->
</div><!--/ #introwrap -->
</section>

<section id="contact" name="contact">
<div id="footerwrap">
    <div class="container">
        <div class="col-lg-5">
            <h3>Krona Koblenz S.P.A.</h3>
            <p>
                via Piane 90,<br/>
                Coriano, Rimini<br/>
                47853<br/>
                Italy
            </p>
        </div>

    </div>
</div>
</section>
<div id="c">
    <div class="container">
        <p>
            <a href="http://intranet.krona.it"></a><b>kNet 2.0</b></a>.<br/>
            <strong>Copyright &copy; {{ Carbon\Carbon::now()->year }}.</strong> {{ trans('_message.createdby') }} <a href="#">Luca Ciotti</a>.
        </p>

    </div>
</div>


<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
{{-- <script src="{{ asset('/js/bootstrap.min.js') }}" type="text/javascript"></script> --}}
<script>
    $('.carousel').carousel({
        interval: 3500
    })
</script>
</body>
</html>
