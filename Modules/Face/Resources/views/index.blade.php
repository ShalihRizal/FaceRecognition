@extends('layouts.app')
@section('title', 'Voucher')

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
                <h2>Facerecog</h2>
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
                        <a href="face/create" class="btn btn-add-data btnAdd text-white">
                            <i data-feather="plus" width="16" height="16" class="me-2"></i>
                            Face
                        </a>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="table-data" class="table-data w-100">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Name</th>
                                <th>foto</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (sizeof($faces) == 0)
                            <tr>
                                <td colspan="6" align="center">Data kosong</td>
                            </tr>
                            @else
                            @foreach ($faces as $face)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $face->user_name }}</td>
                                <td width=>
                                    @if ($face->face_image)
                                    <img style="width: 50px; height: 50px; object-fit: cover; border-radius: 30px;" src="{{ asset('storage/' . $face->face_image) }}">
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
@endsection