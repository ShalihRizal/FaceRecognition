<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="favicon.ico">
    <title>Login</title>
    <!-- Simple bar CSS -->
    <link rel="stylesheet" href="{{asset('css')}}/simplebar.css">
    <link rel="shortcut icon" href="{{asset('img')}}/hadid.png">
    <!-- Fonts CSS -->
    <link href="https://fonts.googleapis.com/css2?family=Overpass:ital,wght@0,100;0,200;0,300;0,400;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <!-- Icons CSS -->
    <link rel="stylesheet" href="{{asset('css')}}/feather.css">
    <!-- Date Range Picker CSS -->
    <link rel="stylesheet" href="{{asset('css')}}/daterangepicker.css">
    <!-- App CSS -->
    <link rel="stylesheet" href="{{asset('css')}}/app-light.css" id="lightTheme">
    <link rel="stylesheet" href="{{asset('css')}}/app-dark.css" id="darkTheme" disabled>
    {{-- {!! ReCaptcha::htmlScriptTagJsApi() !!} --}}
  </head>
  <body class="light ">
    <div class="mt-10">
      <br>
      <br><br>
      <br><br>
      <div class="row justify-content-center">
        <div class="card">
            <div class="card-body">

                <form action="{{ url('do_login') }}" method="post">
                    <p class="h4 mb-3" style="line-height: 2em;" align="center"><strong>Selamat Datang</strong></p>
                    <div align="center">
                        {{-- <a href="/"><img src="{{asset('img')}}/hadid.png" width="200" class="img-fluid" /></a> --}}
                      </div>
                    @csrf
                                      <div class="form-group">
                                          <label>Username</label>
                                          <input class="form-control form-control-lg" type="text" name="user_username" id="user_username" placeholder="Masukan Username" />
                                      </div>
                                      <div class="form-group">
                                          <label>Kata Sandi</label>
                                          <div class="input-group">
                                            <input class="form-control form-control-lg " type="password" name="password" id="password" placeholder="********" />
                                          <div class="input-group-append">
                                            <span id="myeyesbutton" onclick="change()" class="input-group-text">
                                                <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-eye-fill"
                                                    fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z" />
                                                    <path fill-rule="evenodd"
                                                        d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8zm8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z" />
                                                </svg>
                                            </span>
                                        </div>
                                          </div>
                                    </div>
                                      <div class="form-group" align="center">
                                          <button type="submit" class="btn btn-lg btn-primary form-control form-control-lg mb-3">Masuk</button>
                                          {{-- <a href="/register">Belum punya akun!</a> --}}
                                          {{-- <a href="mailto:info@nocturnailed.tech">Bantuan!</a> --}}
                                      </div>
                  </form>
            </div>
        </div>
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
    <script type="text/javascript">
      window.dataLayer = window.dataLayer || [];
      function gtag()
      {
        dataLayer.push(arguments);
      }
      gtag('js', new Date());
      gtag('config', 'UA-56159088-1');

      //show hide password
    function change() {
        var x = document.getElementById('password').type;
        if (x == 'password') {
            document.getElementById('password').type = 'text';

            document.getElementById('myeyesbutton').innerHTML = `<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-eye-slash-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M10.79 12.912l-1.614-1.615a3.5 3.5 0 0 1-4.474-4.474l-2.06-2.06C.938 6.278 0 8 0 8s3 5.5 8 5.5a7.029 7.029 0 0 0 2.79-.588zM5.21 3.088A7.028 7.028 0 0 1 8 2.5c5 0 8 5.5 8 5.5s-.939 1.721-2.641 3.238l-2.062-2.062a3.5 3.5 0 0 0-4.474-4.474L5.21 3.089z"/>
                                                        <path d="M5.525 7.646a2.5 2.5 0 0 0 2.829 2.829l-2.83-2.829zm4.95.708l-2.829-2.83a2.5 2.5 0 0 1 2.829 2.829z"/>
                                                        <path fill-rule="evenodd" d="M13.646 14.354l-12-12 .708-.708 12 12-.708.708z"/>
                                                        </svg>`;
        } else {
            document.getElementById('password').type = 'password';

            document.getElementById('myeyesbutton').innerHTML = `<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-eye-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z"/>
                                                        <path fill-rule="evenodd" d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8zm8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z"/>
                                                        </svg>`;
        }
    }
    </script>
  </body>
</html>
</body>
</html>
