@extends('layouts.app')
@section('title', 'Role')

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

@section('content')
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
                <h2>Role</h2>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card card-content">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="table-data" class="table-data w-100">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th width="80%">Grup</th>
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
                                    <a href="{{ url('sysrole/edit/'. $group->group_id ) }}" class="btn">
                                        <i data-feather="edit"></i>
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
@endsection
@section('script')
<script type="text/javascript">
var notyf = new Notyf({
        duration: 5000,
        position:{
            x:'right',
            y:'top'
        }
    });
    var msg = $('#msgId').html()
    if (msg !== undefined) {
        notyf.success(msg)
    }

</script>
@endsection
