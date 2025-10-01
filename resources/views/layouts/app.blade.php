<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@stack('title')</title>
    <link rel="shortcut icon" type="image/png" href="{{asset('assets')}}/images/logos/seodashlogo.png" />
    <link rel="stylesheet" href="{{asset('css')}}/feather.css">
    <link rel="stylesheet" href="{{asset('css')}}/dataTables.bootstrap4.css">
    <link rel="stylesheet" href="{{asset('css')}}/select2.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@9.17.2/dist/sweetalert2.min.css">
    <!-- include summernote css/js -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>
    <link rel="stylesheet" href="{{ asset('assets/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{asset('assets')}}/css/styles.min.css">
    <link rel="stylesheet" href="{{asset('css')}}/style_css.css">
    

    @stack('asset_css')
    @stack('style_css')
</head>

<body>
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed">
        <!--  Sidebar -->
        @include('layouts.components.sidebar')
        <div class="body-wrapper">
            <!--  Header -->
            @include('layouts.components.navbar')
            <div class="container-fluid">
                @yield('content')
            </div>
        </div>
    </div>
    @include('layouts.includes.script')
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Cari semua li di sidebar yang punya <ul> di dalamnya
            document.querySelectorAll(".sidebar-nav li").forEach(function (li) {
                let submenu = li.querySelector("ul");
                let link = li.querySelector(".nav-link");

                if (submenu && link) {
                    // Tambah class toggleable supaya kelihatan bisa di-klik
                    link.classList.add("toggleable");

                    // Matikan fungsi href default kalau perlu
                    link.addEventListener("click", function (e) {
                        e.preventDefault();

                        // Tutup semua submenu lain (opsional)
                        document.querySelectorAll(".sidebar-nav ul ul").forEach(function (sm) {
                            if (sm !== submenu) sm.classList.remove("open");
                        });

                        // Toggle submenu ini
                        submenu.classList.toggle("open");
                    });
                }
            });
        });
        
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

        document.addEventListener("DOMContentLoaded", function () {
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

    @yield('script')
</body>
</html>