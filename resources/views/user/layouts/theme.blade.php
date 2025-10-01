<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="favicon.ico">
    <title>@stack('title')</title>
    <!-- Simple bar CSS -->
    <link rel="stylesheet" href="{{asset('css')}}/simplebar.css">
    <link rel="shortcut icon" href="{{asset('img')}}/hadid.png">
    <!-- Fonts CSS -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <!-- Icons CSS -->
    <link rel="stylesheet" href="{{asset('css')}}/feather.css">
    <!-- Date Range Picker CSS -->
    <link rel="stylesheet" href="{{asset('css')}}/daterangepicker.css">
    <!-- App CSS -->
    <link rel="stylesheet" href="{{asset('css')}}/app-light.css" id="lightTheme">
    <link rel="stylesheet" href="{{asset('css')}}/app-dark.css" id="darkTheme" disabled>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('assets/fontawesome/css/all.min.css') }}">

    @stack('asset_css')
    <link rel="stylesheet" href="{{asset('css')}}/style_css.css">
    {{-- {!! ReCaptcha::htmlScriptTagJsApi() !!} --}}
    @stack('style_css')
  </head>
  <body class="light ">
    @yield('content')
    <script src="{{asset('js')}}/jquery.min.js"></script>
    <script src="{{asset('js')}}/popper.min.js"></script>
    <script src="{{asset('js')}}/moment.min.js"></script>
    <script src="{{asset('js')}}/bootstrap.min.js"></script>
    <script src="{{asset('js')}}/simplebar.min.js"></script>
    <script src="{{asset('js')}}/daterangepicker.js"></script>
    <script src="{{asset('js')}}/jquery.stickOnScroll.js"></script>
    <script src="{{asset('js')}}/tinycolor-min.js"></script>
    <script src="{{asset('js')}}/config.js"></script>
    <script src="{{asset('js')}}/apps.js"></script>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-56159088-1"></script>
    <script type="text/javascript">
      window.dataLayer = window.dataLayer || [];

      function gtag(){
        dataLayer.push(arguments);
      }

      gtag('js', new Date());
      gtag('config', 'UA-56159088-1');
    </script>
    <script src="{{asset('js')}}/scripts_js.js"></script>
    @yield('script')
  </body>
</html>
</body>
</html>
