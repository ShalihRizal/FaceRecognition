@extends('layouts.app')
@section('title', 'Daftar')

@section('nav')
<div class="row align-items-center">
    <div class="col">
        <!-- Page pre-title -->
        <div class="page-pretitle">
            Pengguna
        </div>
        <h2 class="page-title">
            Daftar Pengguna
        </h2>
    </div>
    <!-- Page title actions -->
    <div class="col-auto ms-auto d-print-none">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent p-0 mt-1 mb-0">
                <li class="breadcrumb-item"><a href="{{ url('') }}"><i data-feather="home"
                            class="breadcrumb-item-icon"></i></a></li>
                <li class="breadcrumb-item"><a href="#">Pengguna</a></li>
                <li class="breadcrumb-item active" aria-current="page">Daftar Pengguna</li>
            </ol>
        </nav>
    </div>
</div>
@endsection

@section('content')
<!-- ============================================================== -->
<!-- Container fluid  -->
<!-- ============================================================== -->
<!-- <div class="container-fluid"> -->
<!-- ============================================================== -->
<!-- Start Page Content -->
<!-- ============================================================== -->
<!-- basic table -->

<div class="row title-module">
    <div class="col-12">
        <div class="card top-title">
            <div class="card-header card-header-title">
                <h2>Daftar Pengguna</h2>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card card-content">
            <div class="card-body">
                <div class="row list-btn">
                    <div class="col-md-3">
                        <a href="javascript:void(0)" class="btn btn-add-data btnAdd text-white">
                            <i data-feather="plus" width="16" height="16" class="me-2"></i>
                            Tambah Daftar Pengguna
                        </a>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="table-data" class="table-data w-100">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th width="25%">Nama</th>
                                <th width="25%">Email</th>
                                <th width="15%">Group</th>
                                <th width="15%">Status</th>
                                <th width="15%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (sizeof($users) == 0)
                            <tr>
                                <td colspan="3" align="center">Data kosong</td>
                            </tr>
                            @else

                            @foreach ($users as $user)
                            <tr>
                                <td width="5%">{{ $loop->iteration }}</td>
                                <td width="25%">{{ $user->user_name }}</td>
                                <td width="25%">{{ $user->user_email }}</td>
                                <td width="15%">{{$user->group_name  }}</td>
                                <td width="15%">
                                    @php
                                    $userstatus = ($user->user_status == 1) ? "Aktif" : "Tidak Aktif" ;
                                    @endphp
                                    {{ $userstatus  }}
                                </td>
                                <td width="15%">
                                    <a onclick="btnEdit('{{ $user->user_id }}')" class="btn">
                                        <i data-feather="edit" class="i-edit"></i>
                                    </a>
                                    <a onclick="hapusData('{{ $user->user_id }}')" class="btn">
                                        <i data-feather="trash-2" class="i-delete"></i>
                                    </a>
                                </td>

                            </tr>
                            @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- ============================================================== -->
<!-- End PAge Content -->
<!-- ============================================================== -->
<!-- </div> -->
<!-- ============================================================== -->
<!-- End Container fluid  -->

