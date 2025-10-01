@extends('layouts.app')
@push('title')
    Dashboard
@endpush
@section('content')
    <div class="row">
        <div class="col-md-12 col-lg-12 col-12">
            <div class="card card-welcome">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8 title-welcome d-flex flex-column justify-content-center">
                            <h2>Welcome,</h2>
                            <h1>{{ auth()->user()->user_name }}</h1>
                        </div>
                        <div class="col-md-4 title-welcome d-flex flex-column justify-content-center">
                            <img src="{{asset('img')}}/welcome.png" alt="image" class="img-fluid">
                        </div>
                    </div>
                </div>   
            </div>   
        </div>
    </div>
    <div class="row">
        <div class="col-lg-8">
            <div class="card card-grafik">
                <div class="card-body">
                    <h5 class="card-title d-flex align-items-center gap-2 mb-4">
                        Traffic Overview
                        <span>
                            <iconify-icon icon="solar:question-circle-bold" class="fs-7 d-flex text-muted" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="tooltip-success" data-bs-title="Traffic Overview"></iconify-icon>
                        </span>
                    </h5>
                    <div id="traffic-overview" >
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card card-grafik">
                <div class="card-body text-center">
                <img src="../assets/images/backgrounds/product-tip.png" alt="image" class="img-fluid" width="205">
                <h4 class="mt-7">Productivity Tips!</h4>
                <p class="card-subtitle mt-2 mb-3">Duis at orci justo nulla in libero id leo
                    molestie sodales phasellus justo.</p>
                    <button class="btn btn-primary mb-3">View All Tips</button>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card overflow-hidden hover-img">
                <div class="position-relative">
                <a href="javascript:void(0)">
                    <img src="../assets/images/blog/blog-img1.jpg" class="card-img-top" alt="matdash-img">
                </a>
                <span class="badge text-bg-light text-dark fs-2 lh-sm mb-9 me-9 py-1 px-2 fw-semibold position-absolute bottom-0 end-0">2
                    min Read</span>
                <img src="../assets/images/profile/user-3.jpg" alt="matdash-img" class="img-fluid rounded-circle position-absolute bottom-0 start-0 mb-n9 ms-9" width="40" height="40" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Georgeanna Ramero">
                </div>
                <div class="card-body p-4">
                <span class="badge text-bg-light fs-2 py-1 px-2 lh-sm  mt-3">Social</span>
                <a class="d-block my-4 fs-5 text-dark fw-semibold link-primary" href="">As yen tumbles, gadget-loving
                    Japan goes
                    for secondhand iPhones</a>
                <div class="d-flex align-items-center gap-4">
                    <div class="d-flex align-items-center gap-2">
                    <i class="ti ti-eye text-dark fs-5"></i>9,125
                    </div>
                    <div class="d-flex align-items-center gap-2">
                    <i class="ti ti-message-2 text-dark fs-5"></i>3
                    </div>
                    <div class="d-flex align-items-center fs-2 ms-auto">
                    <i class="ti ti-point text-dark"></i>Mon, Dec 19
                    </div>
                </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card overflow-hidden hover-img">
                <div class="position-relative">
                <a href="javascript:void(0)">
                    <img src="../assets/images/blog/blog-img2.jpg" class="card-img-top" alt="matdash-img">
                </a>
                <span class="badge text-bg-light text-dark fs-2 lh-sm mb-9 me-9 py-1 px-2 fw-semibold position-absolute bottom-0 end-0">2
                    min Read</span>
                <img src="../assets/images/profile/user-2.jpg" alt="matdash-img" class="img-fluid rounded-circle position-absolute bottom-0 start-0 mb-n9 ms-9" width="40" height="40" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Georgeanna Ramero">
                </div>
                <div class="card-body p-4">
                <span class="badge text-bg-light fs-2 py-1 px-2 lh-sm  mt-3">Gadget</span>
                <a class="d-block my-4 fs-5 text-dark fw-semibold link-primary" href="">Intel loses bid to revive
                    antitrust case
                    against patent foe Fortress</a>
                <div class="d-flex align-items-center gap-4">
                    <div class="d-flex align-items-center gap-2">
                    <i class="ti ti-eye text-dark fs-5"></i>4,150
                    </div>
                    <div class="d-flex align-items-center gap-2">
                    <i class="ti ti-message-2 text-dark fs-5"></i>38
                    </div>
                    <div class="d-flex align-items-center fs-2 ms-auto">
                    <i class="ti ti-point text-dark"></i>Sun, Dec 18
                    </div>
                </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card overflow-hidden hover-img">
                <div class="position-relative">
                <a href="javascript:void(0)">
                    <img src="../assets/images/blog/blog-img3.jpg" class="card-img-top" alt="matdash-img">
                </a>
                <span class="badge text-bg-light text-dark fs-2 lh-sm mb-9 me-9 py-1 px-2 fw-semibold position-absolute bottom-0 end-0">2
                    min Read</span>
                <img src="../assets/images/profile/user-3.jpg" alt="matdash-img" class="img-fluid rounded-circle position-absolute bottom-0 start-0 mb-n9 ms-9" width="40" height="40" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Georgeanna Ramero">
                </div>
                <div class="card-body p-4">
                <span class="badge text-bg-light fs-2 py-1 px-2 lh-sm  mt-3">Health</span>
                <a class="d-block my-4 fs-5 text-dark fw-semibold link-primary" href="">COVID outbreak deepens as more
                    lockdowns
                    loom in China</a>
                <div class="d-flex align-items-center gap-4">
                    <div class="d-flex align-items-center gap-2">
                    <i class="ti ti-eye text-dark fs-5"></i>9,480
                    </div>
                    <div class="d-flex align-items-center gap-2">
                    <i class="ti ti-message-2 text-dark fs-5"></i>12
                    </div>
                    <div class="d-flex align-items-center fs-2 ms-auto">
                    <i class="ti ti-point text-dark"></i>Sat, Dec 17
                    </div>
                </div>
                </div>
            </div>
        </div>
        <div class="py-6 px-6 text-center">
            <p class="mb-0 fs-4">Design and Developed by <a href="https://adminmart.com/" target="_blank"
                class="pe-1 text-primary text-decoration-underline">AdminMart.com</a>Distributed by <a href="https://themewagon.com/" target="_blank"
                class="pe-1 text-primary text-decoration-underline">ThemeWagon</a></p>
        </div>
    </div>
@endsection
@section('script')

@endsection
