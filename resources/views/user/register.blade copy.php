<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="favicon.ico">
    <title>Register - Hadid</title>
    <!-- Simple bar CSS -->
    <link rel="stylesheet" href="{{asset('css')}}/simplebar.css">
    <link rel="shortcut icon" href="{{asset('img/hadid.png')}}">
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
      <div class="row justify-content-center">
          <div class="col-md-5">
            <a href="/"><img src="{{asset('img')}}/techno.png" class="img-fluid" /></a>
          </div>
        <div class="col-md-4 m-5">

            <form action="{{ url('user/register') }}" method="POST">

                @csrf
                <div class="modal-body">
                    <div class="form-body">
                        <p class="h4 mb-3" style="line-height: 2em;"><strong>Selamat Datang di Hadid</strong></p>

                                <div class="form-group">
                                    <label class="form-label">Nama <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="user_name" id="user_name"
                                        placeholder="Masukan nama pengguna" value="{{ old('user_name') }}">
                                </div>

                                <div class="form-group" hidden>
                                    <label class="form-label">Status <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="user_status" id="user_status"
                                        placeholder="Masukan Status" value="1">
                                </div>

                                <div class="form-group" hidden>
                                    <label class="form-label">Grup <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="group_id" id="group_id"
                                        placeholder="Masukan Grup" value="3">
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" name="user_email" id="user_email"
                                        placeholder="Masukan email pengguna" value="{{ old('user_email') }}">
                                    @if ($errors->has('user_email'))
                                    <span class="text-danger">
                                        <label id="basic-error" class="validation-error-label" for="basic">Email
                                            sudah digunakan</label>
                                    </span>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Password <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="user_password" id="user_password"
                                        placeholder="Masukan Password" value="{{ old('user_password') }}">
                                </div>

                                <div class="form-group" align="center">
                                    <button type="submit" class="btn btn-lg btn-primary form-control form-control-lg mb-3">Buat Akun</button>
                                    <a href="/login">Sudah punya akun!</a>
                                </div>

                    </div>
                </div>

            </form>
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
