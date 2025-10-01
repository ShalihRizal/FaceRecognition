@extends('layouts.app')
@section('title', 'Grup')

@section('nav')
<div class="row align-items-center">
    <div class="col">
        <!-- Page pre-title -->
        <div class="page-pretitle">
            Pengguna
        </div>
        <h2 class="page-title">
            Grup Pengguna
        </h2>
    </div>
    <!-- Page title actions -->
    <div class="col-auto ms-auto d-print-none">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent p-0 mt-1 mb-0">
                <li class="breadcrumb-item"><a href="{{ url('') }}"><i data-feather="home"
                            class="breadcrumb-item-icon"></i></a></li>
                <li class="breadcrumb-item"><a href="#">Pengguna</a></li>
                <li class="breadcrumb-item active" aria-current="page">Grup Pengguna</li>
            </ol>
        </nav>
    </div>
</div>
@endsection

@section('content')
<!-- ============================================================== -->
<!-- Start Page Content -->
<!-- ============================================================== -->
<!-- basic table -->

<div class="row title-module">
    <div class="col-12">
        <div class="card top-title">
            <div class="card-header card-header-title">
                <h2>Grup Pengguna</h2>
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
                            Tambah Grup Pengguna
                        </a>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="table-data" class="table-data w-100">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th width="80%">Nama</th>
                                <th width="15%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (sizeof($groups) == 0)
                            <tr>
                                <td colspan="3" align="center">Data kosong</td>
                            </tr>
                            @else
                            @foreach ($groups as $group)
                            <tr>
                                <td width="5%">{{ $loop->iteration }}</td>
                                <td width="80%">{{ $group->group_name }}</td>
                                <td width="15%">
                                    <a onclick="btnEdit('{{ $group->group_id }}')" class="btn">
                                        <i data-feather="edit" class="i-edit"></i>
                                    </a>
                                    <a onclick="hapusData('{{ $group->group_id }}')" class="btn">
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

<!-- Modal Add -->
<div class="modal addModal" tabindex="-1" role="dialog" id="modal-add-data">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header modal-header-add">
                <h5 class="modal-title">Tambah Grup</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ url('usergroup/store') }}" method="POST" id="addForm">
                @csrf
                <div class="modal-body">
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">Nama Grup<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="group_name" id="group_name"
                                        placeholder="Masukan nama grup" value="{{ old('group_name') }}">
                                    @if ($errors->has('group_name'))
                                    <span class="text-danger">
                                        <label id="basic-error" class="validation-error-label" for="basic">Nama grup
                                            sudah digunakan</label>
                                    </span>
                                    @endif
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

<!-- ============================================================== -->
<!-- End PAge Content -->
@endsection

@section('script')
@if (session('message'))
    <script>
        Swal.fire({
            title: 'Berhasil!',
            text: "{{ session('message') }}",
            icon: 'success',
            confirmButtonColor: '#3085d6'
        });
    </script>
@endif
<script type="text/javascript">
    $('.btnAdd').click(function () {
        $('#module_name').val('');
        $('.addModal form').attr('action', "{{ url('usergroup/store') }}");
        $('.addModal .modal-title').text('Tambah Grup');
        $('.addModal').modal('show');
    });

    // check error
    @if(count($errors))
    $('.addModal').modal('show');
    @endif

    function btnEdit(id) {
        var url = "{{ url('usergroup/getdata') }}";
        $('.addModal form').attr('action', "{{ url('usergroup/update') }}" + '/' + id);

        $.ajax({
            type: 'GET',
            url: url + '/' + id,
            dataType: 'JSON',
            success: function (data) {

                if (data.status == 1) {

                    $('#group_name').val(data.result.group_name);
                    $('.addModal .modal-title').text('Ubah Grup');
                    $('.addModal').modal('show');

                }

            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                alert('Error : Gagal mengambil data');
            }
        });
    }

    function hapusData(id) {

        var url = "{{ url('usergroup/delete') }}";

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

    $("#addForm").validate({
        rules: {
            group_name: "required",
        },
        messages: {
            group_name: "Nama grup tidak boleh kosong",
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

</script>
@endsection
