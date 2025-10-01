@extends('layouts.app')
@section('title', 'Konfigurasi')

@section('nav')

@endsection

@section('content')
@if (session('message'))

@endif

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header w-100">
                @if (session('message'))

                <strong id="msgId" hidden>{{ session('message') }}</strong>

                @endif
                <div class="row">
                    <div class="col-md-6">
                        <h3 class="h3">Konfigurasi</h3>
                    </div>
                    <div class="col-md-6">

                    </div>
                </div>
            </div>
            <div class="card-body">
                {{-- <div class="addData"> --}}
                <a href="javascript:void(0)" class="btn btn-success btnAdd text-white mb-3">
                    <i data-feather="plus" width="16" height="16" class="me-2"></i>
                    Tambah Konfigurasi
                </a>
                {{-- </div> --}}

                <div class="table-responsive">
                    <table id="table-data" class="table table-stripped card-table table-vcenter text-nowrap table-data">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th>Konfigurasi Key</th>
                                <th>Konfigurasi Value</th>
                                <th width="15%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (sizeof($configurations) == 0)
                            <tr>
                                <td colspan="4" align="center">Data kosong</td>
                            </tr>
                            @else
                            @foreach ($configurations as $config)
                            <tr>
                                <td width="5%">{{ $loop->iteration }}</td>
                                <td>{{ $config->key }}</td>
                                <td>{{ $config->value }}</td>
                                <td width="15%">
                                    @if($config->configuration_id > 0)
                                    <a href="javascript:void(0)" class="btn btn-icon btnEdit btn-warning text-white"
                                        data-id="{{ $config->configuration_id }}" data-toggle="tooltip"
                                        data-placement="top" title="Ubah">
                                        <i data-feather="edit" width="16" height="16"></i>
                                    </a>
                                    <a href="javascript:void(0)" class="btn btn-icon btn-danger text-white btnDelete"
                                        data-url="{{ url('configuration/delete/'. $config->configuration_id) }}"
                                        data-toggle="tooltip" data-placement="top" title="Hapus">
                                        <i data-feather="trash-2" width="16" height="16"></i>
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
<div class="modal addModal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Konfigurasi</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form action="{{ url('configuration/store') }}" method="POST" id="addForm">
                @csrf
                <div class="modal-body">
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">Konfigurasi Key<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="key" id="key"
                                        placeholder="Masukan key Konfigurasi" value="{{ old('key') }}">
                                    @if ($errors->has('key'))
                                    <span class="text-danger">
                                        <label id="basic-error" class="validation-error-label" for="basic">Konfigurasi
                                            key
                                            tidak
                                            boleh sama</label>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">Konfigurasi Value<span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="value" id="value"
                                        placeholder="Masukan nilai Konfigurasi" value="{{ old('value') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="text-white btn btn-danger" data-dismiss="modal">Batal</button>
                    <button type="submit" class="text-white btn btn-success">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Modal Add -->
@endsection

@section('script')
<script type="text/javascript">
    $('.btnAdd').click(function () {
        document.getElementById("addForm").reset();
        $('.addModal form').attr('action', "{{ url('configuration/store') }}");
        $('.addModal .modal-title').text('Tambah Konfigurasi');
        $('.addModal').modal('show');
    });

    // check error
    @if(count($errors))
    $('.addModal').modal('show');
    @endif

    $('.btnEdit').click(function () {

        var id = $(this).attr('data-id');
        var url = "{{ url('configuration/getdata') }}";

        $('.addModal form').attr('action', "{{ url('configuration/update') }}" + '/' + id);

        $.ajax({
            type: 'GET',
            url: url + '/' + id,
            dataType: 'JSON',
            success: function (data) {
                console.log(data);

                if (data.status == 1) {

                    $('#key').val(data.result.key);
                    $('#value').val(data.result.value);
                    $('.addModal .modal-title').text('Ubah Konfigurasi');
                    $('.addModal').modal('show');

                }

            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                alert('Error : Gagal mengambil data');
            }
        });

    });

    $('.btnDelete').click(function () {
        $('.btnDelete').attr('disabled', true)
        var url = $(this).attr('data-url');
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
                $.ajax({
                    type: 'GET',
                    url: url,
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
    });

    $("#addForm").validate({
        rules: {
            key: "required",
        },
        messages: {
            key: "Konfigurasi key tidak boleh kosong",
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
