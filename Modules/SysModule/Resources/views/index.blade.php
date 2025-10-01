@extends('layouts.app')
@section('title', 'Modul')

@section('content')
<!-- Container fluid  -->
<!-- ============================================================== -->
<!-- <div class="container-fluid"> -->
<!-- ============================================================== -->
<!-- Start Page Content -->
<!-- ============================================================== -->
<!-- basic table -->
@if (session('message'))

@endif

<div class="row title-module">
    <div class="col-12">
        <div class="card top-title">
            <div class="card-header card-header-title">
                <h2>Modul</h2>
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
                            Tambah Modul
                        </a>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="table-data" class="table-data w-100">
                        <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Aksi</th>
                        </tr>
                        </thead>
                        <tbody>
                            @if (sizeof($modules) == 0)
                                <tr>
                                    <td colspan="3" align="center">Data kosong</td>
                                </tr>
                            @else
                                @foreach ($modules as $module)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $module->module_name }}</td>
                                        <td>
                                            @if($module->module_id > 0)
                                                <a onclick="btnEdit('{{ $module->module_id }}')" class="btn">
                                                    <i data-feather="edit" class="i-edit"></i>
                                                </a>
                                                <a onclick="hapusData('{{ $module->module_id }}')" class="btn">
                                                    <i data-feather="trash-2" class="i-delete"></i>
                                                </a>
                                            @endif
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
<div class="modal addModal fade" tabindex="-1" role="dialog" id="modal-add-data">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header modal-header-add">
                <h5 class="modal-title">Tambah Menu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ url('sysmodule/store') }}" method="POST" id="addForm">
                @csrf
                <div class="modal-body">
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">Nama Modul<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="module_name" id="module_name"
                                        placeholder="Masukan nama module" value="{{ old('module_name') }}" required>
                                    @if ($errors->has('module_name'))
                                    <span class="text-danger">
                                        <label id="basic-error" class="validation-error-label" for="basic">Modul tidak
                                            boleh sama</label>
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
        $('.addModal form').attr('action', "{{ url('sysmodule/store') }}");
        $('.addModal .modal-title').text('Tambah Modul');
        $('.addModal').modal('show');
    });

    $("#addForm").validate({
        rules: {
            module_name: "required",
        },
        messages: {
            module_name: "Modul tidak boleh kosong",
        },
        errorElement: "em",
        errorClass: "invalid-feedback",
        errorPlacement: function (error, element) {
            $(element).parents('.form-group').append(error);
        },
        highlight: function (element) {
            $(element).addClass("is-invalid").removeClass("is-valid");
        },
        unhighlight: function (element) {
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


    function btnEdit(id) {
        var url = "{{ url('sysmodule/getdata') }}";
        $('.addModal form').attr('action', "{{ url('sysmodule/update') }}" + '/' + id);

        $.ajax({
            type: 'GET',
            url: url + '/' + id,
            dataType: 'JSON',
            success: function (data) {
                if (data.status == 1) {
                    $('#module_name').val(data.result.module_name);
                    $('.addModal .modal-title').text('Ubah Modul');
                    $('.addModal').modal('show');
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                alert('Error : Gagal mengambil data');
            }
        });
    }

    function hapusData(id) {

        var url = "{{ url('sysmodule/delete') }}";

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
