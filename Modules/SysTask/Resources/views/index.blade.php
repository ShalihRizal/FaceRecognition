@extends('layouts.app')
@section('title', 'Task')

@section('nav') 
<div class="row align-items-center">
    <div class="col">
        <div class="page-pretitle">Hak Akses</div>
        <h2 class="page-title">Task</h2>
    </div>
    <div class="col-auto ms-auto d-print-none">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent p-0 mt-1 mb-0">
                <li class="breadcrumb-item"><a href="{{ url('') }}"><i data-feather="home" class="breadcrumb-item-icon"></i></a></li>
                <li class="breadcrumb-item"><a href="#">Hak Akses</a></li>
                <li class="breadcrumb-item active" aria-current="page">Task</li>
            </ol>
        </nav>
    </div>
</div>
@endsection

@section('content')
<div class="row title-module">
    <div class="col-12">
        <div class="card top-title">
            <div class="card-header card-header-title">
                <h2>Task</h2>
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
                            <i data-feather="plus" width="16" height="16" class="me-2"></i> Tambah Task
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="javascript:void(0)" class="btn btn-add-data-secondary btnAddDataTask text-white">
                            <i data-feather="plus" width="16" height="16" class="me-2"></i> Tambah Data Task
                        </a>
                    </div>
                </div>

                <div class="table-responsive">
                    <table id="table-data" class="table-data w-100">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Module</th>
                                <th>Task</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (sizeof($tasks) == 0)
                                <tr><td colspan="4" align="center">Data kosong</td></tr>
                            @else
                                @foreach ($tasks as $task)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $task->module_name }}</td>
                                        <td>{{ $task->task_data_name }}</td>
                                        <td>
                                            <a onclick="btnEdit('{{ $task->task_id }}')" class="btn">
                                                <i data-feather="edit" class="i-edit"></i>
                                            </a>
                                            <a onclick="hapusData('{{ $task->task_id }}')" class="btn">
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

@include('systask::modal')
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

<script>
$(document).ready(function() {
    // init select2
    $('.js-example-basic-multiple').select2({
        dropdownParent: $('#modal-add-data'),
        width: '100%'
    });
});

// === Tambah Task ===
$('.btnAdd').click(function () {
    $('#module_id').val('');
    $('#task_data_id').val([]).trigger('change');

    // set multiple mode
    $('#task_data_id').attr('multiple', 'multiple').attr('name','task_data_id[]');

    $('.addModal form').attr('action', "{{ url('systask/store') }}");
    $('.addModal .modal-title').text('Tambah Task');
    $('.addModal').modal('show');
});

// === Tambah Data Task ===
$('.btnAddDataTask').click(function () {
    $('#task_data_name').val('');
    $('.addModalDataTask').modal('show');
});

// === Edit Task ===
function btnEdit(id) {
    var url = "{{ url('systask/getdata') }}";
    $('.addModal form').attr('action', "{{ url('systask/update') }}/" + id);

    $.ajax({
        type: 'GET',
        url: url + '/' + id,
        dataType: 'JSON',
        success: function (data) {
            if (data.status == 1) {
                $('#module_id').val(data.result.module_id);

                // ubah ke mode single
                $('#task_data_id').removeAttr('multiple').attr('name','task_data_id');

                $('#task_data_id').val(data.result.task_data_id).trigger('change');
                $('.addModal .modal-title').text('Ubah Task');
                $('.addModal').modal('show');
            }
        },
        error: function () {
            alert('Error : Gagal mengambil data');
        }
    });
}

// === Hapus Task ===
function hapusData(id) {
    var url = "{{ url('systask/delete') }}";

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
                didOpen: () => { Swal.showLoading(); }
            });
            $.ajax({
                type: 'GET',
                url: url + '/' + id,
                success: function () {
                    Swal.fire('Terhapus!','Data Berhasil Dihapus.','success')
                        .then(() => location.reload());
                },
                error: function () {
                    Swal.fire('Gagal!','Gagal menghapus data.','error');
                }
            });
        }
    })
}

// === Validasi Form Add/Update ===
$("#addForm").validate({
    rules: {
        module_id: "required",
        task_data_id: "required",
    },
    messages: {
        module_id: "Modul harus dipilih",
        task_data_id: "Task harus diisi",
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

    submitHandler: function(form) {
        Swal.fire({
            title: 'Menyimpan data...',
            text: 'Mohon tunggu sebentar',
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading(); }
        });

        $.ajax({
            url: $(form).attr('action'),
            type: $(form).attr('method'),
            data: $(form).serialize(),
            success: function(res) {
                Swal.close();

                if (res.status === 'success') {
                    Swal.fire({
                        title: 'Berhasil!',
                        text: res.message,
                        icon: 'success',
                        confirmButtonColor: '#3085d6'
                    }).then(() => location.reload());
                } 
                else if (res.status === 'warning') {
                    let existsList = (res.exists || []).map(e => 
                        `Module: ${e.module_id}, Task: ${e.task_data_id}`
                    ).join('<br>');

                    let doneList = (res.inserted || []).map(e => 
                        `Module: ${e.module_id}, Task: ${e.task_data_id}`
                    ).join('<br>');

                    // Bangun pesan HTML dinamis
                    let htmlMessage = `${res.message}<br><br>`;
                    if (doneList) {
                        htmlMessage += `<b>Data yang berhasil tersimpan:</b><br>${doneList}<br><br>`;
                    }
                    if (existsList) {
                        htmlMessage += `<b>Data sudah ada:</b><br>${existsList}`;
                    }

                    Swal.fire({
                        title: 'Perhatian!',
                        html: htmlMessage,
                        icon: 'warning',
                        confirmButtonColor: '#3085d6'
                    }).then(() => location.reload());
                }
            },
            error: function(xhr) {
                Swal.close();
                Swal.fire({
                    title: 'Gagal!',
                    text: xhr.responseJSON?.message || 'Terjadi kesalahan saat menyimpan data.',
                    icon: 'error',
                    confirmButtonColor: '#d33'
                }).then(() => location.reload());
            }
        });

        return false;
    }
});

$("#addFormTask").validate({
    rules: {
        task_data_name: "required",
    },
    messages: {
        task_data_name: "Task Data Nama tidak boleh kosong",
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

    submitHandler: function(form) {
        Swal.fire({
            title: 'Menyimpan data...',
            text: 'Mohon tunggu sebentar',
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading(); }
        });

        $.ajax({
            url: $(form).attr('action'),
            type: $(form).attr('method'),
            data: $(form).serialize(),
            success: function(res) {
                Swal.close();

                if (res.status === 'success') {
                    Swal.fire({
                        title: 'Berhasil!',
                        text: res.message,
                        icon: 'success',
                        confirmButtonColor: '#3085d6'
                    }).then(() => location.reload());
                } 
                else if (res.status === 'warning') {
                    // Bangun pesan HTML dinamis
                    let htmlMessage = `${res.message}<br><br>`;
                    
                    Swal.fire({
                        title: 'Perhatian!',
                        html: htmlMessage,
                        icon: 'warning',
                        confirmButtonColor: '#3085d6'
                    }).then(() => location.reload());
                }
            },
            error: function(xhr) {
                Swal.close();
                Swal.fire({
                    title: 'Gagal!',
                    text: xhr.responseJSON?.message || 'Terjadi kesalahan saat menyimpan data.',
                    icon: 'error',
                    confirmButtonColor: '#d33'
                }).then(() => location.reload());
            }
        });

        return false;
    }
});
</script>
@endsection
