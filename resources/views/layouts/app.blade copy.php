<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="favicon.ico">
    <title>
        @if(View::hasSection('title'))
        @yield('title') -
        @endif
        Hadid
    </title>
    <!-- Simple bar CSS -->
    <link rel="stylesheet" href="{{asset('css')}}/simplebar.css">

    <link rel="shortcut icon" href="{{asset('img')}}/hadid.png">
    <!-- Fonts CSS -->
    <link href="https://fonts.googleapis.com/css2?family=Overpass:ital,wght@0,100;0,200;0,300;0,400;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <!-- Icons CSS -->
    <link rel="stylesheet" href="{{asset('css')}}/feather.css">
    <link rel="stylesheet" href="{{asset('css')}}/dataTables.bootstrap4.css">
    <link rel="stylesheet" href="{{asset('css')}}/select2.css">
    <link rel="stylesheet" href="{{asset('css')}}/dropzone.css">
    <link rel="stylesheet" href="{{asset('css')}}/uppy.min.css">
    <link rel="stylesheet" href="{{asset('css')}}/jquery.steps.css">
    <link rel="stylesheet" href="{{asset('css')}}/jquery.timepicker.css">
    <link rel="stylesheet" href="{{asset('css')}}/quill.snow.css">
    <!-- Date Range Picker CSS -->
    <link rel="stylesheet" href="{{asset('css')}}/daterangepicker.css">
    <!-- App CSS -->
    <link rel="stylesheet" href="{{asset('css')}}/app-light.css" id="lightTheme">
    <link rel="stylesheet" href="{{asset('css')}}/app-dark.css" id="darkTheme" disabled>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@9.17.2/dist/sweetalert2.min.css">
    <!-- include summernote css/js -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>

    <style type="text/css">
        .parsley-errors-list li {
            list-style: none;
            color: red;
        }
        .handlelist{
            cursor: move;
            cursor: -webkit-grabbing;
        }
        .handleimage{
            cursor: move;
            cursor: -webkit-grabbing;
        }
        .ghost {
          opacity: 0.4;
        }
    </style>

  </head>
  <body class="vertical  light">
    <div class="wrapper">
      <nav class="topnav navbar navbar-light">
        <button type="button" class="navbar-toggler text-muted mt-2 p-0 mr-3 collapseSidebar">
          {{-- <i class="fe fe-menu navbar-toggler-icon"></i> --}}
          <i class="navbar-toggler-icon" data-feather="menu" width="16" height="16"></i>
        </button>

        <ul class="nav">
          <li class="nav-item">
            <a class="nav-link text-muted my-2" href="#" id="modeSwitcher" data-mode="light">
              {{-- <i class="fe fe-sun fe-16"></i> --}}
              <i data-feather="sun" width="16" height="16"></i>
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link dropdown-toggle text-muted pr-0" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="avatar avatar-sm mt-2">
              @if(Auth::user()->user_image == NULL)
                                    <img src="{{url('img/avatars/profile.png')}}"
                                    class="avatar-img rounded-circle" alt="user_name" />
                                @else
                                <img src="{{Auth::user()->user_image}}"
                                    class="avatar-img rounded-circle" alt="user_name" />
                                @endif
                                <span>{{ Auth::user()->user_name }}</span>
                            </span>
            </a>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
              <a class="dropdown-item" href="/profile">Profile</a>
              <a class="dropdown-item logout" data-url="/logout">Keluar</a>
            </div>
          </li>
        </ul>
      </nav>
      <aside class="sidebar-left border-right bg-white shadow" id="leftSidebar" data-simplebar>
        <a href="#" class="btn collapseSidebar toggle-btn d-lg-none text-muted" data-toggle="toggle">
          <i class="fe fe-x"><span class="sr-only"></span></i>
        </a>
        <nav class="vertnav navbar navbar-light">
          <!-- nav bar -->
          <div class="w-50 mb-4 d-flex">
            <img class="navbar-brand mx-auto mt-2 flex-fill text-center" src="{{asset('img')}}/hadid.png" width="50"/>
            {{-- <strong>hadid</strong> --}}
          </div>
          <ul class="navbar-nav flex-fill w-100 mb-2">
            @include('components.menudash')
          </ul>

        </nav>
      </aside>
      <main role="main" class="main-content">
        @yield('content')
      </main> <!-- main -->
    </div> <!-- .wrapper -->
    <script src="{{asset('js')}}/jquery.min.js"></script>
    <script src="{{asset('js')}}/popper.min.js"></script>
    <script src="{{asset('js')}}/moment.min.js"></script>
    <script src="{{asset('js')}}/bootstrap.min.js"></script>
    <script src="{{asset('js')}}/simplebar.min.js"></script>
    <script src="{{asset('js')}}/daterangepicker.js"></script>
    <script src="{{asset('js')}}/jquery.stickOnScroll.js"></script>
    <script src="{{asset('js')}}/tinycolor-min.js"></script>
    <script src="{{asset('js')}}/config.js"></script>
    <script src="{{asset('js')}}/d3.min.js"></script>
    <script src="{{asset('js')}}/topojson.min.js"></script>
    <script src="{{asset('js')}}/datamaps.all.min.js"></script>
    <script src="{{asset('js')}}/datamaps-zoomto.js"></script>
    <script src="{{asset('js')}}/datamaps.custom.js"></script>
    <script src="{{asset('js')}}/Chart.min.js"></script>
    <script>
      /* defind global options */
      Chart.defaults.global.defaultFontFamily = base.defaultFontFamily;
      Chart.defaults.global.defaultFontColor = colors.mutedColor;
    </script>
    <script src="{{asset('js')}}/gauge.min.js"></script>
    <script src="{{asset('js')}}/jquery.sparkline.min.js"></script>
    <script src="{{asset('js')}}/apexcharts.min.js"></script>
    <script src="{{asset('js')}}/apexcharts.custom.js"></script>
    <script src="{{asset('js')}}/jquery.mask.min.js"></script>
    <script src="{{asset('js')}}/select2.min.js"></script>
    <script src="{{asset('js')}}/jquery.steps.min.js"></script>
    <script src="{{asset('js')}}/jquery.validate.min.js"></script>
    <script src="{{asset('js')}}/jquery.timepicker.js"></script>
    <script src="{{asset('js')}}/dropzone.min.js"></script>
    <script src="{{asset('js')}}/uppy.min.js"></script>
    <script src="{{asset('js')}}/quill.min.js"></script>
    <script src="{{asset('js')}}/jquery.dataTables.min.js"></script>
    <script src="{{asset('js')}}/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <!-- include summernote css/js -->
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
    <!-- include parsley js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/parsley.js/2.8.0/parsley.min.js"
        integrity="sha512-wNs1j1Vo1t0stXW7Lz5QL6T7a/9ClH7/X10Q4jd3aIcRsFTTPh0gRkTxRk0jgXcloVwNIrvmkyStp99hMObegQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // $(".table-data").DataTable({
            //     pageLength: 10,
            //     lengthChange: false,
            //     bFilter: true,
            //     autoWidth: true
            // });
            $('.table-data').DataTable(
            {
            autoWidth: true,
            "lengthMenu": [
                [10, 15, 20, -1],
                [10, 15, 20, "All"]
            ]
            });
        });

    </script>

    <script>
        feather.replace()
        // summernote
        $(document).ready(function () {
            $(".summernote").summernote({
            height: 200,
            toolbar: [
              ['style', ['highlight', 'bold', 'italic', 'underline', 'clear']],
              ['font', ['strikethrough', 'superscript', 'subscript']],
              ['para', ['ul', 'ol', 'paragraph']],
              ['insert', ['link']],
              ['view', ['codeview']]
            ],
            });
        });

        // choose file
        $('.custom-file-input').on('change', function () {
            let fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').addClass("selected").html(fileName);
        });
    </script>

    <script src="{{asset('js')}}/apps.js"></script>
    <!-- <script src="{{ url('js/app.js') }}"></script> -->
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

    <script>
        $('.logout').click(function () {
        $('.logout').attr('disabled', true)
        var url = $(this).attr('data-url');
        Swal.fire({
            title: 'Apakah anda yakin ingin Logout ?',
            text: "",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya. Logout'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: 'GET',
                    url: url,
                    success: function (data) {
                        if (result.isConfirmed) {
                            Swal.fire(
                                'Berhasil!',
                                'Berhasil Logout.',
                                'success'
                            ).then(() => {
                                location.reload()
                            })
                        }
                    },
                    error: function (XMLHttpRequest, textStatus, errorThrown) {
                        Swal.fire(
                            'Gagal!',
                            'Gagal Logout.',
                            'error'
                        );
                    }
                });
            }
        })
    });
    </script>

    @yield('script')
  </body>
</html>
