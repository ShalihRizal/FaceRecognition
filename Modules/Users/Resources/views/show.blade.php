@extends('layouts.app')
@section('title', 'Patient Profile')

@section('content')
<div class="container-fluid">
    <div class="row">
        {{-- Profile Section --}}
       <div class="col-lg-4 col-md-4">
            <div class="card card-shadow">
                <div class="card-body text-center position-relative">
                    {{-- Foto Profil --}}
                    <form id="formPhotoUpdate" action="/profile/update-user/{{ $user->user_id }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="profile-container position-relative d-inline-block">
                            <img id="profileImage"
                                src="{{ asset('storage/profile/' . $user->user_image) }}"
                                class="rounded-circle mb-3 img-profile"
                                width="120" height="120" alt="Profile Photo">

                            {{-- Icon Pensil --}}
                            <label for="photoInput" class="edit-icon position-absolute">
                                <i class="fas fa-pen"></i>
                            </label>

                            {{-- Hidden File Input --}}
                            <input type="file" id="photoInput" name="user_image" class="d-none" accept="image/*">
                        </div>

                        {{-- Tombol aksi (hidden dulu) --}}
                        <div id="photoActionButtons" class="" style="display:none;">
                            <button type="submit" class="btn btn-add-data">Update</button>
                            <button type="button" class="btn button-cancel" onclick="cancelPhoto()">Cancel</button>
                        </div>
                    </form>

                    <h5 class="mb-1 mt-3">{{ $user->user_name }}</h5>
                    <p class="text-muted mb-0">{{ $user->user_email }}</p>
                </div>
            </div>
        </div>

        <div class="col-lg-8 col-md-8">
            <div class="card mb-3 card-shadow">
                <div class="card-header card-header-profile d-flex justify-content-between">
                    <span>General Information</span>
                    <a href="javascript:void(0)" id="btneditdatakaryawan" onclick="editDataKaryawan({{ $user->user_id }})">
                        <i class="fas fa-pen text-white"></i>
                    </a>
                </div>
                <div class="card-body p-2">
                    <form id="formUpdate{{ $user->user_id }}" action="/profile/update/{{ $user->user_id }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <table class="table table-borderless">
                            <tr>
                                <td>Nama Karyawan</td>
                                <td id="namaKaryawan{{ $user->user_id }}">
                                    : {{ $user->user_name }}
                                </td>
                            </tr>
                            <tr>
                                <td>Email Karyawan</td>
                                <td id="tglLahir{{ $user->user_id }}">
                                    : {{ $user->user_email }}
                                </td>
                            </tr>
                            <tr>
                                <td>Ijazah</td>
                                <td id="ijazahField{{ $user->user_id }}">
                                    <i class="fas fa-info-circle"></i>
                                </td>
                            </tr>
                        </table>

                        <div id="actionButtons{{ $user->user_id }}"  style="display:none;">
                            <button type="submit" class="btn btn-add-data">Update</button>
                            <button type="button" class="btn button-cancel" onclick="cancelEdit({{ $user->user_id }})">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


    </div>
    <div class="row">
        {{-- General Info & Anamnesis --}}
        <div class="col-lg-12">
            {{-- Tabs Section --}}
            <div class="card card-section-profile">
                <div class="card-body">
                    <ul class="nav nav-tabs" id="profileTabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="future-tab" data-bs-toggle="tab" href="#future" role="tab">Future Visits</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="past-tab" data-bs-toggle="tab" href="#past" role="tab">Past Visits</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="planned-tab" data-bs-toggle="tab" href="#planned" role="tab">Planned Treatments</a>
                        </li>
                    </ul>
                    <div class="tab-content mt-3" id="profileTabsContent">
                        <div class="tab-pane fade show active" id="future" role="tabpanel">
                            <div class="d-flex justify-content-between border-bottom pb-2 mb-2">
                                <div>
                                    <small class="text-muted">26 Sep 2023 - 11:00</small>
                                    <p class="mb-0">Treatment and cleaning of canals - Dr. Oksana Mat</p>
                                </div>
                                <span class="badge bg-success">Scheduled</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <div>
                                    <small class="text-muted">27 Jan 2023 - 11:00</small>
                                    <p class="mb-0">Teeth Whitening - Dr. Max Ochehed</p>
                                </div>
                                <span class="badge bg-success">Scheduled</span>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="past" role="tabpanel">
                            <p>No past visits</p>
                        </div>
                        <div class="tab-pane fade" id="planned" role="tabpanel">
                            <p>No planned treatments</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
@section('script')
    <script>

        function editDataKaryawan(id) {
            // Ambil isi teks Nama Karyawan lalu ubah jadi input
            let nama = document.getElementById("namaKaryawan"+id).innerText.replace(":", "").trim();
            document.getElementById("namaKaryawan"+id).innerHTML = 
                '<div class="form-floating"><input type="text" class="form-control p-1" name="user_name" value="'+nama+'"></div>';

            // Ambil isi teks Email Karyawan lalu ubah jadi input email
            let email = document.getElementById("tglLahir"+id).innerText.replace(":", "").trim();
            document.getElementById("tglLahir"+id).innerHTML = 
                '<div class="form-floating"><input type="email" class="form-control p-1" name="user_email" value="'+email+'"></div>';

            // Ganti tombol ijazah jadi input file
            document.getElementById("ijazahField"+id).innerHTML = 
                '<input type="file" name="ijazah" class="form-control">';

            // Tampilkan tombol Update + Cancel
            document.getElementById("actionButtons"+id).style.display = "flex";
            document.getElementById("btneditdatakaryawan").style.display = "none";
            document.getElementById("actionButtons"+id).classList.add("gap-2", "mt-2");

        }

        function cancelEdit(id) {
            // reload halaman biar kembali ke kondisi awal
            location.reload();
        }

        document.addEventListener("DOMContentLoaded", function(){
            document.querySelectorAll("form[id^='formUpdate'], #formPhotoUpdate").forEach(function(form){
                form.addEventListener("submit", function(e){
                    // tampilkan Swal loading
                    Swal.fire({
                        title: 'Updating...',
                        text: 'Please wait while we save your changes',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading()
                        }
                    });
                });
            });
        });

        document.addEventListener("DOMContentLoaded", function() {
            @if(session('successMessage'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ session('successMessage') }}',
                    showConfirmButton: false,
                    timer: 2000
                });
            @endif

            @if(session('errorMessage'))
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: '{{ session('errorMessage') }}',
                    showConfirmButton: true
                });
            @endif
        });

        const photoInput = document.getElementById("photoInput");
        const profileImage = document.getElementById("profileImage");
        const photoActionButtons = document.getElementById("photoActionButtons");
        let oldSrc = profileImage.src;

        // Saat pilih file → tampilkan preview + tombol aksi
        photoInput.addEventListener("change", function() {
            if (this.files && this.files[0]) {
                let reader = new FileReader();
                reader.onload = function(e) {
                    profileImage.src = e.target.result;
                    photoActionButtons.style.display = "flex"; // munculkan tombol
                }
                reader.readAsDataURL(this.files[0]);
            }
        });

        // Cancel → balikin foto lama + hide tombol
        function cancelPhoto() {
            profileImage.src = oldSrc;
            photoInput.value = ""; // reset input file
            photoActionButtons.style.display = "none";
        }
        
    </script>

@endsection