<!-- Modal Add -->
<div class="modal addModal" tabindex="-1" role="dialog" id="modal-add-data">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header modal-header-add">
                <h5 class="modal-title">Tambah Pengguna</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ url('users/store') }}" method="POST" id="addForm">
                @csrf
                <div class="modal-body">
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label class="form-label">Nama <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="user_name" id="user_name"
                                        placeholder="Masukan nama pengguna" value="{{ old('user_name') }}">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
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
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label class="form-label">Password <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control mb-2" name="user_password_old"
                                        id="user_password_old" placeholder="Masukan password pengguna" readonly
                                        value="{{ old('user_password_old') }}">
                                    {{-- Collapse Update Password --}}
                                    <button type="button" class="btn btn-warning mb-2" id="password_collapse_edit"
                                        data-toggle="collapse" data-target="#toggle-collapse"
                                        id="btn-title-collapse">Update
                                        Password</button>
                                    <span class="form-label text-sm text-danger" id="hint_password"></span>
                                    <div id="toggle-collapse" class="collapse">
                                        <div class="input-group">
                                            <input type="password" id="user_password" name="user_password"
                                                class="form-control" placeholder="Masukan password baru">
                                            <div class="input-group-append">
                                                <span id="myeyesbutton" onclick="change()" class="input-group-text">
                                                    <svg width="1em" height="1em" viewBox="0 0 16 16"
                                                        class="bi bi-eye-fill" fill="currentColor"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z" />
                                                        <path fill-rule="evenodd"
                                                            d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8zm8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z" />
                                                    </svg>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="form-group mt-2" id="password-generated">
                                            <label class="form-label">Password Length:</label>
                                            <input type="number" class="col-md-2" id="the_length_pass" size=3
                                                maxlength="2" value="10">
                                            <button type="button" class="btn btn-light" id="generate_password"
                                                title="Generate Password">Generate <i
                                                    class="fas fa-sync-alt ml-1"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label class="form-label">Group <span class="text-danger">*</span> </label>
                                    <select class="form-control" name="group_id" id="group_id">
                                        <option value="">- Pilih Group -</option>
                                        <option value="{{ old('group_id') }}" selected="selected"></option>
                                        @if(sizeof($groups) > 0)
                                        @foreach($groups as $group)
                                        <option value="{{ $group->group_id }}">{{ $group->group_name }}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3" id="user_status_form">
                                <div class="form-group">
                                    <label class="form-label">Status <span class="text-danger">*</span></label>
                                    <select class="form-control" name="user_status" id="user_status">
                                        <option value="">- Pilih Status -</option>
                                        <option value="{{ old('user_status') }}" selected="selected"></option>
                                        <option value="1" selected="selected">- Aktif -</option>
                                        <option value="0">- Tidak Aktif -</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="col-md-3">
                        <button type="button" class="text-white btn button-cancel" data-bs-dismiss="modal">Batal</button>
                    </div> 
                    <div class="col-md-3">
                        <button type="submit" class="text-white btn btn-add-data">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Modal Add -->
@endsection

@section('script')
@if (session('successMessage'))
    <script>
        Swal.fire({
            title: 'Berhasil!',
            text: "{{ session('successMessage') }}",
            icon: 'success',
            confirmButtonColor: '#3085d6'
        });
    </script>
