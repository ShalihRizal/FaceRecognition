<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="favicon.ico">
    <title>Login - ULIN YUK</title>
    <!-- Simple bar CSS -->
    <link rel="stylesheet" href="{{asset('css')}}/simplebar.css">
    <link rel="shortcut icon" href="{{asset('img/logo/oxigen.png')}}">
    <!-- Fonts CSS -->
    <link href="https://fonts.googleapis.com/css2?family=Overpass:ital,wght@0,100;0,200;0,300;0,400;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <!-- Icons CSS -->
    <link rel="stylesheet" href="{{asset('css')}}/feather.css">
    <!-- Date Range Picker CSS -->
    <link rel="stylesheet" href="{{asset('css')}}/daterangepicker.css">
    <!-- App CSS -->
    <link rel="stylesheet" href="{{asset('css')}}/app-light.css" id="lightTheme">
    <link rel="stylesheet" href="{{asset('css')}}/app-dark.css" id="darkTheme" disabled>
  </head>
  <body class="light ">
    <div class="mt-10">
      <br>
      <br><br>
      <br><br>
      <div class="row align-items-center h-100">
        <form class="col-lg-3 col-md-4 col-10 mx-auto " action="{{ url('do_login') }}" method="post">
          <div class="text-center">
            <p class="h4 mb-3" style="line-height: 2em;"><strong>Selamat Datang di ULIN YUK</strong></p>
                            <img src="img/logo/oxigen.png" width="220" height="200" />
                        </div>
          @csrf
                            <div class="form-group">
                                <label>Email</label>
                                <input class="form-control form-control-lg" type="email" name="email" placeholder="Masukan Email" />
                            </div>
                            <div class="form-group">
                                <label>Kata Sandi</label>
                                <input class="form-control form-control-lg " type="password" name="password" placeholder="********" />
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-lg btn-primary form-control form-control-lg">Masuk</button>
                            </div>
        </form>
      </div>
    </div>
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
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag()
      {
        dataLayer.push(arguments);
      }
      gtag('js', new Date());
      gtag('config', 'UA-56159088-1');
    </script>
  </body>
</html>
</body>
</html>
