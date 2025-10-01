@extends('layouts.app')
@section('title', 'Ubah Role')

@section('nav')
<div class="row align-items-center">
    <div class="col">
        <!-- Page pre-title -->
        <div class="page-pretitle">
            Hak Akses
        </div>
        <h2 class="page-title">
            Role
        </h2>
    </div>
    <!-- Page title actions -->
    <div class="col-auto ms-auto d-print-none">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent p-0 mt-1 mb-0">
                <li class="breadcrumb-item"><a href="{{ url('') }}"><i data-feather="home" class="breadcrumb-item-icon"></i></a></li>
                <li class="breadcrumb-item"><a href="#">Hak Akses</a></li>
                <li class="breadcrumb-item active" aria-current="page">Role</li>
            </ol>
        </nav>
    </div>
</div>
@endsection

@push('style_css')
<style type="text/css">
    .switch {
        display: inline-block;
        height: 34px;
        position: relative;
        width: 60px;
    }
    .switch input {
        display:none;
    }
    .slider {
        background-color: #FF0000;
        bottom: 0;
        cursor: pointer;
        left: 0;
        position: absolute;
        right: 0;
        top: 0;
        transition: .4s;
    }
    .slider:before {
        background-color: #fff;
        bottom: 4px;
        content: "";
        height: 26px;
        left: 4px;
        position: absolute;
        transition: .4s;
        width: 26px;
    }
    input:checked + .slider {
        background-color: #66bb6a;
    }
    input:checked + .slider:before {
        transform: translateX(26px);
    }
    .slider.round {
        border-radius: 34px;
    }
    .slider.round:before {
        border-radius: 50%;
    }

    .big-checkbox {
        transform: scale(1.5); /* Ubah 1.5 jadi lebih besar sesuai kebutuhan */
        -webkit-transform: scale(1.5);
        margin: 5px;
        cursor: pointer;
    }
</style>
@endpush

@section('content')
<!-- ============================================================== -->
<!-- Container fluid  -->
<!-- ============================================================== -->
<!-- <div class="container-fluid"> -->
<!-- ============================================================== -->
<!-- Start Page Content -->
<!-- ============================================================== -->
<!-- basic table -->

<div class="row">
    <ol class="breadcrumb bg-transparent">
        <li class="breadcrumb-item"><a href="/sysrole">Role</a></li>
        <li class="breadcrumb-item active"><a disabled>Ubah Role</a></li>
    </ol>
</div>

<div class="row title-module">
    <div class="col-12">
        <div class="card top-title">
            <div class="card-header card-header-title">
                <h2>Daftar Role</h2>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card card-content">
            <div class="card-body">
                <form action="{{ url('sysrole/update/'. $id) }}" method="POST" id="formRole">
                    @csrf
                    <div class="col-md-6 text-end mt-3 mb-3">
                        <label>
                            <input type="checkbox" name="" class="checkall checkbox big-checkbox">
                        </label>
                        Pilih semua
                    </div>
                    <div class="table-responsive">
                        <table id="table-data" class="table table-striped card-table table-hover text-nowrap">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="45%">Modul</th>
                                    @if(sizeof($tasks_data) > 0)
                                            @foreach($tasks_data as $tasks_datas)
                                                <th>{{ $tasks_datas->task_data_name }}</th>
                                            @endforeach
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @if (sizeof($modules) == 0)
                                    <tr>
                                        <td colspan="7" align="center">Data modul kosong</td>
                                    </tr>
                                @else
                                    @foreach ($modules as $module)
                                        @php
                                            $tasks = explode(',', $module->task);
                                            $ids = explode(',', $module->taskid);
                                        @endphp
                                        <tr>
                                            <td width="5%">{{ $loop->iteration }}</td>
                                            <td width="40%">{{ $module->module_name }}</td>
                                            @foreach ($tasks_data as $tasks_datas)
                                                @php
                                                    $checked = '-';
                                                    $cell    = '-';

                                                    if (in_array($tasks_datas->task_data_name, $tasks)) { 
                                                        $index = array_search($tasks_datas->task_data_name, $tasks);
                                                        $isChecked = in_array($ids[$index], $roleTasks) ? "checked='checked'" : '';
                                                        $cell = "
                                                            <label class='switch' for='checkbox{$tasks_datas->task_data_name}{$ids[$index]}'>
                                                                <input class='check' id='checkbox{$tasks_datas->task_data_name}{$ids[$index]}' type='checkbox'
                                                                    value='{$ids[$index]}' name='task[]' {$isChecked}>
                                                                <div class='slider round'></div>
                                                            </label>";
                                                    }
                                                @endphp

                                                <td>{!! $cell !!}</td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="row px-3 py-3">
                        <div class="col-md-12 text-end">
                            <button type="submit" class="btn btn-success text-white"><i class="fe fe-edit fe-16"></i> Simpan</button>
                        </div> 
                    </div>
                </form>
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
@endsection

@section('script')
<script type="text/javascript">
    $('.checkall').click(function () {
        if ($(this).is(':checked')) {
            $('.check').prop('checked', true);
        } else {
            $('.check').prop('checked', false);
        }
    });

    document.getElementById('formRole').addEventListener('submit', function(e) {
        e.preventDefault(); // cegah submit langsung

        Swal.fire({
            title: 'Menyimpan...',
            text: 'Harap tunggu sebentar',
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // submit form setelah swal tampil
        setTimeout(() => {
            e.target.submit();
        }, 500); // kasih jeda biar swal sempet muncul
    });
</script>
@endsection