@endif
<script type="text/javascript">
    $('.btnAdd').click(function () {
        document.getElementById("addForm").reset();
        $('#module_name').val('');
        $('.addModal form').attr('action', "{{ url('users/store') }}");

        document.getElementById('password_collapse_edit').style.display = 'none';
        $('#password_collapse_edit').text('Generate Password');
        document.getElementById('hint_password').style.display = 'none';
        document.getElementById('user_password_old').style.display = 'none';
        document.getElementById('user_password').style.display = 'blok';
        document.getElementById('user_status_form').style.display = 'none';
        $('.addModal .modal-title').text('Tambah Pengguna');
        $('.addModal').modal('show');

        document.getElementById('btn-title-collapse').value = "Generate Password";
    });

    // check error
    @if(count($errors))
    $('.addModal').modal('show');
    @endif

    $('#generate_password').click(function () {

        let keylist = "123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz!@#$%^&*"
        let temp = ''
        let length = $('#the_length_pass').val();


        for (i = 0; i < length; i++)
            temp += keylist.charAt(Math.floor(Math.random() * keylist.length))

        $('#user_password').val(temp);

    });


    $('.cancel').click(function () {
        // set clear value input password
        $('#user_password').val('');
    });

    $("#addForm").validate({
        rules: {
            group_id: "required",
            user_email: "required",
            user_name: "required",
        },
        messages: {
            group_id: "Nama grup tidak boleh kosong",
            user_name: "User Name tidak boleh kosong",
            user_email: "Email tidak boleh kosong",
        },
        errorElement: "em",
        errorClass: "invalid-feedback",
        errorPlacement: function (error, element) {
            // Add the `help-block` class to the error element
            $(element).parents('.form-group').append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass("is-invalid").removeClass("is-valid");
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).addClass("is-valid").removeClass("is-invalid");
        },

        // Jalankan loading hanya kalau validasi lolos
        submitHandler: function(form) {
            Swal.fire({
                title: 'Menyimpan data...',
                text: 'Mohon tunggu sebentar',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            form.submit(); // lanjut submit normal
        },

        // Optional: alert kalau validasi gagal
        invalidHandler: function() {
            Swal.fire({
                title: 'Gagal!',
                text: 'Periksa kembali input yang wajib diisi.',
                icon: 'error'
            });
        }
    });
    

    var notyf = new Notyf({
        duration: 5000,
        position: {
            x: 'right',
            y: 'top'
        }
    });
    var msg = $('#msgId').html()
    if (msg !== undefined) {
        notyf.success(msg)
    }

    //show hide password
    function change() {
        var x = document.getElementById('user_password').type;
        if (x == 'password') {
            document.getElementById('user_password').type = 'text';

            document.getElementById('myeyesbutton').innerHTML = `<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-eye-slash-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M10.79 12.912l-1.614-1.615a3.5 3.5 0 0 1-4.474-4.474l-2.06-2.06C.938 6.278 0 8 0 8s3 5.5 8 5.5a7.029 7.029 0 0 0 2.79-.588zM5.21 3.088A7.028 7.028 0 0 1 8 2.5c5 0 8 5.5 8 5.5s-.939 1.721-2.641 3.238l-2.062-2.062a3.5 3.5 0 0 0-4.474-4.474L5.21 3.089z"/>
                                                        <path d="M5.525 7.646a2.5 2.5 0 0 0 2.829 2.829l-2.83-2.829zm4.95.708l-2.829-2.83a2.5 2.5 0 0 1 2.829 2.829z"/>
                                                        <path fill-rule="evenodd" d="M13.646 14.354l-12-12 .708-.708 12 12-.708.708z"/>
                                                        </svg>`;
        } else {
            document.getElementById('user_password').type = 'password';

            document.getElementById('myeyesbutton').innerHTML = `<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-eye-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z"/>
                                                        <path fill-rule="evenodd" d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8zm8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z"/>
                                                        </svg>`;
        }
    }

    function btnEdit(id) {
        var url = "{{ url('users/getdata') }}";

        // set display password input
        document.getElementById('user_password_old').style.display = 'block';
        document.getElementById('password_collapse_edit').style.display = 'block';
        document.getElementById('hint_password').style.display = 'block';
        document.getElementById('user_status_form').style.display = 'block';
        $('.addModal form').attr('action', "{{ url('users/update') }}" + '/' + id);

        $.ajax({
            type: 'GET',
            url: url + '/' + id,
            dataType: 'JSON',
            success: function (data) {
                console.log(data);

                if (data.status == 1) {

                    $('#user_name').val(data.result.user_name);
                    $('#user_email').val(data.result.user_email);

                    $('#user_password_old').val(data.result.user_password);
                    $('#group_id').val(data.result.group_id);
                    $('#user_status').val(data.result.user_status);

                    $('.addModal .modal-title').text('Ubah Pengguna');
                    $('.addModal').modal('show');

                }

            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                alert('Error : Gagal mengambil data');
            }
        });
    }

    function hapusData(id) {

        var url = "{{ url('users/delete') }}";

        Swal.fire({
            title: 'Apakah anda yakin ingin menghapus data?',
            text: "Kamu tidak akan bisa mengembalikan data ini setelah dihapus!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya. Hapus'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Menghapus data...',
                    text: 'Mohon tunggu sebentar',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                $.ajax({
                    type: 'GET',
                    url: url + '/' + id,
                    success: function (data) {
                        if (result.isConfirmed) {
                            Swal.fire(
                                'Terhapus!',
                                'Data Berhasil Dihapus.',
                                'success'
                            ).then(() => {
                                location.reload()
                            })
                        }
                    },
                    error: function (XMLHttpRequest, textStatus, errorThrown) {
                        Swal.fire(
                            'Gagal!',
                            'Gagal menghapus data.',
                            'error'
                        );
                    }
                });
            }
        })
    }

</script>
@endsection
