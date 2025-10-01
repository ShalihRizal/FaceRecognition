@extends('layouts.app')
@section('title', 'Tambah Data Wajah')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-6">
                        <h1 class="h3">Tambah Data Pengenalan Wajah</h1>
                    </div>
                    <div class="col-md-6 text-end">
                        <a href="{{ url('face') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>

            @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle"></i> {{ session('error') }}
            </div>
            @endif

            @if(session('message'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> {{ session('message') }}
            </div>
            @endif

            <div class="card-body">
                <!-- User Info Section -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <h5><i class="fas fa-user"></i> Informasi User</h5>
                            <p class="mb-1"><strong>Nama:</strong> {{ Auth::user()->user_name }}</p>
                            <p class="mb-0">
                                <strong>Status Foto Profil:</strong>
                                @if(Auth::user()->user_image)
                                <span class="text-success">Tersedia</span> - Sistem akan membandingkan wajah dengan foto profil Anda
                                @else
                                <span class="text-warning">Tidak tersedia</span> - Sistem tidak dapat membandingkan dengan foto profil
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <form action="{{ url('face/store') }}" method="POST" enctype="multipart/form-data" id="faceForm">
                    @csrf
                    <input type="hidden" name="face_image" id="face_image">

                    <!-- Camera Section -->
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <div class="alert alert-warning">
                                <i class="fas fa-info-circle"></i>
                                Pastikan wajah berada dalam area lingkaran dan pencahayaan cukup.
                                @if(Auth::user()->user_image)
                                Sistem akan membandingkan area wajah dengan foto profil Anda. Minimal kemiripan 70%.
                                @endif
                            </div>

                            <div class="camera-container position-relative border rounded p-3 mb-3" style="max-width: 640px; margin: 0 auto;">
                                <video id="video" autoplay playsinline class="w-100"></video>
                                <div id="cameraOverlay" class="position-absolute top-0 start-0 w-100 h-100" style="pointer-events: none;"></div>
                            </div>

                            <div class="action-buttons mt-3">
                                <button id="startbutton" type="button" class="btn btn-primary btn-lg">
                                    <i class="fas fa-camera"></i> Ambil Foto
                                </button>
                                <button id="retakebutton" type="button" class="btn btn-warning btn-lg" style="display:none;">
                                    <i class="fas fa-redo"></i> Ambil Ulang
                                </button>
                            </div>

                            <!-- Preview Section -->
                            <div id="previewSection" class="mt-4" style="display:none;">
                                <h5>Preview Area Wajah yang akan Dicocokkan:</h5>
                                <img id="photoPreview" src="" alt="Preview Wajah" class="img-thumbnail" style="max-width: 320px;">
                                <p class="text-muted mt-2"><small>Hanya area wajah dalam lingkaran yang akan digunakan untuk pencocokan</small></p>
                            </div>
                        </div>
                    </div>

                    <canvas id="canvas" style="display:none;"></canvas>
                    <canvas id="croppedCanvas" style="display:none;"></canvas>

                    <!-- Submit Section -->
                    <div class="row mb-3 mt-4">
                        <div class="col-md-12 text-end">
                            <button type="submit" class="btn btn-success btn-lg" id="submitBtn" disabled>
                                <i class="fas fa-save"></i> Simpan Data Wajah
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    (function() {
        var width = 640;
        var height = 0;
        var streaming = false;
        var video = null;
        var canvas = null;
        var croppedCanvas = null;
        var startbutton = null;
        var retakebutton = null;
        var stream = null;
        var photoPreview = null;
        var previewSection = null;
        var submitBtn = null;
        var cameraOverlay = null;

        function startup() {
            video = document.getElementById('video');
            canvas = document.getElementById('canvas');
            croppedCanvas = document.getElementById('croppedCanvas');
            startbutton = document.getElementById('startbutton');
            retakebutton = document.getElementById('retakebutton');
            photoPreview = document.getElementById('photoPreview');
            previewSection = document.getElementById('previewSection');
            submitBtn = document.getElementById('submitBtn');
            cameraOverlay = document.getElementById('cameraOverlay');

            startCamera();

            video.addEventListener('canplay', function(ev) {
                if (!streaming) {
                    height = video.videoHeight / (video.videoWidth / width);
                    if (isNaN(height)) {
                        height = width / (4 / 3);
                    }
                    video.setAttribute('width', width);
                    video.setAttribute('height', height);
                    canvas.setAttribute('width', width);
                    canvas.setAttribute('height', height);
                    croppedCanvas.setAttribute('width', width);
                    croppedCanvas.setAttribute('height', height);
                    streaming = true;

                    // Create camera overlay after video dimensions are set
                    createCameraOverlay();
                }
            }, false);

            startbutton.addEventListener('click', function(ev) {
                takepicture();
                ev.preventDefault();
            }, false);

            retakebutton.addEventListener('click', function(ev) {
                startCamera();
                startbutton.style.display = "inline-block";
                retakebutton.style.display = "none";
                previewSection.style.display = "none";
                submitBtn.disabled = true;
                ev.preventDefault();
            }, false);

            // Form submission handler
            document.getElementById('faceForm').addEventListener('submit', function(e) {
                if (!document.getElementById('face_image').value) {
                    e.preventDefault();
                    alert('Silakan ambil foto terlebih dahulu');
                    return false;
                }

                // Show loading state
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
                submitBtn.disabled = true;
            });
        }

        function createCameraOverlay() {
            const overlay = cameraOverlay;
            const videoWidth = video.offsetWidth;
            const videoHeight = video.offsetHeight;

            // Create SVG overlay
            const svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
            svg.setAttribute('width', '100%');
            svg.setAttribute('height', '100%');
            svg.setAttribute('viewBox', `0 0 ${videoWidth} ${videoHeight}`);

            // Create circular face area
            const centerX = videoWidth / 2;
            const centerY = videoHeight / 2;
            const radius = Math.min(videoWidth, videoHeight) * 0.35; // 35% of smaller dimension

            // Outer circle (guide)
            const outerCircle = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
            outerCircle.setAttribute('cx', centerX);
            outerCircle.setAttribute('cy', centerY);
            outerCircle.setAttribute('r', radius);
            outerCircle.setAttribute('fill', 'none');
            outerCircle.setAttribute('stroke', '#00ff00');
            outerCircle.setAttribute('stroke-width', '2');
            outerCircle.setAttribute('stroke-dasharray', '10,5');
            outerCircle.setAttribute('opacity', '0.7');

            // Face area circle (akan di-crop)
            const faceCircle = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
            faceCircle.setAttribute('cx', centerX);
            faceCircle.setAttribute('cy', centerY);
            faceCircle.setAttribute('r', radius - 5);
            faceCircle.setAttribute('fill', 'none');
            faceCircle.setAttribute('stroke', '#ff0000');
            faceCircle.setAttribute('stroke-width', '2');
            faceCircle.setAttribute('opacity', '0.8');

            // Crosshair lines
            const lineHorizontal = document.createElementNS('http://www.w3.org/2000/svg', 'line');
            lineHorizontal.setAttribute('x1', centerX - radius);
            lineHorizontal.setAttribute('y1', centerY);
            lineHorizontal.setAttribute('x2', centerX + radius);
            lineHorizontal.setAttribute('y2', centerY);
            lineHorizontal.setAttribute('stroke', '#00ff00');
            lineHorizontal.setAttribute('stroke-width', '1');
            lineHorizontal.setAttribute('opacity', '0.5');
            lineHorizontal.setAttribute('stroke-dasharray', '5,5');

            const lineVertical = document.createElementNS('http://www.w3.org/2000/svg', 'line');
            lineVertical.setAttribute('x1', centerX);
            lineVertical.setAttribute('y1', centerY - radius);
            lineVertical.setAttribute('x2', centerX);
            lineVertical.setAttribute('y2', centerY + radius);
            lineVertical.setAttribute('stroke', '#00ff00');
            lineVertical.setAttribute('stroke-width', '1');
            lineVertical.setAttribute('opacity', '0.5');
            lineVertical.setAttribute('stroke-dasharray', '5,5');

            // Instruction text
            const text = document.createElementNS('http://www.w3.org/2000/svg', 'text');
            text.setAttribute('x', centerX);
            text.setAttribute('y', centerY + radius + 30);
            text.setAttribute('text-anchor', 'middle');
            text.setAttribute('fill', '#ffffff');
            text.setAttribute('font-size', '14');
            text.setAttribute('font-weight', 'bold');
            text.setAttribute('opacity', '0.8');
            text.textContent = 'Tempatkan wajah dalam area lingkaran merah';

            // Append all elements
            svg.appendChild(outerCircle);
            svg.appendChild(faceCircle);
            svg.appendChild(lineHorizontal);
            svg.appendChild(lineVertical);
            svg.appendChild(text);

            overlay.innerHTML = '';
            overlay.appendChild(svg);
        }

        function startCamera() {
            navigator.mediaDevices.getUserMedia({
                    video: {
                        width: {
                            ideal: 1280
                        },
                        height: {
                            ideal: 720
                        },
                        facingMode: 'user'
                    },
                    audio: false
                })
                .then(function(s) {
                    stream = s;
                    video.srcObject = stream;
                    video.play().then(() => {
                        // Recreate overlay when video starts playing
                        setTimeout(createCameraOverlay, 100);
                    });
                })
                .catch(function(err) {
                    console.log("Error accessing camera: " + err);
                    alert('Tidak dapat mengakses kamera. Pastikan Anda memberikan izin akses kamera.');
                });
        }

        function stopCamera() {
            if (stream) {
                stream.getTracks().forEach(track => track.stop());
            }
        }

        function takepicture() {
            var context = canvas.getContext('2d');
            var croppedContext = croppedCanvas.getContext('2d');

            if (width && height) {
                // Clear canvases
                context.clearRect(0, 0, width, height);
                croppedContext.clearRect(0, 0, width, height);

                // Draw original image to main canvas
                context.drawImage(video, 0, 0, width, height);

                // Crop to face area only (circular region)
                const faceImageData = cropToFaceArea(canvas);

                // Set the cropped image data to hidden input
                document.getElementById('face_image').value = faceImageData;

                // Show preview of cropped face area
                photoPreview.src = faceImageData;
                previewSection.style.display = "block";

                // Enable submit button
                submitBtn.disabled = false;

                // Stop camera and switch buttons
                stopCamera();
                startbutton.style.display = "none";
                retakebutton.style.display = "inline-block";
            }
        }

        function cropToFaceArea(sourceCanvas) {
            const tempCanvas = document.createElement('canvas');
            const tempCtx = tempCanvas.getContext('2d');

            // Gunakan ukuran yang lebih kecil untuk fokus pada wajah
            const cropSize = Math.min(sourceCanvas.width, sourceCanvas.height) * 0.5; // 50% dari ukuran terkecil
            const centerX = sourceCanvas.width / 2;
            const centerY = sourceCanvas.height / 2;

            // Set ukuran canvas hasil crop
            tempCanvas.width = cropSize;
            tempCanvas.height = cropSize;

            // Gambar langsung area wajah dari source canvas
            tempCtx.drawImage(
                sourceCanvas,
                centerX - cropSize / 2, // source x
                centerY - cropSize / 2, // source y  
                cropSize, // source width
                cropSize, // source height
                0, // destination x
                0, // destination y
                cropSize, // destination width
                cropSize // destination height
            );

            // Return image data dengan kualitas tinggi
            return tempCanvas.toDataURL('image/png', 0.95);
        }

        // Handle window resize
        window.addEventListener('resize', function() {
            if (streaming) {
                setTimeout(createCameraOverlay, 100);
            }
        });

        // Clean up camera when leaving page
        window.addEventListener('beforeunload', function() {
            stopCamera();
        });

        window.addEventListener('load', startup, false);
    })();
</script>

<style>
    .camera-container {
        background: #000;
        border-radius: 10px;
        overflow: hidden;
    }

    #video {
        border-radius: 8px;
        background: #000;
    }

    #cameraOverlay {
        border-radius: 8px;
    }

    /* Animation for the guide circle */
    @keyframes pulse {
        0% {
            opacity: 0.7;
        }

        50% {
            opacity: 0.3;
        }

        100% {
            opacity: 0.7;
        }
    }

    #cameraOverlay circle:nth-child(2) {
        animation: pulse 2s ease-in-out infinite;
    }

    /* Responsive design */
    @media (max-width: 768px) {
        .camera-container {
            max-width: 100% !important;
        }

        #video {
            max-width: 100%;
        }
    }
</style>
@endsection